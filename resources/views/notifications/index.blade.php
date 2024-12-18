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
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 col-md-2 text-center d-flex" style="font-size: 3rem; justify-content: center; align-items: center;">
                            <i class="bi bi-bell"></i>
                        </div>
                        <div class="col-8 col-md-10">
                                @switch($notification->type)
                                    @case(\App\Notifications\NewChatMessage::class)
                                        <a class="btn p-2 w-75" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                            <h2 class="p-none m-none">{{ __('New Chat Message') }}</h2>
                                            <p class="p-none m-none">{{ __('in the chat with the :department department', ["department" => __($notification->data['department'])]) }}</p>
                                        </a>
                                        @break
                                    @case(\App\Notifications\SigFavorite\SigFavoriteReminder::class)
                                        <a class="btn p-2 w-75" href="{{ route("timetable-entry.show", ['entry' => $notification->data['timetableEntryid']]) }}">
                                            <h2 class="p-none m-none">{{ __('Event starts soon') }}</h2>
                                            <x-markdown>
                                                {{
                                                    __($notification['type']::$text, [
                                                        "event" => $notification->data['eventName'],
                                                        "minutes_before" => $notification->data['minutes_before']
                                                    ])
                                                }}
                                            </x-markdown>
                                        </a>
                                        @break
                                    @case(\App\Notifications\SigTimeslot\SigTimeslotReminder::class)
                                        <a class="btn p-2 w-75" href="{{ route("timetable-entry.show", ['entry' => $notification->data['sigTimeslotId']]) }}">
                                            <h2 class="p-none m-none">{{ __('Booked timeslot reminder') }}</h2>
                                            <p class="p-none m-none">{{ __('your booked timeslot of the event :event starts in :minutes_before minutes!', ["event" => $notification->data['eventName'], "minutes_before" => $notification->data['minutes_before']]) }}</p>
                                        </a>
                                        @break
                                    @case(\App\Notifications\TimetableEntry\TimetableEntryCancelled::class)
                                        <a class="btn p-2 w-75" href="{{ route('public.timeslot-show', ['entry' => $data['timetableEntryId']]) }}">
                                            <h2 class="p-none m-none">{{ __('Event cancelled') }}</h2>
                                            <p class="p-none m-none">{{ __('The event :event was cancelled!', ["event" => $notification->data['eventName']]) }}</p>
                                        </a>
                                        @break
                                    @case(\App\Notifications\TimetableEntry\TimetableEntryLocationChanged::class)
                                        <a class="btn p-2 w-75" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                            <h2 class="p-none m-none">{{ __('Event new location') }}</h2>
                                            <p class="p-none m-none">{{ __('The location for the event :event has changed!', ["event" => $notification->data['eventName']]) }}</p>
                                            <p class="p-none m-none">{{ __('New location: ') . $notification->data['newLocation'] }}</p>
                                        </a>
                                        @break
                                    @case(\App\Notifications\TimetableEntry\TimetableEntryTimeChanged::class)
                                        <a class="btn p-2 w-75" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                            <h2 class="p-none m-none">{{ __('Event time changed') }}</h2>
                                            <p class="p-none m-none">{{ __('The time of the event :event has changed!', ["event" => $notification->data['eventName']]) }}</p>
                                            <p class="p-none m-none">{{ __('New time: :startTime - :endTime', ["startTime" => Carbon::parse($notification->data['newStartTime'])->format("H:i"), "endTime" => Carbon::parse($notification->data['newEndTime'])->format("H:i")]) }}</p>
                                        </a>
                                        @break
                                    @case(\App\Notifications\Ddas\ArtshowWinnerNotification::class)
                                        <p>
                                            {{ __("You have won the following items in the art show:") }}
                                        </p>
                                        <p>
                                            @foreach(\App\Models\Ddas\ArtshowItem::find($notification->data['artshowItems'] ?? 0) AS $item)
                                                {{ $item->name }}, {{ $item->highestBid->value }} EUR<br>
                                            @endforeach
                                        </p>
                                        <p>
                                            {{ __("Please visit Dealers' Den for pickup (Refer to the con book for opening hours!)") }}
                                        </p>
                                        @break
                                    @default
                                @endswitch
                        </div>
                    <div class="col-12 text-end text-muted">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
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
