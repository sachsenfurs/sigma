@extends('layouts.app')
@section('title', "Hosts")
@section('content')
    <div class="container">
        @foreach($hosts AS $host)
            <x-list-host-location
                :instance="$host"
                :link="route('hosts.show', $host)"
                :title="$host->name"
                :hide="$host->hide"
                :edit_link="\App\Filament\Resources\SigHostResource::getUrl('edit', [ 'record' => $host ])"
                :avatar="$host->avatar"
            >
                {{ $host->description }}
            </x-list-host-location>
        @endforeach

    </div>
@endsection
