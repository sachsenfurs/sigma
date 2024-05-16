@props([
    'type' => 'text',
    'name' => '',
    'placeholder' => "",
    'id' => null,
])
<x-form.input-error :name="$name" />
<div class="form-floating">
    @if($type=="select")<select @else <input type="{{ $type }}" @endif @class(['form-control', 'border-danger' => $errors->has($name)]) {{ $attributes->except("class") }} id="{{ $id ?? $name }}" name="{{ $name }}" value="{{ old($name) }}" placeholder="{{ $placeholder }}">
        {{ $slot }}
    @if($type=="select")</select>@endif
    <label for="{{ $id ?? $name }}">{{ $placeholder }}</label>
</div>

