@props([
    'ident' => '',
    'lt' => '',
])

    <div class="col-4">
        <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
        <input type="file" class="form-control" id="{{ $ident }}" name="image">
    </div>
