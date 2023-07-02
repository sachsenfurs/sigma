@extends('layouts.app')
@section('title', "Home")
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
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
                    <h2>Registrierungen</h2>
                    <hr>
                </div>
                <div class="col-12 col-md-12 text-center">
                    @if (auth()->user()->attendeeEvents()->count() == 0)
                        <p>Du hast aktuell noch keine Registrierungen</p>
                        <div class="d-none d-xl-block">
                            <a class="btn btn-primary" href="/table" role="button">Events entdecken</a>
                        </div>
                        <div class="d-block d-sm-none">
                            <a class="btn btn-primary btn-lg" href="/table" role="button">Events entdecken</a>
                        </div>
                    @else
                        <!-- Table head start -->
                        <div class="d-none d-xl-block">
                            <div class="row border-bottom border-dark mb-2">
                                <div class="col-3 col-md-3">
                                    <strong>Event</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Datum</strong>
                                </div>
                                <div class="col-3 col-md-3">
                                    <strong>Zeitraum</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Raum</strong>
                                </div>
                                <div class="col-2 col-md-2">
                                    <strong>Aktionen</strong>
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
                                            <strong>Datum</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('d.m.Y', strtotime($registration->sigTimeslot->timetableEntry->start)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Zeitraum</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ date('H:i', strtotime($registration->sigTimeslot->slot_start)) }} - {{ date('H:i', strtotime($registration->sigTimeslot->slot_end)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                            <strong>Raum</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            {{ $registration->sigTimeslot->timetableEntry->sigLocation->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                                    <div class="row">
                                        <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                            <strong>Aktionen</strong>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="d-none d-lg-block d-xl-block">
                                                <a type="button" class="btn btn-info text-black" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                                    <span class="bi bi-people-fill"></span>
                                                </a>
                                                <button type="button" class="btn btn-danger text-white" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                                    <span class="bi bi-x"></span>
                                                </button>
                                            </div>
                                            <div class="d-block d-sm-none">
                                                <a type="button" class="btn btn-info text-black btn-lg" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                                    <span class="bi bi-people-fill"></span>
                                                </a>
                                                <button type="button" class="btn btn-danger text-white btn-lg" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/cancel/{{ $registration->sigTimeslot->id }}')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
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
                        <div class="col-12 col-md-12">
                            <div class="d-none d-xl-block">
                                <a class="btn btn-primary" href="/table" role="button">Weitere Events entdecken</a>
                            </div>
                            <div class="d-block d-sm-none">
                                <a class="btn btn-primary btn-lg" href="/table" role="button">Weitere Events entdecken</a>
                            </div>
                        </div>
                    @endif 
                </div>
            </div>























            <!--
            <div class="row m-3">
                <div class="col-12 col-md-12 text-center">
                    <h2>Registrierungen</h2>
                    <hr>
                </div>
                <div class="col-12 col-md-12 text-center">
                    <div class="d-none d-xl-block">
                        <div class="row border-bottom border-dark mb-2">
                            <div class="col-3 col-md-3">
                                <strong>Event</strong>
                            </div>
                            <div class="col-2 col-md-2">
                                <strong>Datum</strong>
                            </div>
                            <div class="col-3 col-md-3">
                                <strong>Zeitraum</strong>
                            </div>
                            <div class="col-2 col-md-2">
                                <strong>Raum</strong>
                            </div>
                            <div class="col-2 col-md-2">
                                <strong>Aktionen</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Event</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    Fotoshooting
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Datum</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    01.09.2023
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Zeitraum</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    14:00 - 14:15
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Raum</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    Suhl
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                    <strong>Aktionen</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    <div class="d-none d-lg-block d-xl-block">
                                        <a type="button" class="btn btn-info text-black" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                            <span class="bi bi-people-fill"></span>
                                        </a>
                                        <button type="button" class="btn btn-danger text-white" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                            <span class="bi bi-x"></span>
                                        </button>
                                    </div>
                                    <div class="d-block d-sm-none">
                                        <a type="button" class="btn btn-info text-black btn-lg" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                            <span class="bi bi-people-fill"></span>
                                        </a>
                                        <button type="button" class="btn btn-danger text-white btn-lg" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
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
                    <div class="row mb-2">
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Event</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    Volleyball
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Datum</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    02.09.2023
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Zeitraum</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    17:00 - 19:00
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-middle">
                                    <strong>Raum</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    Outdoor
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-1 mb-1 p-0">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                    <strong>Aktionen</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    <div class="d-none d-lg-block d-xl-block">
                                        <a type="button" class="btn btn-info text-black" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                            <span class="bi bi-people-fill"></span>
                                        </a>
                                        <button type="button" class="btn btn-danger text-white" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                            <span class="bi bi-x"></span>
                                        </button>
                                    </div>
                                    <div class="d-block d-sm-none">
                                        <a type="button" class="btn btn-info text-black btn-lg" onclick="$('#userModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
                                            <span class="bi bi-people-fill"></span>
                                        </a>
                                        <button type="button" class="btn btn-danger text-white btn-lg" onclick="$('#deleteModal').modal('show'); $('#deleteForm').attr('action', '/timeslots/--')" data-toggle="modal" data-target="#deleteModal" data-timeslot="--">
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
                    <div class="col-12 col-md-12">
                        <div class="d-none d-xl-block">
                            <a class="btn btn-primary" href="/table" role="button">Weitere Events entdecken</a>
                        </div>
                        <div class="d-block d-sm-none">
                            <a class="btn btn-primary btn-lg" href="/table" role="button">Weitere Events entdecken</a>
                        </div>
                    </div>
                </div>
            </div>-->
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
          <h5 class="modal-title w-100" id="deleteModalLabel">Registrierung stornieren?</h5>
        </div>
        <div class="modal-body">
            Möchtest du die Registrierung für das Event<br>
            <strong>EVENTNAME</strong><br>
            wirklich stornieren?
        </div>
        <div class="modal-footer">
            <form id="deleteForm" action="" method="POST">
                @method('DELETE')
                @csrf
                <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">Abbrechen</a>
                <button type="submit" class="btn btn-danger">Stornieren</button>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
