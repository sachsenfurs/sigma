@props([
    'class',
    'timetableEntry',
    'route'
])
<button type="button"
    class="btn {{ $class }}"
    onclick="
        $('#reminderForm{{ $timetableEntry->id }}').attr('action', '{{ route($route) }}');
        $('#reminderModal{{ $timetableEntry->id }}').modal('toggle'); 
    "
    data-toggle="modal"
    data-target="#reminderModal{{ $timetableEntry->id }}"
    @if($timetableEntry->start < \Carbon\Carbon::now()) disabled @endif
    >
    {{ $slot }}
</button>