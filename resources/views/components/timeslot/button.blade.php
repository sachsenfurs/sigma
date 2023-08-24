@props([
    'disabled' => true,
    'class' => "btn-primary",
])
<a class="col-lg-4 col-12 border align-middle text-center btn {{ $class }}" @if($disabled) disabled @endif {{ $attributes->only("onclick") }}>
    {{ $slot }}
</a>
