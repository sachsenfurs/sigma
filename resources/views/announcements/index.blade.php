@extends('layouts.app')
@section('title', __("Announcements"))

@section('content')
    <div class="container">
        <h2 class="pb-3 d-none d-lg-block">{{ __("Announcements") }}</h2>

        <livewire:announcements />
    </div>
@endsection
