@extends('layouts.app')
@section('title', "Home")
@section('content')
<div class="container">
{{--    <h2>{{ __("Before the convention") }}</h2>--}}
    <div class="row row-cols-1 row-cols-md-3 mx-auto align-items-stretch justify-content-center" style="max-width: 970px">
        <x-home-signup-card :title="__('SIG Sign Up')" img="/images/signup/sigfox.png" :href="route('sigs.create')">
            {{ __("Submit your Events, Workshops, Presentations and more!") }}
        </x-home-signup-card>
        @if(app(\App\Settings\DealerSettings::class)->enabled)
            <x-home-signup-card :title="__('Dealers Den Sign Up')" img="/images/signup/dealerfox.png" :href="route('dealers.create')">
                {{ __("Would you like to sell your art at the con?") }}
            </x-home-signup-card>
        @endif
        @if(app(\App\Settings\ArtShowSettings::class)->enabled)
            <x-home-signup-card :title="__('Art Show Item Sign Up')" img="/images/signup/artshowfox.png" :href="route('artshow.create')">
                {{ __("Submit your art for exhibition or auction") }}
            </x-home-signup-card>
        @endif
    </div>
</div>


{{--    <h2 class="mt-3">{{ __("At the convention") }}</h2>--}}
{{--    <div class="row justify-content-center">--}}

{{--        <div class="col-md-8">--}}
{{--            @if (!auth()->user()->telegram_user_id)--}}
{{--                <div class="row m-3">--}}
{{--                    <div class="col-12 col-md-12 text-center">--}}
{{--                        <h2>{{ __("Notifications") }}</h2>--}}
{{--                        <p>{{ __("Connect your account with telegram to enable notifications") }}</p>--}}
{{--                    </div>--}}
{{--                    <div class="col-10 col-md-4 mx-auto">--}}
{{--                        <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="{{ app(\App\Settings\AppSettings::class)->telegram_bot_name }}" data-size="large" data-auth-url="{{ route("telegram.connect") }}" data-request-access="write"></script>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            <div class="row m-3">--}}
{{--                <div class="col-12 col-md-12 text-center">--}}
{{--                    <h2>{{ __("Events you've signed up for:") }}</h2>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-md-12 text-center">--}}
{{--                    @if (auth()->user()->attendeeEvents()->count() == 0)--}}
{{--                        <p>{{ __("You haven't signed up for any event yet!") }}</p>--}}
{{--                        <a class="btn btn-primary btn-lg" href="{{ route("schedule.listview") }}" role="button">--}}
{{--                            {{ __("Explore Events") }}--}}
{{--                        </a>--}}
{{--                    @else--}}
{{--                        <!-- Table head start -->--}}
{{--                        <div class="d-none d-xl-block">--}}
{{--                            <div class="row border-bottom border-dark mb-2">--}}
{{--                                <div class="col-3 col-md-3">--}}
{{--                                    <strong>{{ __("Event") }}</strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-2 col-md-2">--}}
{{--                                    <strong>{{ __("Date") }}</strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-2 col-md-2">--}}
{{--                                    <strong>{{ __("Time span") }}</strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-2 col-md-2">--}}
{{--                                    <strong>{{ __("Location") }}</strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 col-md-3">--}}
{{--                                    <strong>{{ __("Actions") }}</strong>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- Table head end -->--}}

{{--                        <!-- Table body start -->--}}
{{--                        @foreach ($registrations as $registration)--}}
{{--                            <div class="row mb-2">--}}
{{--                                <div class="col-12 col-md-3 mt-1 mb-1">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">--}}
{{--                                            <strong>{{ __("Event") }}</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6 col-md-12">--}}
{{--                                            {{ $registration->sigTimeslot->timetableEntry->sigEvent->name_localized }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-2 mt-1 mb-1">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">--}}
{{--                                            <strong>{{ __("Date") }}</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6 col-md-12">--}}
{{--                                            {{ date('l', strtotime($registration->sigTimeslot->timetableEntry->start)) }} ({{ date('d.m', strtotime($registration->sigTimeslot->timetableEntry->start)) }})--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-2 mt-1 mb-1">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">--}}
{{--                                            <strong>{{ __("Time span") }}</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6 col-md-12">--}}
{{--                                            {{ date('H:i', strtotime($registration->sigTimeslot->slot_start)) }} - {{ date('H:i', strtotime($registration->sigTimeslot->slot_end)) }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-2 mt-1 mb-1">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">--}}
{{--                                            <strong>{{ __("Location") }}</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6 col-md-12">--}}
{{--                                            {{ $registration->sigTimeslot->timetableEntry->sigLocation->name }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-3 mt-1 mb-1 p-0">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6 col-md-6 d-block d-sm-none align-items-center">--}}
{{--                                            <strong>{{ __("Actions") }}</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6 col-md-12">--}}
{{--                                            <div class="d-none d-lg-block d-xl-block">--}}
{{--                                                <x-buttons.timeslot-notification-edit :reg="$registration" />--}}
{{--                                                <a type="button" class="btn btn-info text-black" onclick="$('#attendeeListModal{{$registration->sigTimeslot->id}}').modal('toggle');" data-toggle="modal" data-target="#attendeeListModal{{$registration->sigTimeslot->id}}">--}}
{{--                                                    <span class="bi bi-people-fill"></span>--}}
{{--                                                </a>--}}
{{--                                                <button type="button" class="btn btn-danger text-white"--}}
{{--                                                onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name_localized }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')"--}}
{{--                                                data-toggle="modal" data-target="#deleteModal"--}}

{{--                                                @if ($registration->sigTimeslot->reg_end < \Carbon\Carbon::now())--}}
{{--                                                    disabled--}}
{{--                                                @endif--}}

{{--                                                >--}}
{{--                                                    <span class="bi bi-x"></span>--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-block d-sm-none">--}}
{{--                                                <x-buttons.timeslot-notification-edit :reg="$registration" />--}}
{{--                                                <a type="button" class="btn btn-info text-black btn-lg" onclick="$('#attendeeListModal{{$registration->sigTimeslot->id}}').modal('toggle');" data-toggle="modal" data-target="#attendeeListModal{{$registration->sigTimeslot->id}}">--}}
{{--                                                    <span class="bi bi-people-fill"></span>--}}
{{--                                                </a>--}}
{{--                                                <button type="button" class="btn btn-danger text-white btn-lg"--}}
{{--                                                onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name_localized }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')"--}}
{{--                                                data-toggle="modal" data-target="#deleteModal"--}}

{{--                                                @if ($registration->sigTimeslot->reg_end < \Carbon\Carbon::now())--}}
{{--                                                    disabled--}}
{{--                                                @endif--}}

{{--                                                >--}}
{{--                                                    <span class="bi bi-x"></span>--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <x-modal.timeslotReminder-selector :sigTimeslot="$registration->sigTimeslot" />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-6 col-md-6 d-block d-sm-none mx-auto">--}}
{{--                                    <hr>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <x-modal.attendee-list :timeslot="$registration->sigTimeslot" />--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--                <hr class="my-4">--}}
{{--                <div class="col-12 col-md-12 text-center my-3">--}}
{{--                    <h2>{{ __("Favorite Events") }}</h2>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-md-12 text-center">--}}
{{--                    <!-- Table head start -->--}}
{{--                    <div class="d-none d-xl-block">--}}
{{--                        <div class="row border-bottom border-dark mb-2">--}}
{{--                            <div class="col-3 col-md-3">--}}
{{--                                <strong>{{ __("Event") }}</strong>--}}
{{--                            </div>--}}
{{--                            <div class="col-2 col-md-2">--}}
{{--                                <strong>{{ __("Date") }}</strong>--}}
{{--                            </div>--}}
{{--                            <div class="col-3 col-md-3">--}}
{{--                                <strong>{{ __("Time span") }}</strong>--}}
{{--                            </div>--}}
{{--                            <div class="col-2 col-md-2">--}}
{{--                                <strong>{{ __("Location") }}</strong>--}}
{{--                            </div>--}}
{{--                            <div class="col-2 col-md-2">--}}
{{--                                <strong>{{ __("Actions") }}</strong>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Table head end -->--}}

{{--                    <!-- Table body start -->--}}
{{--                    @forelse ($favorites as $fav)--}}
{{--                        <div class="row mb-2">--}}
{{--                            <div class="col-12 col-md-3 mt-1 mb-1">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6 col-md-6 d-block d-md-none align-middle">--}}
{{--                                        <strong>{{ __("Event") }}</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 col-md-12">--}}
{{--                                        {{ $fav->timetableEntry->sigEvent->name_localized }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 col-md-2 mt-1 mb-1">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6 col-md-6 d-block d-md-none align-middle">--}}
{{--                                        <strong>{{ __("Date") }}</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 col-md-12">--}}
{{--                                        {{ $fav->timetableEntry->start->isoFormat("dddd, DD.MM") }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 col-md-3 mt-1 mb-1">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6 col-md-6 d-block d-md-none align-middle">--}}
{{--                                        <strong>{{ __("Time span") }}</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 col-md-12">--}}
{{--                                        {{ $fav->timetableEntry->start->format("H:i") }} - {{ $fav->timetableEntry->end->format("H:i") }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 col-md-2 mt-1 mb-1">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6 col-md-6 d-block d-md-none align-middle">--}}
{{--                                        <strong>{{ __("Location") }}</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 col-md-12">--}}
{{--                                        {{ $fav->timetableEntry->sigLocation->name }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 col-md-2 mt-1 mb-1 p-0">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6 col-md-6 d-block d-md-none align-items-center">--}}
{{--                                        <strong>{{ __("Actions") }}</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 col-md-12">--}}
{{--                                        <div class="d-block">--}}
{{--                                            <x-buttons.notification-edit :fav="$fav" />--}}
{{--                                            <button type="button" class="btn btn-danger text-white btn"--}}
{{--                                                    data-bs-signame="{{ $fav->timetableEntry->sigEvent->name_localized }}"--}}
{{--                                                    data-bs-entryid="{{ $fav->timetableEntry->id }}"--}}
{{--                                                    data-bs-toggle="modal" data-bs-target="#deleteFavModal"--}}
{{--                                                    @disabled($fav->timetableEntry->start < \Carbon\Carbon::now())--}}
{{--                                            >--}}
{{--                                                <span class="bi bi-x"></span>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-6 col-md-6 d-block d-sm-none mx-auto">--}}
{{--                                <hr>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <x-modal.reminder-selector :timetableEntry="$fav->timetableEntry" />--}}
{{--                    @empty--}}
{{--                        <p>{{ __("You currently don't have any favorite events") }}</p>--}}
{{--                    @endforelse--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<script>--}}
{{--    $(document).ready(function() {--}}
{{--        document.querySelectorAll("#deleteModal,#deleteFavModal").forEach((modal) => {--}}
{{--            modal.addEventListener('show.bs.modal', function (event) {--}}
{{--                // Button that triggered the modal--}}
{{--                let button = event.relatedTarget;--}}
{{--                // Extract info from data-bs-* attributes--}}
{{--                let name = button.getAttribute('data-bs-signame');--}}
{{--                let id = button.getAttribute('data-bs-entryid');--}}
{{--                modal.querySelector("form").action = "/favorites/" + id;--}}
{{--                modal.querySelector('.modal-body p').textContent = name;--}}
{{--            });--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}

{{--<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--      <div class="modal-content">--}}
{{--        <div class="modal-header">--}}
{{--          <h5 class="modal-title" id="userModalLabel">{{ __("Attendee List") }}</h5>--}}
{{--        </div>--}}
{{--        <div class="modal-body">--}}
{{--            <ul>--}}
{{--                <li>Teilnehmer 1</li>--}}
{{--                <li>Teilnehmer 2</li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--        <div class="modal-footer">--}}
{{--            <form id="userForm" action="" method="POST">--}}
{{--                @method('CREATE')--}}
{{--                @csrf--}}
{{--                <a class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</a>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--        <div class="modal-content text-center">--}}
{{--            <div class="modal-header text-center">--}}
{{--            <h5 class="modal-title w-100" id="deleteModalLabel">{{ __("Cancel Registration?") }}</h5>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                {{ __("Do you really want to cancel the following registration?") }}<br>--}}
{{--                <strong><p class="m-0" id="deleteModalEventName"></p></strong>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <form id="deleteForm" action="" method="POST">--}}
{{--                    @method('DELETE')--}}
{{--                    @csrf--}}
{{--                    <a class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Abort") }}</a>--}}
{{--                    <button type="submit" class="btn btn-danger">{{ __("Yes") }}</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="modal fade" id="deleteFavModal" tabindex="-1" role="dialog" aria-labelledby="deleteFavModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--      <div class="modal-content text-center">--}}
{{--        <div class="modal-header text-center">--}}
{{--          <h5 class="modal-title w-100" id="deleteFavModalLabel">Remove favorite?</h5>--}}
{{--        </div>--}}
{{--        <div class="modal-body">--}}
{{--            {{ __("Do you really want to remove the following event from your favorites?") }}<br>--}}
{{--            <strong><p class="m-0"></p></strong>--}}
{{--        </div>--}}
{{--        <div class="modal-footer">--}}
{{--            <form id="deleteFavForm" action="" method="POST">--}}
{{--                @method('DELETE')--}}
{{--                @csrf--}}
{{--                <input type="hidden" id="deleteFavForm_timetable_entry_id" name="timetable_entry_id" />--}}
{{--                <a class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Abort") }}</a>--}}
{{--                <button type="submit" class="btn btn-danger">{{ __("Yes")  }}</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}
@endsection
