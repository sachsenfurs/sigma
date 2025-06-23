@props([
    'name' => "status",
])
@if(session($name))
    <div wire:key="{{ rand(0,999999) }}" class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition.duration.500ms>
        {{ session($name) }}
    </div>
@endif
