@extends('layouts.app')
@section('title', __("Home"))
@section('content')
<div class="container">
{{--    <h2>{{ __("Before the convention") }}</h2>--}}
    @if($preConMode)
        <div class="row row-cols-1 row-cols-md-3 mx-auto align-items-stretch justify-content-center" style="max-width: 970px">
            <x-home-signup-card :title="__('SIG Sign Up')" img="/images/signup/sigfox.png" :href="route('sigs.create')">
                {{ __("Submit your Events, Workshops, Presentations and more!") }}
            </x-home-signup-card>
            @if(app(\App\Settings\DealerSettings::class)->enabled)
                <x-home-signup-card :title="__('Dealer\'s Den Sign Up')" img="/images/signup/dealerfox.png" :href="route('dealers.create')">
                    {{ __("Would you like to sell your art at the con?") }}
                </x-home-signup-card>
            @endif
            @if(app(\App\Settings\ArtShowSettings::class)->enabled)
                <x-home-signup-card :title="__('Art Show Item Sign Up')" img="/images/signup/artshowfox.png" :href="route('artshow.create')">
                    {{ __("Submit your art for exhibition or auction") }}
                </x-home-signup-card>
            @endif
        </div>
    @else
        <div class="row mt-5">
            <div class="col-12 col-md-8">
                <div class="container">
                    <h2 class="p-2"><i class="bi bi-calendar-week"></i> {{ __("Your Upcoming Events") }}</h2>
                    @if (auth()->user()->attendeeEvents()->count() == 0)
                        <p>{{ __("You haven't signed up for any event yet!") }}</p>
                        <a class="btn btn-primary btn-lg" href="{{ route("schedule.listview") }}" role="button">
                            {{ __("Explore Events") }}
                        </a>
                    @else
                        @foreach ($registrations as $registration)
                            <div class="card">
                                <div class="card-body">
                                    <h3>{{ $registration->sigTimeslot->timetableEntry->sigEvent->name_localized }}</h3>
                                    <hr>
                                    <table>
                                        <tr>
                                            <td><strong>{{ __("Location") }}</strong></td>
                                            <td style="padding-left: 15px">{{ $registration->sigTimeslot->timetableEntry->sigLocation->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __("Date") }}</strong></td>
                                            <td style="padding-left: 15px">{{ date('l', strtotime($registration->sigTimeslot->timetableEntry->start)) }} ({{ date('d.m', strtotime($registration->sigTimeslot->timetableEntry->start)) }})</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __("Time span") }}</strong></td>
                                            <td style="padding-left: 15px">{{ date('H:i', strtotime($registration->sigTimeslot->slot_start)) }} - {{ date('H:i', strtotime($registration->sigTimeslot->slot_end)) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-footer d-flex justify-content-center">
                                    <x-buttons.timeslot-notification-edit :reg="$registration" />
                                    <a type="button" class="btn btn-info text-black" style="margin-left: 5px" onclick="$('#attendeeListModal{{$registration->sigTimeslot->id}}').modal('toggle');" data-toggle="modal" data-target="#attendeeListModal{{$registration->sigTimeslot->id}}">
                                        <span class="bi bi-people-fill"></span>
                                    </a>
                                    <button type="button"
                                            class="btn btn-danger text-white"
                                            style="margin-left: 5px"
                                            onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name_localized }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')"
                                            data-toggle="modal" data-target="#deleteModal"
                                            @if ($registration->sigTimeslot->reg_end < \Carbon\Carbon::now())
                                                disabled
                                            @endif
                                    >
                                        <span class="bi bi-x"></span>
                                    </button>
                                </div>
                            </div>
                            <x-modal.attendee-list :timeslot="$registration->sigTimeslot" />
                            <x-modal.timeslotReminder-selector :sigTimeslot="$registration->sigTimeslot" />
                        @endforeach
                    @endif
                    {{--
                    <div class="row row-cols-1 row-cols-md-3 mt-4 mx-auto align-items-stretch justify-content-center" style="max-width: 970px">
                        <x-home-signup-card :title="__('SIG Sign Up')" img="/images/signup/sigfox.png" :href="route('sigs.create')">
                            {{ __("Submit your Events, Workshops, Presentations and more!") }}
                        </x-home-signup-card>
                        @if(app(\App\Settings\ArtShowSettings::class)->enabled)
                            <x-home-signup-card :title="__('Explore Artshow')" img="/images/signup/artshowfox.png" :href="route('artshow.index')">
                                {{ __("Explore which items are in the this years artshow!") }}
                            </x-home-signup-card>
                        @endif
                            
                        <x-home-signup-card :title="__('Lost & Found')" img="/images/signup/sigfox.png" :href="route('sigs.create')">
                            {{ __("Did you lose something on the convention? Click here to see which items were found.") }}
                        </x-home-signup-card>
                        
                    </div>--}}
                </div>
            </div>
            <div class="col-12 col-md-4 mt-2 mt-md-0">
                @if (!auth()->user()->telegram_user_id)
                    <div class="container">
                        <h2 class="p-2"><i class="bi bi-telegram"></i> {{ __("Telegram Connection") }}</h2>
                        <p>{{ __("Haven't connected your Telegram Account yet?") }}</p>
                        <a class="btn btn-primary btn-lg mx-auto" href="{{ route("user-settings.edit") }}" role="button">
                            {{ __("Connect it now") }}
                        </a>
                    </div>
                @endif
                <div class="container mt-2">
                    <h2 class="p-2"><h3><i class="bi bi-bell-fill"></i> {{ __("Notifications") }}</h2>
                    <p>{{ __("Do you want to modify how you recive notifications?") }}</p>
                    <a class="btn btn-primary btn-lg" href="{{ route("user-settings.edit") }}" role="button">
                        {{ __("Modify them here") }}
                    </a>
                </div>
            </div>
        </div>
        <div class="container mt-2">
            <h2 class="p-2"><i class="bi bi-heart-fill"></i> {{ __("Favorite Events") }}</h2>
            <div class="row">
                @forelse ($favorites as $fav)
                    <div class="col-12 col-md-4">
                        <div class="card my-1">
                            <div class="card-body p-2 m-0">
                                <p class="p-0 m-0" style="font-weight: bold; font-size: 1.35rem; margin-left: 5px;">{{ $fav->timetableEntry->sigEvent->name_localized }}</p>
                                <hr style="margin: 2px 0 2px 0;">
                                <div class="row m-0 py-1">
                                    <div class="col-4 p-0 d-flex align-items-center">
                                        <i class="bi bi-calendar4" style="padding-right: 5px;"></i> {{ __(date('D', strtotime($fav->timetableEntry->start))) }} ({{ date('d.m', strtotime($fav->timetableEntry->start)) }})
                                    </div>
                                    <div class="col-4 p-0 d-flex align-items-center">
                                        <i class="bi bi-clock" style="padding-right: 5px;"></i> {{ $fav->timetableEntry->start->format("H:i") }} - {{ $fav->timetableEntry->end->format("H:i") }}
                                    </div>
                                    <div class="col-4 p-0">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <x-buttons.notification-edit :fav="$fav" :small="true"/>
                                            <button type="button" class="btn btn-danger text-white btn"
                                                style="margin-left: 5px;"
                                                data-bs-signame="{{ $fav->timetableEntry->sigEvent->name_localized }}"
                                                data-bs-entryid="{{ $fav->timetableEntry->id }}"
                                                data-bs-toggle="modal" data-bs-target="#deleteFavModal"
                                                @disabled($fav->timetableEntry->start < \Carbon\Carbon::now())
                                                >
                                                <span class="bi bi-x"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-modal.reminder-selector :timetableEntry="$fav->timetableEntry" />
                    </div>
                @empty
                    <p>{{ __("You currently don't have any favorite events") }}</p>
                @endforelse
            </div>
        </div>
    @endif
</div>

<!-- START Registered Events Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-header text-center">
            <h5 class="modal-title w-100" id="deleteModalLabel">{{ __("Cancel Registration?") }}</h5>
            </div>
            <div class="modal-body">
                {{ __("Do you really want to cancel the following registration?") }}<br>
                <strong><p class="m-0" id="deleteModalEventName"></p></strong>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Abort") }}</a>
                    <button type="submit" class="btn btn-danger">{{ __("Yes") }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Registered Events Modal -->

<!-- START Favorites Modal -->
<div class="modal fade" id="deleteFavModal" tabindex="-1" role="dialog" aria-labelledby="deleteFavModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content text-center">
        <div class="modal-header text-center">
        <h5 class="modal-title w-100" id="deleteFavModalLabel">Remove favorite?</h5>
        </div>
        <div class="modal-body">
            {{ __("Do you really want to remove the following event from your favorites?") }}<br>
            <strong><p class="m-0"></p></strong>
        </div>
        <div class="modal-footer">
            <form id="deleteFavForm" action="" method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" id="deleteFavForm_timetable_entry_id" name="timetable_entry_id" />
                <a class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Abort") }}</a>
                <button type="submit" class="btn btn-danger">{{ __("Yes")  }}</button>
            </form>
        </div>
    </div>
    </div>
</div>
<!-- END Favorites Modal -->
@endsection
