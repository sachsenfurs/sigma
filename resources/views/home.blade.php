@extends('layouts.app')
@section('title', "Home")
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
            <div class="row m-3">
                <div class="col-12 col-md-12 text-center">
                    <h2>Registrations</h2>
                </div>
                <div class="col-12 col-md-12 text-center">
                    @if (auth()->user()->attendeeEvents()->count() == 0)
                        <p>You currently don't have any registrations</p>
                        <div class="d-none d-xl-block">
                            <a class="btn btn-primary" href="/table" role="button">Explore Events</a>
                        </div>
                        <div class="d-block d-sm-none">
                            <a class="btn btn-primary btn-lg" href="/table" role="button">Explore Events</a>
                        </div>
                    @else
                        <!-- Table head start -->
                        <div class="d-none d-xl-block">
                            <div class="row border-bottom border-dark mb-2">
                                <div class="col-3 col-md-3">
                                    <strong>Event</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Date</strong>
                                </div>
                                <div class="col-3 col-md-3">
                                    <strong>Time Period</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Room</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Actions</strong>
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
                                            <strong>Event</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $registration->sigTimeslot->timetableEntry->sigEvent->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Date</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('l', strtotime($registration->sigTimeslot->timetableEntry->start)) }} ({{ date('d.m', strtotime($registration->sigTimeslot->timetableEntry->start)) }})
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Time Period</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('H:i', strtotime($registration->sigTimeslot->slot_start)) }} - {{ date('H:i', strtotime($registration->sigTimeslot->slot_end)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Room</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $registration->sigTimeslot->timetableEntry->sigLocation->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                            <strong>Actions</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="d-none d-lg-block d-xl-block">
                                                <!-- Until it can be specified if attendees should be visible or not
                                                <a type="button" class="btn btn-info text-black" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal">
                                                    <span class="bi bi-people-fill"></span>
                                                </a>
                                                -->
                                                <button type="button" class="btn btn-danger text-white" onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')" data-toggle="modal" data-target="#deleteModal">
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                            <div class="d-block d-sm-none">
                                                <!-- Until it can be specified if attendees should be visible or not
                                                <a type="button" class="btn btn-info text-black btn-lg" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal">
                                                    <span class="bi bi-people-fill"></span>
                                                </a>
                                                -->
                                                <button type="button" class="btn btn-danger text-white btn-lg" onclick="document.getElementById('deleteModalEventName').innerHTML = '{{ $registration->sigTimeslot->timetableEntry->sigEvent->name }}'; $('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')" data-toggle="modal" data-target="#deleteModal">
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
                        @endforeach
                        <div class="col-12 col-md-12 mt-1">
                            <div class="d-none d-xl-block">
                                <a class="btn btn-primary" href="/table" role="button">Explore more events</a>
                            </div>
                            <div class="d-block d-sm-none">
                                <a class="btn btn-primary btn-lg" href="/table" role="button">Explore more events</a>
                            </div>
                        </div>
                    @endif 
                </div>
                <hr class="mt-5 mb-5">
                <div class="col-12 col-md-12 text-center mt-2">
                    <h2>Favorite Events</h2>
                </div>
                <div class="col-12 col-md-12 text-center">
                    @if (auth()->user()->favorites->count() == 0)
                        <p>You currently don't have any favorite events</p>
                    @else
                        <!-- Table head start -->
                        <div class="d-none d-xl-block">
                            <div class="row border-bottom border-dark mb-2">
                                <div class="col-3 col-md-3">
                                    <strong>Event</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Date</strong>
                                </div>
                                <div class="col-3 col-md-3">
                                    <strong>Time Period</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Room</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Actions</strong>
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
                                            <strong>Event</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $fav->timetableEntry->sigEvent->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Date</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('l', strtotime($fav->timetableEntry->start)) }} ({{ date('d.m', strtotime($fav->timetableEntry->start)) }})
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Time Period</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('H:i', strtotime($fav->timetableEntry->start)) }} - {{ date('H:i', strtotime($fav->timetableEntry->end)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Room</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $fav->timetableEntry->sigLocation->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                            <strong>Actions</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="d-none d-lg-block d-xl-block">
                                                <button type="button" class="btn btn-danger text-white" onclick="document.getElementById('deleteFavModalEventName').innerHTML = '{{ $fav->timetableEntry->sigEvent->name }}'; var input = document.getElementById('deleteFavForm_timetable_entry_id'); input.value = '{{ $fav->id }}'; $('#deleteFavModal').modal('show');" data-toggle="modal" data-target="#deleteFavModal">
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                            <div class="d-block d-sm-none">
                                                <button type="button" class="btn btn-danger text-white btn-lg" onclick="document.getElementById('deleteFavModalEventName').innerHTML = '{{ $fav->timetableEntry->sigEvent->name }}'; var input = document.getElementById('deleteFavForm_timetable_entry_id'); input.value = '{{ $fav->id }}'; $('#deleteFavModal').modal('show');" data-toggle="modal" data-target="#deleteFavModal" data-eventname="{{ $fav->timetableEntry->sigEvent->name }}">
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
          <h5 class="modal-title" id="userModalLabel">Teilnehmerliste</h5>
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
                <a class="btn btn-secondary" onclick="$('#userModal').modal('hide');" data-dismiss="modal">Schließen</a>
            </form>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-header text-center">
            <h5 class="modal-title w-100" id="deleteModalLabel">Cancel registration?</h5>
            </div>
            <div class="modal-body">
                Do you really want to cancel your registration for<br>
                <strong><p class="m-0" id="deleteModalEventName"></p></strong>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">Abort</a>
                    <button type="submit" class="btn btn-danger">Cancel</button>
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
            Do you really want to remove the event<br>
            <strong><p class="m-0" id="deleteFavModalEventName"></p></strong>
            from your favorites?
        </div>
        <div class="modal-footer">
            <form id="deleteFavForm" action="{{ route('remove-favorite') }}" method="POST">
                @csrf
                <input type="hidden" id="deleteFavForm_timetable_entry_id" name="timetable_entry_id" />
                <a class="btn btn-secondary" onclick="$('#deleteFavModal').modal('hide');" data-dismiss="modal">Abort</a>
                <button type="submit" class="btn btn-danger">Remove</button>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
