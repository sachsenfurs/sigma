@props([
    'ident' => '',
    'lt' => '',
    'size' => '',
])

<div class="col{{ $size }}">
        <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
        <input type="file" class="form-control" id="{{ $ident }}" name="image">
</div>