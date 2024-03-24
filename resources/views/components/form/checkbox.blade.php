@props([
    'ident' => '',
    'lt' => '',
    'value' => '1',
])

<div class="form-check">
  <!-- Verstecktes Feld, das einen Wert von 0 sendet, wenn die Checkbox nicht angekreuzt ist -->
  <input type="hidden" value="0" name="{{ $ident }}">
  <!-- Ihre existierende Checkbox -->
  <input class="form-check-input" type="checkbox" value="{{ $value ?? '1' }}" name="{{ $ident }}" id="{{ $ident }}">
  <label class="form-check-label" for="{{ $ident }}">
      {{ $lt }}
  </label>
</div>
