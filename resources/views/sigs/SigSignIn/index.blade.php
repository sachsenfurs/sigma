@extends('layouts.app')
@section('title', __('Sig Anmeldung'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">Sig Anmeldung</h1>

        <div class="m-5">
            <a href="{{ route('sigsignin.create') }}">Create New Sig &rarr;</a>


            <div class="m-auto">
                <p>
                    {{ $user->name }}
                </p>
                <p class="pb-3">
                    {{ $sighost->name }}
                </p>

                <hr class="py-3">
            </div>
            @foreach ($sigs as $event)
                <p class="py-2">
                    {{ $event->name }}
                </p>
                <p class="py-2">
                    {{ $event->description }}
                </p>
                <p class="py-2">
                    {{ $siglocations[$event->id]['location']->name }}
                </p>
                <p class="py-2">
                    {{ $siglocations[$event->id]['time']->start }}
                    -
                    {{ $siglocations[$event->id]['time']->end }}
                </p>
                <hr class="py-3">
            @endforeach
        </div>

    </div>
@endsection
