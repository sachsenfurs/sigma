@props([
    'sig',
])

<div class="card mb-4">
    <div class="card-header">
        <h4 class="d-inline">
            {{ $sig->name_localized }}
        </h4>

        @can("update", $sig)
            <a href="{{ \App\Filament\Resources\SigEventResource::getUrl('edit', ['record' => $sig]) }}" class="inline float-end"><i class="bi bi-pen"></i> Edit</a>
        @endcan
    </div>
    @if($sig->description_localized)
        <div class="card-body">
            <x-markdown>
                {{ $sig->description_localized }}
            </x-markdown>
        </div>
    @endif

    @forelse($sig->timetableEntries()->public()->get() AS $entry)
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-auto d-flex">
                        <i class="bi bi-clock align-self-center"></i>
                    </div>
                    <a href="{{ route("timetable-entry.show", $entry) }}" class="text-decoration-none" style="flex: content">
                    <div class="col-auto">
                        <b>{{ $entry->start->dayName }}</b> <i class="text-muted"> {{ $entry->start->format("d.m.y") }}</i>
                        <div class="text-muted">
                            {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                        </div>
                    </div>
                    </a>
                    <div class="col-auto d-flex">
                        <div class="align-self-center">
                            @if($entry->cancelled)
                                <h2><span class="badge bg-danger">{{ __("Cancelled") }}</span></h2>
                            @else
                                @if($entry->sigEvent->reg_possible)
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

                @if((Route::is("locations.*") OR $entry->sigEvent->publicHosts->count() > 1) AND !$entry->sigEvent->primaryHost->hide)
                    <div class="row mt-2">
                        <div class="col-auto d-flex">
                            <i class="bi bi-person-circle align-self-center"></i>
                        </div>
                        <div class="col-auto">
                            @foreach($entry->sigEvent->publicHosts AS $host)
                                <a href="{{ route("hosts.show", $host) }}" class="text-decoration-none">
                                    <b>{{ $host->name }}</b>@if(!$loop->last), @endif
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
                                <b>{{ $entry->sigLocation->name }}</b>
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
