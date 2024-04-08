@extends('layouts.app')
@section('title', "Meine Events")
@section('content')
    <div class="container">
        <h1 class="text-center">My Events</h1>
        <div class="col-8 col-md-8 text-center mx-auto">
            <div class="d-none d-xl-block">
                <div class="row border-bottom border-secondary mb-2">
                    <div class="col-3 col-md-3">
                        <strong>{{ __("Name") }}</strong>
                    </div>
                    <div class="col-3 col-md-3">
                        <strong>{{ __("Attendees") }}</strong>
                    </div>
                    <div class="col-3 col-md-3">
                        <strong>{{ __("Favorites") }}</strong>
                    </div>
                </div>
            </div>
            @foreach ($sigs as $event)
                <div class="row mb-2" style="min-height: 50px;">
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                    <strong>{{ __("Name") }}</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    <a href="{{ route('sigs.show', $event->id) }}">
                                        <strong>{{ $event->name }}</strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                    <strong>{{ __("Attendees") }}</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    <i class="bi bi-people-fill"></i> {{ $additionalInformations[$event->id]['attendees']}}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mt-1 mb-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                    <strong>{{ __("Favorites") }}</strong>
                                </div>
                                <div class="col-6 col-md-12">
                                    <i class="bi bi-heart-fill"></i> {{ $additionalInformations[$event->id]['favorites']}}
                                </div>
                            </div>
                        </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
