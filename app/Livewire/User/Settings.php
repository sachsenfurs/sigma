<?php

namespace App\Livewire\User;

use App\Enums\UserCalendarSettings;
use App\Facades\NotificationService;
use App\Livewire\Traits\HasModal;
use App\Livewire\User\Forms\SettingsNotificationForm;
use App\Models\UserCalendar;
use Illuminate\Support\Collection;
use Livewire\Component;

class Settings extends Component
{
    use HasModal;

    public array $notificationType = [];
    public Collection $calendars;

    public array $calendarSettings = [];
    public ?UserCalendar $selectedCalendar = null;

    public SettingsNotificationForm $notificationForm;

    public function mount() {
        foreach(NotificationService::userNotifications() AS $notification => $name) {
            $this->notificationType[$name] = [
                'name' => NotificationService::resolveClass($name)::getName(),
                'channels' => auth()->user()->notification_channels[$name] ?? [],
            ];
        }

        $this->notificationForm->fill([
            'types' => collect(auth()->user()->notification_channels)
                ->map(function($type) {
                    return collect($type)->mapWithKeys(function($channel) {
                        return [$channel => true];
                    });
                })
        ]);

        $this->calendars = auth()->user()->calendars;

        foreach($this->calendars AS $calendar) {
            foreach($calendar->settings AS $setting) {
                $this->calendarSettings[$calendar->id][$setting] = true;
            }
        }
    }

    public function saveNotifications() {
        $this->notificationForm->save();
        session()->flash("notifications", __("Settings saved!"));
    }

    public function addCalendar(): void {
        $this->authorize("create", UserCalendar::class);
        auth()->user()->calendars()->create([
            'settings' => collect(UserCalendarSettings::cases())->map(fn($case) => $case->name)
        ]);
        $this->mount();
    }

    public function showCalendar(string $calendarId): void {
        $this->selectedCalendar = UserCalendar::findOrFail($calendarId);
        $this->showModal("show");
    }

    public function removeCalendar(string $calendarId): void {
        $this->selectedCalendar = UserCalendar::findOrFail($calendarId);
        $this->showModal("remove");
    }

    public function destroyCalendar(): void {
        $this->authorize("delete", $this->selectedCalendar);
        $this->selectedCalendar?->delete();
        $this->hideModal("remove");
        $this->mount();
        $this->selectedCalendar = null;
        session()->flash("calendars", __("Calendar removed!"));
    }

    public function saveCalendars() {
        foreach($this->calendarSettings AS $calendarId => $settings) {
            if($calendar = UserCalendar::find($calendarId)) {
                $this->authorize("update", $calendar);
                $calendar->update([
                    // filter by value => "true"
                    'settings' => collect($settings)->filter(fn($s) => $s)->keys()
                ]);
            }
        }
        session()->flash("calendars", __("Settings saved!"));
    }

    public function render() {
        return view('livewire.user.settings');
    }
}
