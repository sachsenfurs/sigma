@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        @if(app(\App\Settings\ArtShowSettings::class)->show_items_date->isAfter(now()))
            <x-infocard>
                {{ __("Art Show Items are not published yet") }}
            </x-infocard>
        @else
            @if($error)
                <x-infocard>
                    {{ $error }}
                </x-infocard>
            @else
                <livewire:ddas.artshow-items />
            @endif
        @endif
    </div>
@endsection
