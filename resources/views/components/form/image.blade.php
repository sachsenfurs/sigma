@props([
    'ident' => '',
    'lt' => '',
    'size' => '',
])

<div class="col{{ $size }}">
        <label for="image" class="form-label">{{ $lt }}</label>
        <input type="file" class="form-control" id="image" name="{{ $ident }}">
</div>