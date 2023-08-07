@extends('layouts.app')
@section('title', "{$host->name}")
@section('content')
    <div class="container">

        <div class="row justify-content-md-center">
            <div class="col-xl-12 mb-3">
                <div class="d-flex align-items-start p-2">
                    @if($host->avatar)
                        <img src="{{ $host->avatar }}" alt="" class="rounded-circle img-thumbnail" style="width: 10em">
                    @endif
                    <div class="w-100 ms-3 align-self-center">
                        <h2>{{ $host->name }}</h2>
                        <p class="text-muted">{{ $host->description }}</p>
                        @can("manage_hosts")
                            <a href="{{ route("hosts.edit", $host) }}"><i class="bi bi-pen"></i> Edit</a>
                        @endcan
                    </div>
                </div>
            </div>

        </div>
        @forelse($sigs AS $sig)
            @if(!$sig->isCompletePrivate())
                <x-list-sig :sig="$sig" />
            @endif
        @empty
            <div class="alert alert-info">
                Keine SIGs zugeordnet
            </div>
        @endforelse

    </div>
@endsection
