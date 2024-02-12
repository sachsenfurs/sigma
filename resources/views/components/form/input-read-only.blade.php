@props([
    'ident' => "",
    'pht' => "",
    'lt' => "",
    'value' =>"",
])

<div class="col-6">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <input type="text" readonly class="form-control-plaintext" name="{{ $ident }}" placeholder="{{ $pht }}" value="{{ $value }}" />
</div>