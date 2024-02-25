@props([
    'ident' => '',
    'lt' => '',
])

<div class="col col-sm-2">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <select class="form-control" name="{{ $ident }}">
        {{ $slot }}
    </select>
</div>
