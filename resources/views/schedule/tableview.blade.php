@extends('layouts.app')
@section('title', __("Event Schedule"))

@section('content')
    <div class="container text-center">

        <h1>@lang("Event Schedule")</h1>
            <p class="mt-3">
                @lang("We have enhanced our schedule view")
            </p>
            <a href="{{ route("schedule.listview") }}" class="btn btn-primary btn-lg mt-1">@lang("Take me to the enhanced event schedule view")</a>


        <h2 class="mt-5">@lang("Calendar View")</h2>
        <p class="mt-3">
            @lang("Or do you prefer the calendar view?")
        </p>
        <a href="{{ route("schedule.calendarview") }}" class="btn btn-secondary btn-lg mt-1">@lang("Take me to the calendar view")</a>
    </div>
@endsection
