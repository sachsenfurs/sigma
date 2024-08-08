@props([
    'reg' => null,
    'route'
])

@php($timeslotReminderExists = auth()->user()->timeslotReminders->contains('timeslot_id', $reg?->sigTimeslot->id))

<button type="button"
    @class([
        'btn',
        'btn-success' => $timeslotReminderExists,
        'btn-primary' => !$timeslotReminderExists,
    ])
    onclick="$('#TimeslotReminderForm{{ $reg?->sigTimeslot->id }}').attr('action', '{{ $timeslotReminderExists ? route("timeslotReminders.update") : route("timeslotReminders.store") }}');"
    data-bs-toggle="modal"
    data-bs-target="#TimeslotReminderModal{{ $reg?->sigTimeslot->id }}"
    @disabled($reg?->sigTimeslot->slot_start->isBefore(now()))
>
    @if($timeslotReminderExists)
        <span class="bi bi-bell"></span> {{ $reg?->timeslotReminders->first()->minutes_before }}min
    @else
        <span class="bi bi-clock"></span>
    @endif
</button>
