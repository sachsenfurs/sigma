@props([
    'type' => 'text',
    'name' => '',
    'placeholder' => "",
    'id' => null,
])

<div class="form-floating">
    @if($type=="select")<select @else <input type="{{ $type }}" @endif {{ $attributes->merge(['class' => "form-control"]) }} id="{{ $id ?? $name }}" name="{{ $name }}" value="{{ old($name) }}" placeholder="{{ $placeholder }}">
        {{ $slot }}
    @if($type=="select")</select>@endif
    <label for="{{ $id ?? $name }}">{{ $placeholder }}</label>
</div>
