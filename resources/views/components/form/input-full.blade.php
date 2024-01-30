@props([
    'ident' => "",
    'pht' => "",
    'lt' => "",
])

<div class="col-6">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <input type="text" class="form-control" id="{{ $ident }}" placeholder="{{ $pht }}">
</div>