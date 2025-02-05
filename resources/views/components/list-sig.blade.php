@props([
    'sig',
])

<div @class(['card mb-4', 'bg-purple-800' => $sig->is_private])>
    <div class="card-header">
        <h4 class="d-inline">
            @if($sig->is_private)
                <i class="bi bi-lock icon-link"></i>
            @endif
            {{ $sig->name_localized }}
        </h4>
    </div>
    @if($sig->description_localized)
        <div class="card-body">
            <x-markdown>
                {{ $sig->description_localized }}
            </x-markdown>
        </div>
    @endif

    @forelse($sig->timetableEntries AS $entry)
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-auto d-flex">
                        <i class="bi bi-clock align-self-center"></i>
                    </div>
                    <a href="{{ route("timetable-entry.show", $entry) }}" class="text-decoration-none" style="flex: content">
                    <div class="col-auto">
                        <span class="fw-bold">{{ $entry->start->dayName }}</span><span class="text-muted fw-light"> &mdash; {{ $entry->start->format("d.m.y") }}</span>
                        <div class="text-muted fw-light">
                            {{ $entry->start->format("H:i") }} @if($entry->duration > 0) - {{ $entry->end->format("H:i") }}@endif
                        </div>
                    </div>
                    </a>
                    <div class="col-auto d-flex">
                        <div class="align-self-center">
                            @if($entry->cancelled)
                                <h2><span class="badge bg-danger">{{ __("Cancelled") }}</span></h2>
                            @else
                                @if($sig->reg_possible)
                                    <a href="{{ route("timetable-entry.show", $entry) }}" class="btn btn-success">{{ __("Click here to sign up") }}</a>
                                @endif
                                @if($entry->hasTimeChanged)
                                    <h2>
                                        <span class="badge bg-warning">{{ __("Changed") }}</span>
                                    </h2>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if((Route::is("locations.*") OR $sig->publicHosts->count() > 1) AND ($sig->primaryHost AND !$sig->primaryHost?->hide))
                    <div class="row mt-2">
                        <div class="col-auto d-flex">
                            <i class="bi bi-person-circle align-self-center"></i>
                        </div>
                        <div class="col-auto">
                            @foreach($sig->publicHosts AS $host)
                                <a href="{{ route("hosts.show", $host) }}" class="text-decoration-none">
                                    <span class="fw-light">{{ $host->name }}</span>@if(!$loop->last), @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(Route::is("hosts.*"))
                    <div class="row mt-2">
                        <div class="col-auto d-flex">
                            <i class="bi bi-geo-alt align-self-center"></i>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route("locations.show", $entry->sigLocation) }}" class="text-decoration-none">
                                <span class="fw-light">{{ $entry->sigLocation->name_localized }}</span>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-auto d-flex">
                        <div class="align-self-center">
                            @if($entry->hasLocationChanged)
                                <span class="badge bg-warning">{{ __("Changed") }}</span>
                            @endif
                        </div>
                    </div>
                </div>

            </li>
        </ul>

    @empty
        <div class="card-footer">
            {{ __("Not listed in schedule") }}
        </div>
    @endforelse
</div>
