@extends('layouts.app')
@section('title', "Hosts")
@section('content')
    <div class="container">
        <table class="table table-hover">
            <tr>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Anzahl SIGs</th>
                <th class="text-end">Aktionen</th>
            </tr>
            @forelse($hosts AS $host)
                <tr>
                    <td class="col-3">
                        <a href="{{ route("hosts.show", $host) }}">{{ $host->name }}</a>
                    </td>
                    <td>{{ $host->description }}</td>
                    <td class="col-1">{{ $host->sig_events_count }}</td>
                    <td class="text-end">
                        <a href="{{ route("hosts.edit", $host) }}">
                            <button type="button" class="btn btn-light"><i class="bi bi-pen"></i></button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Noch keine Hosts eingetragen</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
