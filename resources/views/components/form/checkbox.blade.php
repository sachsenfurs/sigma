@props([
    'ident' => '',
    'lt' => '',
    'value' => '',
])

<div class="form-check">
    <input class="form-check-input" type="checkbox" value="{{ $value }}" id="{{ $ident }}">
    <label class="form-check-label" for="{{ $ident }}">
      {{ $lt }}
    </label>
  </div>