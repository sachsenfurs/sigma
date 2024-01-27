@props([
    'disabled' => true,
    'class' => "btn-primary",
])
<div class="col-lg-4 col-12 align-self-center p-1 my-1">
    <a class="align-middle text-center btn w-100 {{ $class }}" @if($disabled) disabled @endif {{ $attributes->only("onclick") }}>
        {{ $slot }}
    </a>
</div>

