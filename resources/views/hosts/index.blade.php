@extends('layouts.app')
@section('title', "Hosts")
@section('content')
    <div class="container">
        @foreach($hosts AS $host)
            <x-list-host-location
                :instance="$host"
                :link="route('hosts.show', $host)"
                :title="$host->name"
                edit_permission="manage_hosts"
                :hide="$host->hide"
                :edit_link="route('hosts.edit', $host)"
                :avatar="$host->avatar"
            >
                {{ $host->description }}
            </x-list-host-location>
{{--            @if($host->hide AND !auth()?->user()?->can("manage_hosts"))--}}
{{--                @continue--}}
{{--            @endif--}}
{{--            <div class="card mt-3">--}}
{{--                <div class="row g-0">--}}
{{--                    @if($host->avatar)--}}
{{--                        <div class="col-md-2" style="max-height: 100%">--}}
{{--                            <img src="{{ $host->avatar }}" class="img-fluid h-100 w-100 rounded-top" style="object-fit: cover; max-height: 30vw" alt="">--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <div class="@if($host->avatar)col-md-8 @else col-md-10 @endif">--}}
{{--                        <div class="card-body">--}}
{{--                            <h2 class="card-title">--}}
{{--                                <i class="bi bi-person-circle"></i>--}}
{{--                                {{ $host->name }}--}}
{{--                            </h2>--}}
{{--                            <h6 class="card-subtitle mb-2 text-body-secondary"></h6>--}}
{{--                            <p class="card-text">{{ $host->description }}</p>--}}
{{--                            <a href="{{ route("hosts.show", $host) }}" class="stretched-link"> </a>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                    <div class="col-md-2 align-middle">--}}
{{--                        <div class="container d-flex h-100 w-100">--}}
{{--                            <div class="align-self-center w-100 m-3" style="text-align: right">--}}
{{--                                <div class="display-6">{{ $host->sig_events_count }}</div>--}}
{{--                                {{ Str::plural("Event", $host->sig_events_count) }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @can("manage_hosts")--}}
{{--                <div class="card-footer">--}}
{{--                    <div class="w-100 container p-2">--}}
{{--                        <a href="{{ route("hosts.edit", $host) }}" class="">--}}
{{--                            <i class="bi bi-pen"></i> Edit--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endcan--}}
        @endforeach

    </div>
@endsection
