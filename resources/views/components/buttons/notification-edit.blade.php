@props([
    'fav' => null,
    'route'
])

@php($reminderExists = auth()->user()->reminders->contains('timetable_entry_id', $fav?->timetableEntry->id))

<button type="button"
    @class([
        'btn',
        'btn-success' => $reminderExists,
        'btn-primary' => !$reminderExists,
    ])
    onclick="$('#reminderForm{{ $fav?->timetableEntry->id }}').attr('action', '{{ $reminderExists ? route("reminders.update") : route("reminders.store") }}');"
    data-bs-toggle="modal"
    data-bs-target="#reminderModal{{ $fav?->timetableEntry->id }}"
    @disabled($fav?->timetableEntry->start < \Carbon\Carbon::now())
>
    @if($reminderExists)
        <span class="bi bi-bell"></span> {{ $fav?->reminders->first()->minutes_before }}min
    @else
        <span class="bi bi-clock"></span>
    @endif
</button>
