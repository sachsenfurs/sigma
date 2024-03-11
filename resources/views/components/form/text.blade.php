@props([
    'ident' => '',
    'pht' => '',
    'lt' => '',
    'size' => '',
    'value' => '',
])

<div class="col{{ $size }}">
    <label for="{{ $ident }}" class="form-label">{{ $lt }}</label>
    <textarea class="form-control" type="text" name="{{ $ident }}" rows="5" placeholder="{{ $pht }}" value="{{$value}}"></textarea>
</div>