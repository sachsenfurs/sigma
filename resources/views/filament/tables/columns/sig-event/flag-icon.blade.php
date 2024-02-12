@foreach($getRecord()->languages as $language)
    <img src="/icons/{{ $language }}-flag.svg" alt="" style="height: 1em; margin-right: 5px">
@endforeach
