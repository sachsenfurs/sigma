@extends('layouts.app')
@section('title', __("Event Schedule"))

@section('content')
    @if(app(\App\Settings\AppSettings::class)->show_schedule_date->isAfter(now()))
        <div class="container text-center">
            <div class="card p-4 fs-1">
                {{ __("The Schedule is not published yet") }}
            </div>
        </div>
    @else
        <div id="calendar">
            <sig-calendar></sig-calendar>
        </div>
   @endif
@endsection
