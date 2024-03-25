@props([
    'ident' => '',
    'lt' => '',
    'size' => '',
    'cs' => '',
])

<div class="col{{ $cs }}">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <select class="form-select" multiple size="{{ $size }}" aria-label="Multiple select {{ $lt }}" id="{{ $ident }}">
        {{ $slot }}
    </select>
</div>
