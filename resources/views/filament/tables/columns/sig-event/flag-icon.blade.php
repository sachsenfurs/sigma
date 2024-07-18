@foreach(collect($getRecord()->languages)->sort() ?? collect($getRecord()->sigEvent->languages)->sort() ?? [] as $language)
    <img src="/icons/{{ $language }}-flag.svg" alt="{{ $language }}" style="height: 1em; margin-right: 5px">
@endforeach

