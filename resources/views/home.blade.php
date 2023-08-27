@extends('layouts.app')
@section('title', "Home")
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
{{--            <div class="card text-center">--}}
{{--                <div class="card-header">{{ __('Dashboard') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    @if (session('status'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('status') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    {{ __('You are logged in!') }}--}}
{{--                </div>--}}
{{--            </div>--}}
            @if (!auth()->user()->telegram_user_id)
                <div class="row m-3">
                    <div class="col-12 col-md-12 text-center">
                        <h2>{{ __("Notifications") }}</h2>
                        <p>{{ __("Connect your account with telegram to enable notifications") }}</p>
                    </div>
                    <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="cyber_kacec_bot" data-size="large" data-auth-url="https://sigma.sachsenfurs.de/telegram/auth" data-request-access="write"></script>
                </div>
            @endif
            <div class="row m-3">
                <div class="col-12 col-md-12 text-center">
                    <h2>{{ __("Events you've signed up for:") }}</h2>
                </div>
                <div class="col-12 col-md-12 text-center">
                    @if (auth()->user()->attendeeEvents()->count() == 0)
                        <p>{{ __("You haven't signed up for any event yet!") }}</p>
                        <a class="btn btn-primary btn-lg" href="{{ route("public.tableview") }}" role="button">
                            {{ __("Explore Events") }}
                        </a>
                    @else
                        <!-- Table head start -->
                        <div class="d-none d-xl-block">
                            <div class="row border-bottom border-dark mb-2">
                                <div class="col-3 col-md-3">
                                    <strong>{{ __("Event") }}</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>{{ __("Date") }}</strong>
                                </div>
                                <div class="col-3 col-md-3">
                                    <strong>{{ __("Time span") }}</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>{{ __("Location") }}</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>{{ __("Actions") }}</strong>
                                </div>
                            </div>
                        </div>
                        <!-- Table head end -->

                        <!-- Table body start -->
                        @foreach (auth()->user()->attendeeEvents as $registration)
                            <div class="row mb-2">
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Event") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $registration->sigTimeslot->timetableEntry->sigEvent->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Date") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('l', strtotime($registration->sigTimeslot->timetableEntry->start)) }} ({{ date('d.m', strtotime($registration->sigTimeslot->timetableEntry->start)) }})
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Time span") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('H:i', strtotime($registration->sigTimeslot->slot_start)) }} - {{ date('H:i', strtotime($registration->sigTimeslot->slot_end)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Location") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $registration->sigTimeslot->timetableEntry->sigLocation->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                            <strong>{{ __("Actions") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="d-none d-lg-block d-xl-block">
                                                <a type="button" class="btn btn-info text-black" onclick="$('#attendeeListModal{{$registration->sigTimeslot->id}}').modal('toggle');" data-toggle="modal" data-target="#attendeeListModal{{$registration->sigTimeslot->id}}">
                                                    <span class="bi bi-people-fill"></span>
                                                </a>
                                                <button type="button" class="btn btn-danger text-white"
                                                onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')" 
                                                data-toggle="modal" data-target="#deleteModal"
                                                @if ($registration->sigTimeslot->slot_start < \Carbon\Carbon::now())
                                                    @disabled(true)
                                                @endif
                                                >
                                                
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                            <div class="d-block d-sm-none">
                                                <a type="button" class="btn btn-info text-black btn-lg" onclick="$('#attendeeListModal{{$registration->sigTimeslot->id}}').modal('toggle');" data-toggle="modal" data-target="#attendeeListModal{{$registration->sigTimeslot->id}}">
                                                    <span class="bi bi-people-fill"></span>
                                                </a>
                                                <button type="button" class="btn btn-danger text-white btn-lg"
                                                onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')" 
                                                data-toggle="modal" data-target="#deleteModal"
                                                @if ($registration->sigTimeslot->slot_start < \Carbon\Carbon::now())
                                                    @disabled(true)
                                                @endif
                                                >
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 d-block d-sm-none mx-auto">
                                    <hr>
                                </div>
                            </div>
                            <x-modals.attendee-list :timeslot="$registration->sigTimeslot" />
                        @endforeach
                    @endif
                </div>
                <hr class="mt-5 mb-5">
                <div class="col-12 col-md-12 text-center mt-2">
                    <h2>{{ __("Favorite Events") }}</h2>
                </div>
                <div class="col-12 col-md-12 text-center">
                    @if (auth()->user()->favorites->count() == 0)
                        <p>{{ __("You currently don't have any favorite events") }}</p>
                    @else
                        <!-- Table head start -->
                        <div class="d-none d-xl-block">
                            <div class="row border-bottom border-dark mb-2">
                                <div class="col-3 col-md-3">
                                    <strong>{{ __("Event") }}</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>{{ __("Date") }}</strong>
                                </div>
                                <div class="col-3 col-md-3">
                                    <strong>{{ __("Time span") }}</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>{{ __("Location") }}</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>{{ __("Actions") }}</strong>
                                </div>
                            </div>
                        </div>
                        <!-- Table head end -->

                        <!-- Table body start -->
                        @foreach (auth()->user()->favorites as $fav)
                            <div class="row mb-2">
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Event") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $fav->timetableEntry->sigEvent->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Date") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('l', strtotime($fav->timetableEntry->start)) }} ({{ date('d.m', strtotime($fav->timetableEntry->start)) }})
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Time span") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('H:i', strtotime($fav->timetableEntry->start)) }} - {{ date('H:i', strtotime($fav->timetableEntry->end)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>{{ __("Location") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $fav->timetableEntry->sigLocation->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                            <strong>{{ __("Actions") }}</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="d-none d-lg-block d-xl-block">
                                                <button type="button" class="btn 
                                                    @if (auth()->user()->reminders->contains('timetable_entry_id', $fav->timetableEntry->id))
                                                        btn-success
                                                    @else
                                                        btn-primary
                                                    @endif
                                                    text-white"
                                                    onclick="$('#reminderModal{{ $fav->timetableEntry->id }}').modal('toggle')"
                                                    data-toggle="modal"
                                                    data-target="#reminderModal{{ $fav->timetableEntry->id }}"
                                                    @if ($fav->timetableEntry->start < \Carbon\Carbon::now())
                                                        @disabled(true)
                                                    @endif
                                                    >
                                                    
                                                    @if (auth()->user()->reminders->contains('timetable_entry_id', $fav->timetableEntry->id))
                                                        <span class="bi bi-bell">
                                                            {{--
                                                            {{ dd(auth()->user()->reminders->get('timetable_entry_id', $fav->timetableEntry->id)) }}
                                                            {{ auth()->user()->reminders->get('timetable_entry_id', $fav->timetableEntry->id)->send_at }} {{ __("Minutes")}}
                                                            --}}
                                                        </span>
                                                    @else
                                                        <span class="bi bi-clock"></span>
                                                    @endif
                                                </button>
                                                <button type="button" class="btn btn-danger text-white" 
                                                onclick="document.getElementById('deleteFavModalEventName').innerHTML = '{{ $fav->timetableEntry->sigEvent->name }}'; var input = document.getElementById('deleteFavForm_timetable_entry_id'); input.value = '{{ $fav->timetableEntry->id }}'; $('#deleteFavModal').modal('show');"
                                                data-toggle="modal" data-target="#deleteFavModal"
                                                @if ($fav->timetableEntry->start < \Carbon\Carbon::now())
                                                    @disabled(true)
                                                @endif
                                                >
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                            <div class="d-block d-sm-none">
                                                <button type="button" class="btn btn-lg
                                                    @if (auth()->user()->reminders->contains('timetable_entry_id', $fav->timetableEntry->id))
                                                        btn-success
                                                    @else
                                                        btn-primary
                                                    @endif
                                                    text-white"
                                                    onclick="$('#reminderModal{{ $fav->timetableEntry->id }}').modal('toggle')"
                                                    data-toggle="modal"
                                                    data-target="#reminderModal{{ $fav->timetableEntry->id }}"
                                                    @if ($fav->timetableEntry->start < \Carbon\Carbon::now())
                                                        @disabled(true)
                                                    @endif
                                                    >
                                                    
                                                    @if (auth()->user()->reminders->contains('timetable_entry_id', $fav->timetableEntry->id))
                                                        <span class="bi bi-bell">
                                                            {{--
                                                            {{ dd(auth()->user()->reminders->get('timetable_entry_id', $fav->timetableEntry->id)) }}
                                                            {{ auth()->user()->reminders->get('timetable_entry_id', $fav->timetableEntry->id)->send_at }} {{ __("Minutes")}}
                                                            --}}
                                                        </span>
                                                    @else
                                                        <span class="bi bi-clock"></span>
                                                    @endif
                                                </button>
                                                <button type="button" class="btn btn-danger text-white btn-lg" 
                                                onclick="document.getElementById('deleteFavModalEventName').innerHTML = '{{ $fav->timetableEntry->sigEvent->name }}'; var input = document.getElementById('deleteFavForm_timetable_entry_id'); input.value = '{{ $fav->timetableEntry->id }}'; $('#deleteFavModal').modal('show');"
                                                data-toggle="modal" data-target="#deleteFavModal"
                                                @if ($fav->timetableEntry->start < \Carbon\Carbon::now())
                                                    @disabled(true)
                                                @endif
                                                >
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 d-block d-sm-none mx-auto">
                                    <hr>
                                </div>
                            </div>
                            <x-modals.reminder-selector :timetableEntry="$fav->timetableEntry" />
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel">{{ __("Attendee List") }}</h5>
        </div>
        <div class="modal-body">
            <ul>
                <li>Teilnehmer 1</li>
                <li>Teilnehmer 2</li>
            </ul>
        </div>
        <div class="modal-footer">
            <form id="userForm" action="" method="POST">
                @method('CREATE')
                @csrf
                <a class="btn btn-secondary" onclick="$('#userModal').modal('hide');" data-dismiss="modal">{{ __("Close") }}</a>
            </form>
        </div>
      </div>
    </div>
</div>
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
                    <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">{{ __("Abort") }}</a>
                    <button type="submit" class="btn btn-danger">{{ __("Yes") }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteFavModal" tabindex="-1" role="dialog" aria-labelledby="deleteFavModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content text-center">
        <div class="modal-header text-center">
          <h5 class="modal-title w-100" id="deleteFavModalLabel">Remove favorite?</h5>
        </div>
        <div class="modal-body">
            {{ __("Do you really want to remove the following event from your favorites?") }}<br>
            <strong><p class="m-0" id="deleteFavModalEventName"></p></strong>
        </div>
        <div class="modal-footer">
            <form id="deleteFavForm" action="{{ route('favorites.delete') }}" method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" id="deleteFavForm_timetable_entry_id" name="timetable_entry_id" />
                <a class="btn btn-secondary" onclick="$('#deleteFavModal').modal('hide');" data-dismiss="modal">{{ __("Abort") }}</a>
                <button type="submit" class="btn btn-danger">{{ __("Yes")  }}</button>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
