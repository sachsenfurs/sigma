@props([
    'ident' => "",
    'pht' => "",
    'lt' => "",
])

<div class="col-1">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <input type="text" class="form-control" name="{{ $ident }}" placeholder="{{ $pht }}">
</div>