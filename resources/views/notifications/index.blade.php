@extends('layouts.app')
@section('title', "Notifications")
@section('content')
    <div class="container" style="min-height: 50vh;">
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="row">
                <div class="col-0 col-md-9"></div>
                <div class="col-12 col-md-3 d-flex flex-row justify-content-center justify-content-md-end">
                    <div class="m-4">
                        <form action="{{ route('notifications.update') }}" method="POST">
                            @method('PATCH')
                            @csrf

                            <button type="submit" class="btn btn-primary">
                                {{ __('Mark as read') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @forelse ($notifications as $notification)
            <div class="card m-4 rounded-4">
                <div class="row">
                    <div class="col-4 col-md-2 text-center d-flex" style="font-size: 3rem; justify-content: center; align-items: center;">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="col-8 col-md-10 p-3">
                            @switch($notification->data['type'])
                                @case('chat_new_message')
                                    <a class="btn p-2 w-75" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                        <h2 class="p-none m-none">{{ __('New Chat Message') }}</h2>
                                        <p class="p-none m-none">{{ __('in the chat with the :department department', ["department" => __($notification->data['department'])]) }}</p>
                                    </a>
                                    @break
                                @case('sig_favorite_reminder')
                                    <a class="btn p-2 w-75" href="{{ route("timetable-entry.show", ['entry' => $this->data['timetableEntryid']]) }}">
                                        <h2 class="p-none m-none">{{ __('Event starts soon') }}</h2>
                                        <p class="p-none m-none">{{ __('your favorite event :event starts in :minutes_before minutes!', ["event" => $notification->data['eventName'], "minutes_before" => $notification->data['minutes_before']]) }}</p>
                                    </a>
                                    @break
                                @case('sig_timeslot_reminder')
                                    <a class="btn p-2 w-75" href="{{ route("timetable-entry.show", ['entry' => $this->data['sigTimeslotId']]) }}">
                                        <h2 class="p-none m-none">{{ __('Booked timeslot reminder') }}</h2>
                                        <p class="p-none m-none">{{ __('your booked timeslot of the event :event starts in :minutes_before minutes!', ["event" => $notification->data['eventName'], "minutes_before" => $notification->data['minutes_before']]) }}</p>
                                    </a>
                                    @break
                                @case('timetable_entry_cancelled')
                                    <a class="btn p-2 w-75" href="{{ route('public.timeslot-show', ['entry' => $data['timetableEntryId']]) }}">
                                        <h2 class="p-none m-none">{{ __('Event cancelled') }}</h2>
                                        <p class="p-none m-none">{{ __('The event :event was cancelled!', ["event" => $notification->data['eventName']]) }}</p>
                                    </a>
                                    @break
                                @case('timetable_entry_location_changed')
                                    <a class="btn p-2 w-75" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                        <h2 class="p-none m-none">{{ __('Event new location') }}</h2>
                                        <p class="p-none m-none">{{ __('The location for the event :event has changed!', ["event" => $notification->data['eventName']]) }}</p>
                                        <p class="p-none m-none">{{ __('New location: ') . $notification->data['newLocation'] }}</p>
                                    </a>
                                    @break
                                @case('timetable_entry_time_changed')
                                    <a class="btn p-2 w-75" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                        <h2 class="p-none m-none">{{ __('Event time changed') }}</h2>
                                        <p class="p-none m-none">{{ __('The time of the event :event has changed!', ["event" => $notification->data['eventName']]) }}</p>
                                        <p class="p-none m-none">{{ __('New time: :startTime - :endTime', ["startTime" => Carbon::parse($notification->data['newStartTime'])->format("H:i"), "endTime" => Carbon::parse($notification->data['newEndTime']])->format("H:i")) }}</p>
                                    </a>
                                    @break
                                @default
                            @endswitch
                    </div>
                </div>
            </div>
        @empty
            <div class="container text-center m-5">
                <p>{{ __("No new Notifications") }}</p>
            </div>
        @endforelse
    </div>
@endsection
