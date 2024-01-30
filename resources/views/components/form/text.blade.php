@props([
    'ident' => '',
    'pht' => '',
    'lt' => '',
])

<div class="col-6">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <textarea class="form-control" type="text" id="{{ $ident }}" rows="5" placeholder="{{ $pht }}"></textarea>
</div>