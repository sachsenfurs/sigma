@props([
    'name',
])
@error($name)
    <span {{ $attributes->class(['text-danger d-block']) }}>{{ $message }}</span>
@enderror
