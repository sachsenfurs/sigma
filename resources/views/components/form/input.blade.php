@props([
    'type' => "text",
    'name' => "",
    'id' => null,
])
@if($type == "textarea")
<textarea name="{{$name}}" id="{{ $id ?? $name }}" {{ $attributes->merge(['class' => "form-control"])->except("value") }} wire:keydown.stop.enter>{{ $attributes->get("value", old($name)) }}</textarea>
@else
<input type="{{ $type }}" name="{{$name}}" id="{{ $id ?? $name }}" {{ $attributes->merge(['class' => "form-control"]) }} value="{{ old($name) }}">
@endif
