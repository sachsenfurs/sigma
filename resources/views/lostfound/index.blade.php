@extends('layouts.app')
@section('title', "Lost & Found")
@section('content')
    <div class="container">
        <div class="alert alert-info row m-1 mb-3 d-flex align-items-center">
            <div class="col-auto ">
                <i class="bi bi-exclamation-lg fs-4"></i>
            </div>
            <div class="col">
                {{ __("If you lost or found something, please reach out to Con Ops (Leitstelle)!") }}
            </div>
        </div>

        <x-tabs :tabs="['Lost', 'Found']">
            @slot("Lost")
                @forelse(\App\Models\LostFoundItem::lost()->get() AS $item)
                    <x-lostfound.card :item="$item"></x-lostfound.card>
                @empty
                    <div class="p-3">
                        {{ __("Nothing has been lost yet") }}
                    </div>
                @endforelse
            @endslot

            @slot("Found")
                @forelse(\App\Models\LostFoundItem::found()->get() AS $item)
                    <x-lostfound.card :item="$item"></x-lostfound.card>
                @empty
                    <div class="p-3">
                        {{ __("Nothing has been found yet") }}
                    </div>
                @endforelse
            @endslot
        </x-tabs>
    </div>

@endsection
