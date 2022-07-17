@extends('layouts.app')
@section('title', "Locations - {$location->name}")
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                {{ $location->name }}
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($location->sigEvents AS $sigEvent)
                        {{--
                        // FIXME
                        Events, die ne abweichende Location haben, werden hier nicht angezeigt
                        --}}
                        <div class="col-3">
                            <strong>
                                <a href="{{ route("sigs.edit", $sigEvent) }}">{{ $sigEvent->name }}</a>
                            </strong>
                        </div>
                        <div class="col-2">
                            @foreach($sigEvent->timetableEntries AS $entry)
                                <p>
                                    <b>{{ $entry->start->format("d.m.Y") }}</b><br>
                                    {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                                </p>
                            @endforeach
                        </div>
                        <div class="col-7">
                            <td>{{ $sigEvent->description }}</td>
                        </div>
                    @empty
                        Keine SIGs zugeordnet
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
