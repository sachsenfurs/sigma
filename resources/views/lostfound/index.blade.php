@extends('layouts.app')
@section('title', "Lost & Found")
@section('content')
    <div class="container">

        <h2>{{ __("Found") }}</h2>
        @forelse(\App\Models\LostFoundItem::found()->get() AS $item)
            <x-lostfound.card :item="$item"></x-lostfound.card>
        @empty
            {{ __("Nothing has been found yet") }}
        @endforelse

        <h2 class="mt-3">{{ __("Lost") }}</h2>
        @forelse(\App\Models\LostFoundItem::lost()->get() AS $item)
            <x-lostfound.card :item="$item"></x-lostfound.card>
        @empty
            {{ __("Nothing has been lost yet") }}
        @endforelse

    </div>

@endsection
