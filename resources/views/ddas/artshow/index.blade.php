@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        @if(app(\App\Settings\ArtShowSettings::class)->show_items_date->isAfter(now()))
            <div class="card p-4 fs-1 text-center">
                {{ __("Art Show Items are not published yet") }}
            </div>
        @else

        @endif
    </div>
@endsection
