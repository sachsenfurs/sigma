@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        <h2 class="pt-2 pb-5 text-center">
            {{ __('Artshow Item List') }}
        </h2>

        <div class="d-flex justify-content-center p-3">
            <a href="{{ route('artshow.create') }}" class="btn btn-primary">{{ __('Add Item') }}</a>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">
                    {{ __('Item List') }}
                </h3>
            </div>
            <div class="card-body">
                @foreach ($as_artists as $artist)
                    @foreach ($as_items as $item)
                        @if ($item->artshow_artist_id == $artist->id)
                            @if ($item->approved == 1)
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 mb-3 pe-md-0">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="col">
                                                            {{ __('Artist') }}
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col">
                                                            {{ $artist->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="col">
                                                            {{ __('Item Name') }}
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col ">
                                                            <a class="card-link"
                                                                href="{{ route('artshow.show', $item->id) }}">
                                                                {{ $item->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3 pe-md-0">
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
                                            <div class="col-md-6 mb-3">
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
                                    </div>
                                    <div class="col-md-5 mb-3 px-md-0">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('Description') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    {{ $item->description }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('Image') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    <img src="/storage/{{ $item->image_file }}" alt="{{ $item->name }}"
                                                        class="card-img-bottom">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    @endsection
