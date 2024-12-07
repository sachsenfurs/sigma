@extends('layouts.app')
@section('title', __("Hosts"))
@section('content')
    <div class="container">
        @forelse($hosts AS $host)
            <x-list-host-location
                :instance="$host"
                :link="route('hosts.show', $host)"
                :title="$host->name"
                :hide="$host->hide"
                :avatar="$host->avatar"
            >
                {{ $host->description }}
            </x-list-host-location>
        @empty
            <x-infocard>
                {{ __("The Schedule is not published yet") }}
            </x-infocard>
        @endforelse

    </div>
@endsection
