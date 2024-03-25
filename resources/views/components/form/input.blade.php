@props([
    'ident' => "",
    'pht' => "",
    'lt' => "",
    'value' =>"",
    'size' => "",
])

<div class="col{{ $size }}">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <input type="text" class="form-control" name="{{ $ident }}" placeholder="{{ $pht }}" value="{{ $value }}">
</div>