@extends('layouts.app')
@section('title', __('SIG Overview'))

@section('content')
    <div class="container">
        <h2>{{ __('SIG Overview') }}</h2>

        @can("create", \App\Models\SigEvent::class)
            <div class="row justify-content-center">
                <div class="col-12 col-lg-4">
                    <a href="{{ route("sigs.create") }}" class="btn card btn-success fs-5">
                        <div class="card-body">
                            <i class="bi bi-plus icon-link"></i> {{ __("Register a new SIG") }}
                        </div>
                    </a>
                </div>
            </div>
        @endcan

        @foreach($sigHosts AS $host)
            <h5 class="py-2 fw-light pt-4">{{ __("Registered SIGs for :name", ['name' => $host->name]) }}</h5>
            <section class="accordion pb-4" id="sigList">
                @foreach($host->sigEvents AS $sig)
                    <article class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$sig->id}}" aria-expanded="false" aria-controls="collapse{{$sig->id}}">
                                <div class="row w-100">
                                    @if(!$sig->approved)
                                        <div class="col-12 text-start pb-2">
                                            <span @class(['badge d-inline-block fw-normal p-2 text-uppercase', $sig->approval->style()])
                                                  style="font-size:0.75rem">
                                                {{ $sig->approval->name() }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="col-12 d-flex align-content-center">
                                        <span class="fs-5 me-2 align-self-center">
                                            {{ $sig->name_localized }}
                                        </span>
                                        <x-flag :language="$sig->languages" class="align-self-center"/>
                                        <span class="d-inline-flex gap-1">
                                            <span class="badge fs-6 bg-dark-subtle text-secondary align-content-center">{{ $sig->duration/60 }} h</span>
                                            @if($sig->favorites_count > 0)
                                                <span class="badge fs-6 bg-dark-subtle text-secondary align-content-center"><i class="bi bi-heart-fill"></i> {{ $sig->favorites_count }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{$sig->id}}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="row">
                                    <div @class(['col-12', 'col-xl-6' => $sig->name_localized_other]) style="text-align: justify">
                                        <h4>{{ __("Description") }}</h4>
                                        <x-markdown>
                                            {{ $sig->description_localized }}
                                        </x-markdown>
                                    </div>
                                    @if($sig->name_localized_other)
                                        <div class="col-12 col-xl-6 fst-italic text-secondary" style="text-align: justify">
                                            <h4>{{ __("Translation") }}</h4>
                                            <h5 class="me-2">
                                                {{ $sig->name_localized_other }}
                                            </h5>
                                            <x-markdown>
                                                {{ $sig->description_localized_other }}
                                            </x-markdown>
                                        </div>
                                    @endif

                                    @if($sig->additional_info)
                                        <div class="col-12 my-4">
                                            <h4>
                                                {{ __("Organizational Information") }}
                                            </h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    {!! nl2br(e(($sig->additional_info))) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <h4>{{ __("Scheduled") }}</h4>
                                </div>

                                <div class="row justify-items-center g-3">
                                    @if(!app(\App\Settings\AppSettings::class)->isSchedulePublic())
                                        <div class="alert alert-info bg-info-subtle mt-4">
                                            {{ __("Note: The schedule has not yet been published. Changes can be made at any time!") }}
                                        </div>
                                    @endif
                                    @forelse($sig->timetableEntries AS $entry)
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="card">
                                                <div class="card-body row">
                                                    <div class="col">
                                                        <div>
                                                            <i class="bi bi-calendar-day icon-link"></i>
                                                            {{ $entry->start->dayName }}, {{ $entry->start->format("d.m.Y") }}
                                                        </div>
                                                        <div>
                                                            <i class="bi bi-clock icon-link"></i>
                                                            {{ $entry->start->format("H:i") }} - {{ $entry->end->format("H:i") }}
                                                        </div>
                                                    </div>
                                                    <div class="col align-content-center">
                                                        <div class="mt-1 text-center">
                                                            <i class="bi bi-geo-alt icon-link"></i>
                                                            <a href="{{ route("locations.show", $entry->sigLocation) }}" class="text-decoration-none">
                                                                {{ $entry->sigLocation->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col">
                                            {{ __("This event is not listed in the schedule yet") }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endforeach
    </div>
@endsection
