@props([
    'sig',
])

<div class="card mb-4">
    <div class="card-header">
        <h4 class="d-inline">{{ $sig->name }}</h4>
        @can("manage_events")
            <a href="{{ route("sigs.edit", $sig) }}" class="inline float-end"><i class="bi bi-pen"></i> Edit</a>
        @endcan
    </div>

    <div class="card-body">
        {{ $sig->description }}
    </div>

    @forelse($sig->timetableEntries AS $entry)
        @if($entry->hide AND !auth()?->user()?->can("manage_events"))
            @continue
        @endif
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-auto d-flex">
                        <i class="bi bi-clock align-self-center"></i>
                    </div>
                    <div class="col-auto">
                        <b>{{ $entry->start->format("l") }}</b> <i class="text-muted"> {{ $entry->start->format("d.m.y") }}</i>
                        <div class="text-muted">
                            {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                        </div>
                    </div>
                    <div class="col-auto d-flex">
                        <div class="align-self-center">
                            @if($entry->cancelled)
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                @if($entry->sigEvent->reg_possible)
                                    <a href="{{ route("public.timeslot-show", $entry) }}" class="btn btn-success">Click here to sign up</a>
                                @endif
                                @if($entry->hasTimeChanged())
                                    <span class="badge bg-warning">Changed</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    @if(Route::is("locations.*") AND !$entry->sigEvent->sigHost->hide)
                        <div class="col-auto d-flex">
                            <i class="bi bi-person-circle align-self-center"></i>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route("hosts.show", $entry->sigEvent->sigHost) }}" class="text-decoration-none">
                                <b>{{ $entry->sigEvent->sigHost->name }}</b>
                            </a>
                        </div>
                    @endif
                    @if(Route::is("hosts.*"))
                        <div class="col-auto d-flex">
                            <i class="bi bi-geo-alt align-self-center"></i>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route("locations.show", $entry->sigLocation) }}" class="text-decoration-none">
                                <b>{{ $entry->sigLocation->name }}</b>
                            </a>
                        </div>
                    @endif


                    <div class="col-auto d-flex">
                        <div class="align-self-center">
                            @if($entry->hasLocationChanged())
                                <span class="badge bg-warning">Changed</span>
                            @endif
                        </div>
                    </div>
                </div>

            </li>
        </ul>

    @empty
        <div class="card-footer">
            Nicht im Programmplan
        </div>
    @endforelse
</div>
