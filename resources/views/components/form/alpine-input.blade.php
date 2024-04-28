@props([
    'type' => "text",
    'label' => "",
    'for' => "",
])
<div x-data="input">
    @if($label)
        <label class="form-label" for="{{ $attributes->get("id", $attributes->get("name", $for)) }}">{{ $label }}</label>
    @endif

    <div @class(['input-group' => $attributes->get("group-text")])>
        <x-form.input :type="$type" {{ $attributes }} x-effect="validate" x-bind:class="error && 'border-danger'" />

        @if($attributes->get("group-text"))
            <span class="input-group-text">{{ $groupText }}</span>
        @endif
    </div>

    <span class="text-danger d-block" x-cloak x-show="error" x-text="error"></span>
    {{ $slot }}
</div>
