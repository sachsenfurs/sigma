@props([
    'language' => [],
    'height' => '1.4em',
])
@php
    if(!is_array($language))
        $language = [$language];
@endphp
@foreach($language AS $lang)
    <img src="{{ url("/icons/$lang-flag.svg") }}" alt="{{ $lang }}" {{ $attributes->merge(['style' => 'height: '.$height, 'class' => 'rounded-1 me-1']) }}>
@endforeach


