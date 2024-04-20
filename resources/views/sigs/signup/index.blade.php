@extends('layouts.app')
@section('title', __('SIG Overview'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">{{ __('SIG Overview') }}</h1>

        <div>
            <div class="d-flex justify-content-center p-3">
                <a href="{{ route('sigs.signup.create') }}" class="pb-2 btn btn-primary">{{ __('Create SIG') }}</a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">{{ __('My Events') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 col-md-2 pe-0 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col">
                                        {{ __('Reg Number') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col">
                                        {{ $user->reg_id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 col-md-3 pe-md-0">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col">
                                        {{ __('User') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 pe-md-0 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col">
                                        {{ __('E-Mail') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($sighost)
                            <div class="col-md-2">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('SIG Host') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $sighost->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <hr>
                    @if ($sighost)
                        @foreach ($sigs as $event)
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-4 mb-3 pe-md-0">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col">
                                                        {{ __('Name') }}
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col">
                                                        {{ $event->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3 pe-md-0">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col">
                                                        {{ __('SIG Location') }}
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col">
                                                        {{ $siglocations[$event->id]['location'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (
                                            $siglocations[$event->id]['time'] == 'Keine Zeit zugewiesen' ||
                                                $siglocations[$event->id]['time'] == 'No Time Assigned')
                                            <div class="col-md-4 mb-3 pe-md-0">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="col">
                                                            {{ __('SIG Date') }}
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col">
                                                            {{ $siglocations[$event->id]['time'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-4 mb-3 pe-md-0">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col col-md-12">
                                                                {{ __('SIG Date') }}
                                                            </div>
                                                            <div class="col col-md-12">
                                                                {{ __('SIG Time') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col col-md-12">
                                                                {{ $siglocations[$event->id]['time']->start->format('d.m.Y') }}
                                                            </div>
                                                            <div class="col col-md-10">
                                                                {{ $siglocations[$event->id]['time']->start->format('H:i') }}
                                                                -
                                                                {{ $siglocations[$event->id]['time']->end->format('H:i') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md">
                                    <div class="row">
                                        <div class="col-6 pe-md-0">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col">
                                                        SIG Beschreibung
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col">
                                                        {{ $event->description }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
