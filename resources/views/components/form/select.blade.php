@props([
    'ident' => '',
    'lt' => '',
])

<div class="col col-sm-2">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <select name="Test" class="form-control" id="{{ $ident }}">
        {{ $slot }}
    </select>
</div>
