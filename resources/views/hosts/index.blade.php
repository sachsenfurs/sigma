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
        @endforeach

    </div>
@endsection
