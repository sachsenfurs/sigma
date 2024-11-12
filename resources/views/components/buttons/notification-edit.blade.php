@props([
    'fav' => null,
    'small' => false,
    'route'
])

@php($reminderExists = $fav->timetableEntry->reminders->where("user_id", auth()->id()))

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
        <span class="bi bi-bell icon-link"></span>
        <span @class(['small' => $small])>
            {{ $fav->timetableEntry->reminders->where("user_id", auth()->id())->first()?->minutes_before }}min
        </span>
    @else
        <span class="bi bi-clock icon-link"></span>
    @endif
</button>
