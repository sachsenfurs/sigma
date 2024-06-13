@foreach($getRecord()->languages ?? $getRecord()->sigEvent->languages ?? [] as $language)
    <img src="/icons/{{ $language }}-flag.svg" alt="{{ $language }}" style="height: 1em; margin-right: 5px">
@endforeach

