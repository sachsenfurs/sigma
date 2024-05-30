@props([
    'language' => []
])
@php
    if(!is_array($language))
        $language = [$language];
@endphp
@foreach($language AS $lang)
    <img src="/icons/{{ $lang }}-flag.svg" alt="{{ $lang }}" {{ $attributes->merge(['style' => 'height: 1em; margin-right: 5px; vertical-align: baseline']) }}>
@endforeach


