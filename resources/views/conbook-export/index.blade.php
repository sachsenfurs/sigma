@extends('layouts.app')

@section('content')
@foreach($days AS $day=>$entries)
    <div class="container">
        <h2 class="mt-2">{{ $day }}</h2>

        @foreach($entries AS $entry)
            <div class="card mb-3">
                <div class="card-header">
                    {{ $entry->sigEvent->name }}
                    @foreach($entry->sigEvent->languages AS $language)
                        <img src="/icons/{{$language}}-flag.svg" style="height: 15px" alt="{{ strtoupper($language) }}">
                    @endforeach
                </div>
                <div class="card-body">
                    <div class="card-subtitle">
                        {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                        |
                        {{ $entry->sigLocation->name }}
                        @if(!$entry->sigEvent->sigHost->hide)
                            | Host: {{ $entry->sigEvent->sigHost->name }}
                        @endif
                    </div>
                    <div class="mt-2">
                        <x-markdown>
                            {{ $entry->sigEvent->description_localized }}
                        </x-markdown>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endforeach


@endsection
