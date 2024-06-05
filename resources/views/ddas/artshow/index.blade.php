@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        <h2 class="pb-3">
            {{ __('Art Show Item List') }}
        </h2>

        @if(app(\App\Settings\ArtShowSettings::class)->show_items_date->isAfter(now()))
            {{ __("Art Show Items are not published yet") }}
        @else

        @endif
    </div>
@endsection
