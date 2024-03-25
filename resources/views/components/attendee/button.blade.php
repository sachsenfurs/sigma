@props([
    'disabled' => true,
    'class' => "btn-primary",
])
<a class="{{ $class }}" @if($disabled) disabled @endif {{ $attributes->only("onclick") }}>
    {{ $slot }}
</a>

