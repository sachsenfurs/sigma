@extends('layouts.app')

@section('content')
@foreach($days AS $day=>$entries)
    <div class="container">
        <h2 class="mt-2">{{ $day }}</h2>

        @foreach($entries AS $entry)
            <div class="card mb-3">
                <div class="card-header">
                    <p>{{ $entry->sigEvent->name_localized }}
                        @foreach($entry->sigEvent->languages AS $language)
                            <x-flag :language="$language" />
                        @endforeach
                    </p>
                </div>
                <div class="card-body">
                    <div class="card-subtitle">
                        {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                        |
                        {{ $entry->sigLocation->name }}
                        @if(!$entry->sigEvent->primaryHost?->hide)
                            | {{ Str::plural("Host", $entry->sigEvent->publicHosts->count()) }}:
                            {{ $entry->sigEvent->publicHosts->pluck("name")->join(", ") }}
                        @endif
                    </div>
                    <div class="mt-2">
                        <x-markdown>
                            {{ $entry->sigEvent->description_localized }}
                        </x-markdown>
                    </div>
                </div>
                <div class="card-footer">
                    @foreach($entry->sigEvent->sigTags AS $tag)
                        @if($tag->name == "signup")
                            <p>
                                <span class="badge bg-secondary">{{ $tag->description_localized }}</span>
                                <img src="{{ $entry->qrCode() }}"
                                     alt="{{ app(\App\Settings\AppSettings::class)->short_domain ? "https://" . app(\App\Settings\AppSettings::class)->short_domain . $entry->id : $entry->routeUrl("timetable-entry.show") }}"
                                     class="img-thumbnail" style="max-height: 12em;"
                                />
                            </p>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

@endforeach


@endsection
