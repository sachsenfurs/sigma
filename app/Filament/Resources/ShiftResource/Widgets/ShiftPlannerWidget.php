<?php

namespace App\Filament\Resources\ShiftResource\Widgets;

use App\Enums\Necessity;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\SigEventResource;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use App\Models\UserRole;
use App\Models\UserShift;
use App\Settings\AppSettings;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Guava\Calendar\Actions\CreateAction;
use Guava\Calendar\Actions\DeleteAction;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\CalendarResource;
use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class ShiftPlannerWidget extends CalendarWidget implements HasForms
{

//    protected static string $view = 'filament.resources.shift-resource.widgets.shift-planner-widget';
    protected string $calendarView = "resourceTimeGridDay";
    protected bool $eventClickEnabled = true;
    protected ?string $defaultEventClickAction = "edit";
    protected bool $eventDragEnabled = true;
    protected bool $eventResizeEnabled = true;
    protected bool $dateClickEnabled = true;
    protected bool $dateSelectEnabled = true;

    public ?int $user_role_id = null;
    public ?UserRole $userRole = null;
    public ?int $sig_location_id = null;


    public function mount(): void {
        $this->user_role_id     ??= session('calendar_selected_user_role_id', auth()->user()->roles->first()?->id ?? 0);
        $this->sig_location_id  ??= session('calendar_selected_sig_location_id');
        $this->userRole         = UserRole::with("shiftTypes")->find($this->user_role_id);
    }

    public function getHeading(): HtmlString|string {
        return new HtmlString($this->form->toHtml()); // HACKERMAN! XD (the calendar widget doesn't support displaying a form by default ...)
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Select::make('user_role_id')
                    ->label(__('Department'))
                    ->placeholder(__("Select"))
                    ->options(fn() => auth()->user()->roles->pluck("name", "id"))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function($state) {
                        session()->put('calendar_selected_user_role_id', $state);
                        $this->userRole = UserRole::with("shiftTypes")->find($state);
                        return $this->refreshResources()->refreshRecords();
                    }),
                Select::make("sig_location_id")
                    ->label(__("Location"))
                    ->searchable()
                    ->afterStateUpdated(function($state) {
                        session()->put('calendar_selected_sig_location_id', $state);
                        return $this->refreshRecords();
                    })
                    ->live()
                    ->options(SigLocation::used()->get()->mapWithKeys(fn($l) => [$l->id => $l->name_localized . " - " . $l->description_localized]))
            ])
            ;
    }

    public function getDateSelectContextMenuActions(): array {
        return [
            CreateAction::make('create')
                ->model(Shift::class)
                ->modelLabel(__("Shift"))
                ->createAnother(false)
                ->form($this->getSchema())
                ->authorize("deleteAny", Shift::class)
                ->mountUsing(function ($arguments, $form) {
                    return $form->fill([
                        'start'             => data_get($arguments, 'startStr'),
                        'end'               => data_get($arguments, 'endStr'),
                        'shift_type_id'     => data_get($arguments, 'resource.id'),
                        'sig_location_id'   => $this->sig_location_id,
                        'necessity'         => Necessity::MANDATORY,
                        'max_user'          => 1
                    ]);
                }),
        ];
    }

    public function onEventDrop(array $info = []): bool {
        if(data_get($info, "event.extendedProps.model") != Shift::class)
            return false;
        if(!Gate::check("delete", Shift::findOrFail(data_get($info, "event.extendedProps.key"))))
            return false;

        parent::onEventDrop($info);
        if($shiftType = ShiftType::find(data_get($info, 'event.resourceIds.0'))) {
            /**
             * @var Shift $record
             */
            $record = $this->getEventRecord();
            $record->type()->associate($shiftType);
            $record->start = Carbon::parse(data_get($info, "event.start"))->tz(config("app.timezone"));
            $record->end = Carbon::parse(data_get($info, "event.end"))->tz(config("app.timezone"));
            $record->save();
            $this->refreshRecords();
            return true;
        }
        return false;
    }

    public function onEventResize(array $info = []): bool {
        return $this->onEventDrop($info);
    }


    public function getOptions(): array {
        return [
            'headerToolbar' => [
                'start' => 'prev,next today',
                'center' => 'title',
                'end' => 'resourceTimeGridDay,resourceTimelineDay,dayGridMonth'
            ],
            'buttonText' => [
                'resourceTimeGridDay'   => __("Horizontal"),
                'resourceTimelineDay'   => __("Vertical"),
                'dayGridMonth'          => __("Month"),
                'today'                 => __("Today"),
            ],
            'titleFormat' => [
                'day' => 'numeric',
                'month' => 'long',
                'weekday' => 'long',
            ],
            'nowIndicator' => true,
            'slotDuration' => "00:15:00",
            'slotHeight' => 12,
            'slotMinTime' => "07:00:00",
            'slotMaxTime' => "32:00:00",
            'slotLabelInterval' => "01:00",
            'eventResizableFromStart' => true,
            'allDaySlot' => false,
            'height' => "75vh",
            'date' => (function() {
                $first = TimetableEntry::orderBy('start')->first();
                if(Carbon::parse($first?->start)->isAfter(Carbon::now()))
                    return $first->start->format("Y-m-d");
                return app(AppSettings::class)->event_start->format("Y-m-d");
            })(),
        ];
    }

    public function getEventContent(): null|string|array {
        return new HtmlString('<span x-text="(new Date(event.start)).toLocaleTimeString(undefined, { hour: \'2-digit\', minute: \'2-digit\' })"></span>
            <span x-show="!event.resourceIds.includes(\'0\') && event.editable === false">🔒</span>
            <span x-show="event.extendedProps.team">‼️</span>
            <span x-text="event.extendedProps.username"></span>
            <span x-show="event.title"> | </span>
            <span x-text="event.title"></span>
            <div x-show="event.extendedProps.sigLocation">
                <i><span x-text="event.extendedProps.sigLocation"></span></i>
            </div>');

    }

    public function getEvents(array $fetchInfo = []): Collection|array {
        $start      = Carbon::parse($fetchInfo['startStr'] ?? now());
        $end        = Carbon::parse($fetchInfo['endStr'] ?? now());
        $query      = TimetableEntry::with(["sigEvent", "sigLocation"])->whereBetween("start", [$start, $end]);
        if($this->sig_location_id)
            $query->where("sig_location_id", $this->sig_location_id);

        $shifts = Shift::whereHas("type.userRole", fn($query) => $query->where("id", $this->user_role_id))
            ->with(["users", "sigLocation", "userShifts"])
            ->when($this->sig_location_id, fn(Builder $query) => $query->where("sig_location_id", $this->sig_location_id))
            ->get()
            ->map(fn(Shift $shift) => CalendarEvent::make()
                    ->key($shift->id)
                    ->styles([
                        'font-size: 44px'
                    ])
                    ->classNames([
                        'opacity-50' => $shift->userShifts->filter(fn(UserShift $u) => $u->user_id == auth()->id())->count() == 0 AND !$shift->team,
                        'border border-4 border-red-500' => $shift->userShifts->filter(fn(UserShift $u) => $u->user_id == auth()->id())->count() > 0 OR $shift->team,
                    ])
                    ->backgroundColor("rgb(". match($shift->necessity) {
                        Necessity::OPTIONAL => Color::Green[700],
                        Necessity::NICE_TO_HAVE => Color::Yellow[400],
                        Necessity::MANDATORY => Color::Red[700],
                        default => Color::Gray[400],
                        }.")")
                    ->textColor("rgb(". match($shift->necessity) {
                            Necessity::NICE_TO_HAVE => Color::Gray[900],
                            default => Color::Neutral[50],
                        } .")")
                    ->model(Shift::class)
                    ->title($shift->info ?? "")
                    ->start($shift->start->shiftTimezone("UTC"))
                    ->end($shift->end->shiftTimezone("UTC"))
                    ->editable(!$shift->locked)
                    ->resourceId($shift->shift_type_id)
                    ->extendedProp('username', $shift->team ? __("All") : "[".$shift->users->count()."/".$shift->max_user."] " . $shift->users->pluck("name")->join(", "))
                    ->extendedProp('startTime', $shift->start->format("H:i"))
                    ->extendedProp('sigLocation', $this->sig_location_id ? "" : $shift->sigLocation?->description_localized)
                    ->extendedProp('team', $shift->team)
                    ->action('edit')
            );

        $events = $query->get()
                ->map(fn($e) => CalendarEvent::make()
                    ->key($e->id)
                    ->title($e->sigEvent->name_localized)
                    ->start($e->start->shiftTimezone('UTC'))
                    ->end($e->end->shiftTimezone('UTC'))
                    ->resourceId(0)
                    ->editable(false)
                    ->model(TimetableEntry::class)
                    ->action('view')
//                    ->display('ghost')
                    ->backgroundColor('#cccccc')
                    ->textColor('#000000')
                    ->classNames(['scheduleEvent'])
                    ->extendedProp('startTime', $e->start->format("H:i"))
                    ->extendedProp('sigLocation', $e->sigLocation->description_localized)
                );
        return [...$events, ...$shifts];
    }


    public function viewAction(): Action {
        return parent::viewAction()->make("view")
            ->infolist([
                TextEntry::make("sigEvent.name_localized")
                    ->label(__("Event Name"))
                    ->inlineLabel(),
                TextEntry::make("sigLocation")
                    ->label(__("Location"))
                    ->formatStateUsing(fn($state) => $state->name_localized . " - " . $state->description_localized)
                    ->inlineLabel(),
                RepeatableEntry::make("sigEvent.departmentInfos")
                    ->label(__("Requirements to Department"))
                    ->placeholder(__("None"))
                    ->schema([
                        TextEntry::make("userRole")
                            ->inlineLabel()
                            ->label(__("Department"))
                            ->formatStateUsing(fn($state) => $state->name_localized)
                            ->badge(),
                        TextEntry::make('additional_info')
                            ->label(""),
                    ])
            ])
            ->extraModalFooterActions([
                ViewAction::make("view")
                    ->url(fn($record) => SigEventResource::getUrl("view", ['record' => $record->sig_event_id])),
            ])
            ->modelLabel(__("Timetable Entry"));
    }

    public function editAction(): Action {
        return parent::editAction()
            ->modelLabel(__("Shift"))
            ->extraModalFooterActions([
                DeleteAction::make()
                    ->modelLabel(__("Shift"))
                    ->authorize("delete"),
            ]);
    }

    public function getSchema(?string $model = null): ?array {
        if($model != null AND $model != Shift::class)
            return [];
        return [
            Grid::make()
                ->schema([
                    Select::make("users")
                        ->visibleOn("edit")
                        ->multiple()
                        ->columnSpanFull()
                        ->maxItems(fn($record) => $record->max_user ?? 1)
                        ->preload()
                        ->relationship("users", "name", fn(Builder $query) => $query->whereHas("roles", fn($query) => $query->where("user_roles.id", $this->user_role_id)))
                        ->label(__("User"))
                        ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                        ->searchable(['name', 'reg_id']),
                    TextInput::make("info")
                        ->columnSpanFull(),
                ]),

            Section::make(__("Manager Settings"))
                ->visible(auth()->user()->hasPermission(Permission::MANAGE_SHIFTS, PermissionLevel::DELETE))
                ->schema([
                    Grid::make()
                        ->schema([
                            Select::make("shift_type_id")
                                    ->required()
                                  ->label(__("Shift Type"))
                                  ->relationship("type", "name"),
                            Select::make('sig_location_id')
                                  ->relationship('sigLocation', "name")
                                  ->label(__("Location"))
                                  ->preload()
                                  ->default($this->sig_location_id)
                                  ->searchable(['name', 'name_en', 'description', 'description_en'])
                                  ->getOptionLabelFromRecordUsing(FormHelper::formatLocationWithDescription()),
                            DateTimePicker::make("start")
                                  ->required()
                                  ->label(__("Start Date"))
                                  ->before("end"),
                            DateTimePicker::make("end")
                                  ->required()
                                  ->after("start")
                                  ->label(__("End Date")),
                        ]),
                    Grid::make()
                        ->schema([
                            Select::make("necessity")
                                ->label(__("Necessity"))
                                ->options(Necessity::class),
                            TextInput::make('max_user')
                                ->label(__("Max. User"))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->default(1),
                            Toggle::make('team')
                                ->label(__("For all team member")),
                            Toggle::make('locked')
                                ->label(__("Locked")),
                        ]),
                ])
        ];
    }

    public function getResources(): array|Collection {
        return ($this->userRole
                        ?->shiftTypes
                        ?->map(fn($t) => CalendarResource::make($t->id)->title($t->name))
                    ?? collect())->prepend(
                        CalendarResource::make("0")->title(__("Schedule"))
        );
    }


}
