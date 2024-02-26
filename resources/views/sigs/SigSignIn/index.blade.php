@extends('layouts.app')
@section('title', __('Sig Anmeldung'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">Sig Anmeldung</h1>

        <div class="m-5">
            <div class="d-flex justify-content-center p-3">
                <a href="{{ route('sigsignin.create') }}" class="pb-2 btn btn-primary">Create New Sig</a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Sig List</h3>
                </div>
                <div class="card-body">
                    <div class="m-auto">
                        <p>
                            {{ $user->name }}
                        </p>
                        <p>
                            {{ $user->reg_id }}
                        </p>
                        <p>
                            {{ $user->email }}
                        </p>
                        @if ($sighost)
                            <p class="pb-3">
                                {{ $sighost->name }}
                            </p>
                        @endif
        
                        <hr class="py-3">
                    </div>
                    @if ($sighost)
                        @foreach ($sigs as $event)
                            <p class="py-2">
                                {{ $event->name }}
                            </p>
                            <p class="py-2">
                                {{ $event->description }}
                            </p>
                            <p class="py-2">
                                {{ $siglocations[$event->id]['location'] }}
                            </p>
                            @if ($siglocations[$event->id]['time'] == "Keine Zeit eingetragen")
                                <p class="py-2">
                                    {{ $siglocations[$event->id]['time'] }}
                                </p>
                            @else
                            <p class="py-2">
                                {{ $siglocations[$event->id]['time']->start }}
                                -
                                {{ $siglocations[$event->id]['time']->end }}
                            </p>
                            @endif
                            <hr class="py-3">
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
