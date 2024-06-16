@extends('layouts.app')
@section('title', __("Event Schedule"))

@section('content')
    @if(app(\App\Settings\AppSettings::class)->show_schedule_date->isAfter(now()))
        <x-infocard>
            {{ __("The Schedule is not published yet") }}
        </x-infocard>
    @else
        <div id="app">
            <entry-list></entry-list>
        </div>
   @endif
@endsection
