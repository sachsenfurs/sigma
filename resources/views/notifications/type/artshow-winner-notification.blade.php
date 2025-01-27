<p>
    {{ __("You have won the following items in the art show:") }}
</p>
<p>
    @foreach($artshowItems AS $item)
        {{ $item->name }}, {{ $item->highestBid?->value }} EUR<br>
    @endforeach
</p>
<p>
    {{ __("Please visit Dealers' Den for pickup (Refer to the con book for opening hours!)") }}
</p>
