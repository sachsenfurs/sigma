@extends('layouts.app')
@section('title', __('Art Show Item'))

@section('content')

    <div class="container">
        <h1 class="pt-2 pb-5 text-center">{{ __('Show Art Show Items') }}</h1>

        <div class="card">
            <div class="card-header">
                <h3 class="text-center">{{ __('Show Item') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col mb-3 pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('Artist') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $as_artist->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-md-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('Item Name') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3 pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('Approved') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            @if ($item->approved == 0)
                                                {{ __('No') }}
                                            @elseif ($item->approved == 1)
                                                {{ __('Yes') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('Sold') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            @if ($item->sold == 0)
                                                {{ __('No') }}
                                            @elseif ($item->sold == 1)
                                                {{ __('Yes') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-md-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('Start Bid') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->starting_bid }} €
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-md-0 mb-3 mb-md-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            {{ __('Charity') }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->charity_percentage }} %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3 pe-md-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Description (Ger)
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3 pe-md-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Description (Eng)
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->description_en }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="col">
                                    {{__('Image')}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col">
                                    <img src="/storage/{{ $item->image_file }}" alt="{{ $item->name }}" class="card-img-bottom">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="col">
                                    {{__('Additional Informations')}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        {{ $item->additional_info }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('artshow.index') }}" class="btn btn-primary me-2">Back</a>
                    @if ($user->id == $as_artist->user_id)
                        <a href="{{ route('artshow.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                    @endif
                    </div>
                </div>
            </div>
        </div>

    @endsection
