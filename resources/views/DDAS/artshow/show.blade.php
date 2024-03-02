@extends('layouts.app')
@section('title', __('Show Artshow Item'))

@section('content')

    <div class="container">
        <h1 class="pt-2 pb-5 text-center">Show Artshow Item</h1>

        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Artshow Item</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="row">
                            <div class="col mb-3 pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Artist
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $artist->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Item
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
                                            Bestätigung
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->approved }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Verkauft
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->sold }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Start Bid
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->starting_bid }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pe-0">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col">
                                            Charety
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col">
                                            {{ $item->charity_percentage }}%
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
                            <div class="col mb-3 pe-0">
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
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('artshow.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="col">
                                    Image
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col">
                                    <img src="{{ $item->image_file }}" alt="{{ $item->name }}" class="card-img-bottom">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
