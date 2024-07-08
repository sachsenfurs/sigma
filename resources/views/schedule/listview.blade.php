@extends('layouts.app')
@section('title', __("Event Schedule"))

@section('content')
    @if(app(\App\Settings\AppSettings::class)->show_schedule_date->isAfter(now()) AND \Illuminate\Support\Facades\Gate::denies("viewAny", \App\Models\TimetableEntry::class))
        <x-infocard>
            {{ __("The Schedule is not published yet") }}
        </x-infocard>
    @else
        <div id="app">
            <entry-list></entry-list>
        </div>
   @endif
@endsection
