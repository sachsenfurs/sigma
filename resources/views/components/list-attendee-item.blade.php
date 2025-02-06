@props([
    'name' => "",
    'avatar' => null,
    'attendee',
    'canManageSigAttendees',
    'groupRegistrationEnabled',
])
<div class="row mb-3">
    @if($avatar)
        <div class="col-4 col-md-2" style="max-height: 100%;">
            <img class="mx-auto img-fluid rounded-circle h-100 w-100 rounded" src="{{ $avatar }}" style="object-fit: cover; max-height: 30vw" alt="">
        </div>
    @else
        <div class="col-4 col-md-2" style="max-height: 100%;">
            <img class="mx-auto" style="max-height: 30vw;" src="{{ asset('icons/paw.svg') }}" alt="Attendee Paw">
        </div>
    @endif
    <div class="col-6 col-md-6 text-left" style="display: flex; justify-content: center; align-items: center;">
        <div>
            {{ $name }}
        </div>
    </div>
    <div class="col-2 col-md-4" style="display: flex; justify-content: center; align-items: center;">
        <div>
            @if ($groupRegistrationEnabled)
                @if($attendee->timeslot_owner == true)
                    <a type="button" class="btn btn-warning text-black">
                        <span class="bi bi-award-fill"></span>
                    </a>
                @elseif ($canManageSigAttendees)
                    <a type="button" class="btn btn-danger text-white" onclick="$('#removeAttendeeModal{{$attendee->id}}').modal('toggle'); $('#removeAttendeeForm{{ $attendee->id }}').attr('action', '/cancel/{{ $attendee->sigTimeslot->id }}')" data-toggle="modal" data-target="#removeAttendeeModal{{$attendee->id}}">
                        <span class="bi bi-x"></span>
                    </a>
                @endif
            @endif
        </div>
    </div>
    <!---
    @if($avatar)
        <div class="col-4 col-md-2" style="max-height: 100%">
            <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded" style="object-fit: cover; max-height: 30vw" alt="">
        </div>
    @endif
    <div class="@if($avatar)col-8 col-md-10 @else col-md-12 text-left @endif">
        @if ($avatar)
            {{ $name }}
        @else
            <li>{{ $name }}</li>
        @endif
    </div>
    --->
</div>
