<div class="col-12 col-md-8 mx-auto">
    <div class="card mt-3" wire:ignore>
        <div class="card-body">
            <div class="row">
                <div class="col-auto align-content-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="rounded-circle img-thumbnail" style="max-height: 7rem" alt="">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 5rem"></i>
                    @endif
                </div>
                <div class="col align-content-center">
                    <h3>{{ auth()->user()->name }}</h3>
                    <p class="text-muted">
                        {{ __("Reg Number").": ".auth()->user()->reg_id }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3" wire:ignore>
        <div class="card-body">
            <div class="card-title">
                <h4>{{ __("Notifications") }}</h4>
            </div>
            <div class="row p-3">
                @if (!auth()->user()->routeNotificationForTelegram())
                    <div class="col mx-auto text-center">
                        <p>{{ __("Connect your account with telegram to enable notifications") }}</p>
                        <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="{{ app(\App\Settings\AppSettings::class)->telegram_bot_name }}" data-size="large" data-auth-url="{{ route("telegram.connect") }}" data-request-access="write"></script>
                    </div>
                @else
                    <div class="col-auto"><i class="bi bi-check-circle-fill text-success fs-1"></i></div>
                    <div class="col align-content-center">{{ __("You have successfully connected your Telegram account") }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <form wire:submit="saveNotifications">
            <div class="card-header text-md-center">
                <h5 class="card-title m-2">{{ __("Personal settings") }}</h5>
            </div>
            <div class="card-body">
                <x-livewire-alert name="notifications" />
                <div class="col-12">
                    <div class="row m-2">
                        <div class="col-7 text-center text-md-start pb-md-0 pb-3 my-auto"></div>
                        <div class="col-5 col-md-5">
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    <i class="bi bi-envelope fs-5" title="{{ __("Email Notifications") }}"></i>
                                </div>
                                <div class="col-6 col-md-6">
                                    <i class="bi bi-telegram fs-5" title="{{ __("Telegram Notifications") }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($notificationType as $notification => $data)
                        <div class="row m-2">
                            <div class="col-7 text-center text-md-start pb-md-0 pb-3 my-auto">
                                {{ $data['name'] }}
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input fs-5" type="checkbox" wire:model="notificationForm.types.{{ $notification }}.mail">
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input fs-5" type="checkbox" wire:model="notificationForm.types.{{ $notification }}.telegram" @disabled(!auth()->user()->routeNotificationForTelegram())>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary m-1">{{ __("Save") }}</button>
            </div>
        </form>
    </div>


    <div class="card mt-3">
        <div class="card-header position-relative text-md-center">
            <h5 class="card-title m-2">{{ __("Subscribable Calendars") }}</h5>
            @if($calendars->count() > 0)
                @can("create", \App\Models\UserCalendar::class)
                    <button wire:click.debounce="addCalendar" wire:loading.attr="disabled" class="btn btn-light position-absolute top-50 end-0 translate-middle-y me-3">
                        <i class="bi bi-plus icon-link"></i>
                        <span class="d-none d-xxl-inline">
                            {{ __("Add Calendar") }}
                        </span>
                    </button>
                @endcan
            @endif
        </div>
        <div class="card-body overflow-x-auto">
            <x-livewire-alert name="calendars" />
            <p>
                {{ __("Here you can create a calendar link which you can subscribe to in the calendar app on your smartphone.") }}
                {{ __("All selected appointments are then displayed and automatically updated in the event of a change.") }}
            </p>
            <p>
                {{ __("In addition, you will also receive the set notifications, which also work offline once it's synchronized on your device!") }}
            </p>
            @if($calendars->count() > 0)
                <table class="table table-responsive table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{{ __("Calendar") }}</th>
                        <th>{{ __("Settings") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($calendars AS $calendar)
                        <tr>
                            <td class="text-center align-middle">
                                <div>
                                    <span class="badge bg-dark-subtle fs-6 my-2">
                                        {{ $calendar->name }}
                                    </span>
                                </div>
                                <button class="btn btn-outline-light m-1" wire:click="showCalendar('{{$calendar->id}}')">{{ __("Show") }}</button>
                                <button class="btn btn-outline-danger m-1" wire:click="removeCalendar('{{ $calendar->id }}')" title="{{ __("Remove") }}"><i class="bi bi-trash icon-link"></i></button>
                            </td>
                            <td>
                                <div class="row row-cols-2 g-3" wire:key="{{ $calendar->id }}">
                                    @foreach(\App\Enums\UserCalendarSettings::cases() AS $setting)
                                        @continue($setting == \App\Enums\UserCalendarSettings::SHOW_SHIFTS AND !auth()->user()->hasPermission(\App\Enums\Permission::MANAGE_SHIFTS))
                                        <div class="col">
                                            <label for="{{ $loop->index }}{{$calendar->id}}">{{ $setting->name() }}</label>
                                        </div>
                                        <div class="col d-flex align-self-center">
                                            <input class="form-check-input" type="checkbox"
                                                   wire:model="calendarSettings.{{$calendar->id}}.{{$setting->name}}" id="{{ $loop->index }}{{$calendar->id}}"
                                                   wire:loading.attr="disabled" wire:target="calendarSettings"
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    <button wire:click.debounce="saveCalendars" wire:loading.attr="disabled" class="btn btn-primary">Speichern</button>
                </div>

                <x-modal.livewire-modal id="show" :title="__('Show calendar')" class="modal-lg" type="alert">
                    {{ __("Go to your calendar app, select \"subscribe\" and enter the following URL:") }}
                    @if($selectedCalendar?->exists())
                        <div class="input-group mt-3">
                            <button class="btn btn-secondary" id="clipboard-icon" onclick="navigator.clipboard.writeText('{{ route("user-calendar.show", $selectedCalendar) }}')">
                                <i class="bi bi-clipboard icon-link"></i>
                            </button>
                            <input type="text" class="form-control" readonly aria-label="{{ __('Calendar URL') }}" aria-describedby="clipboard-icon"
                                   value="{{ route("user-calendar.show", $selectedCalendar) }}" onfocus="this.select()">
                        </div>
                    @endif
                </x-modal.livewire-modal>

                <x-modal.livewire-modal id="remove" :title="__('Remove Calendar')" type="confirm" action="destroyCalendar">
                    {{ __("Do you really want to remove this calendar?") }}
                </x-modal.livewire-modal>
            @else
                <div class="text-center">
                    <button wire:click="addCalendar()" class="btn btn-light">
                        <i class="bi bi-plus icon-link"></i> {{ __("Add Calendar") }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
