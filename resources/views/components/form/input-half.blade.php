@props([
    'ident' => "",
    'pht' => "",
    'lt' => "",
    'value' =>"",
])

<div class="col-4">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <input type="text" class="form-control" name="{{ $ident }}" placeholder="{{ $pht }}" value="{{ $value }}>
</div>