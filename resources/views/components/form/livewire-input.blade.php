@props([
    'type' => "text",
    'label' => "",
    'for' => "",
    'name' => "",
])
@if($label)
    <label class="form-label" for="{{ $attributes->get("id", $name) }}">{{ $label }}</label>
@endif
@if($attributes->get("group-text")) <div class="input-group"> @endif

    <x-form.input :type="$type" {{ $attributes }} :name="$name" @class(['border-danger' => $errors->has($name)]) wire:model="{{ $name }}"/>

    @if($attributes->get("group-text"))
        <span class="input-group-text">{{ $groupText }}</span>
    @endif

@if($attributes->get("group-text")) </div> @endif
<x-form.input-error :name="$name" />
{{ $slot }}
