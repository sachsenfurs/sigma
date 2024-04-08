@extends('layouts.app')
@section('title', "SIG Übersicht")
@section('content')
    <div class="container">
        <div class="mt-4 mb-4 text-center">
            <a class="btn btn-primary" href="{{ route("sigs.create") }}">SIG Eintragen</a>
        </div>
        <table class="table table-hover">
            <tr>
                <th>Titel</th>
                <th>Host</th>
                <th>Sprachen</th>
                <th>Tags</th>
                <th>Im Programmplan</th>
                <th>Aktion</th>
            </tr>
            @forelse($sigs AS $sig)
                <tr class="@if($sig->timetableCount == 0) alert-danger @endif">
                    <td>
                        @if($sig->description == "" OR $sig->description_en == "")
                            <div class="badge bg-danger">Text unvollständig!</div>
                        @endif

                        <a href="{{ route("sigs.show", $sig) }}" class="text-decoration-none">
                            <h5 class="d-inline">
                                {{ $sig->name }}
                            </h5>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route("hosts.edit", $sig->sigHost) }}" class="btn btn-secondary">
                            <i class="bi bi-person-circle"></i>
                            {{ $sig->sigHost->name }}
                            @if($sig->sigHost->reg_id)
                                ({{ $sig->sigHost->reg_id }})
                            @endif
                        </a>
                    </td>
                    <td>
                        @foreach($sig->languages AS $lang)
                            <img src="/icons/{{ $lang }}-flag.svg" alt="" style="height: 1em">
                        @endforeach
                    </td>
                    <td>
                        @foreach($sig->sigTags AS $tag)
                            <span class="badge bg-success d-block m-1">
                                {{ $tag->description_localized }}
                            </span>
                        @endforeach
                    </td>
                    <td>{{ $sig->timetableCount }}</td>
                    <td>
                        <a href="{{ route("sigs.edit", $sig) }}">
                            <button type="button" class="btn btn-primary"><i class="bi bi-pen"></i></button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Noch keine SIGs eingetragen</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
