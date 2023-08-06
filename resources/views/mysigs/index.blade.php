@extends('layouts.app')
@section('title', "Meine veranstalteten Events")
@section('content')
    <div class="container">
        <h1 class="text-center">My Events</h1>
        <div class="col-12 col-md-12 text-center">
            <div class="d-none d-xl-block">
                <div class="row border-bottom border-secondary mb-2">
                    <div class="col-3 col-md-3">
                        <strong>Title</strong>
                    </div>
                    <div class="col-3 col-md-3">
                        <strong>Location</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Attendees</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Favorites</strong>
                    </div>
                    <div class="col-1 col-md-1">
                        <strong>Actions</strong>
                    </div>
                </div>
            </div>
            @foreach ($sigs as $event)
                <div class="row mb-2" style="min-height: 50px;">
                    <div class="col-12 col-md-3 mt-1 mb-1">
                        <div class="row">
                            <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                <strong>Title</strong>
                            </div>
                            <div class="col-6 col-md-12">
                                <strong>{{ $event->name }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mt-1 mb-1">
                        <div class="row">
                            <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                <strong>Location</strong>
                            </div>
                            <div class="col-6 col-md-12">
                                {{ $event->sigLocation->name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-1 mt-1 mb-1">
                        <div class="row">
                            <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                <strong>Attendees</strong>
                            </div>
                            <div class="col-6 col-md-12">
                                <i class="bi bi-people-fill"></i> XXX
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-1 mt-1 mb-1">
                        <div class="row">
                            <div class="col-6 col-md-6 d-block d-sm-none align-right">
                                <strong>Favorites</strong>
                            </div>
                            <div class="col-6 col-md-12">
                                <i class="bi bi-heart-fill"></i> XXX
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-1 mt-1 mb-1 p-0">
                        <div class="row">
                            <div class="col-6 col-md-6 d-block d-sm-none align-items-center">
                                <strong>Actions</strong>
                            </div>
                            <div class="col-6 col-md-12">
                                <a type="button" class="btn btn-info text-white" href="/events/{{ $event->id }}">
                                    <i class="bi bi-info-circle-fill"></i>
                                </a>
                            </div>    
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
