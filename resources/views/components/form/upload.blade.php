@props([
    'ident' => "",
    'lt' => "",
    'size' => "",
])

<div class="input-group mb-3 col{{ $size }}">
    <input type="file" class="form-control" id="{{ $ident }}">
    <label class="input-group-text" for="{{ $ident }}">{{ $lt }}</label>
</div>