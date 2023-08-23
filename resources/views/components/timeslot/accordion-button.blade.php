@props([
    'entry',
    'class'
])

<button {{ $attributes->merge(['class' => "accordion-button"]) }}
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#panelsStayOpen-collapse-ts_{{ $entry->id }}"
        aria-expanded="true"
        aria-controls="panelsStayOpen-collapseOne"
>
    {{ $entry->start->dayName }}, {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
    @isset($slot)
        <br>
        {{ $slot }}
   @endisset
</button>
