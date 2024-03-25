@extends('layouts.app')
@section('title', __('Edit Artshow Item'))

@section('content')

    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            Edit Artshow Item
        </h1>
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">
                    Edit Item
                </h3>
            </div>
            <form action="{{ route('artshow.show', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-6 mb-3 pe-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                Artist
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <input type="text" name="artist" value="{{ $artist->name }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                Item
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <input type="text" name="name" value="{{ $item->name }}"
                                                    class="form-control">
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
                                                Start Bid
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <input type="text" name="starting_bid" value="{{ $item->starting_bid }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                Charity
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <input type="text" name="charity_percentage"
                                                    value="{{ $item->charity_percentage }}" class="form-control">
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
                                                <textarea type="text" name="description" rows="4" class="form-control">{{ $item->description }}</textarea>
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
                                                <textarea rows="4" type="text" name="description" class="form-control">{{ $item->description_en }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-3">
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                Image
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <img src="{{ $item->image_file }}" alt="{{ $item->name }}"
                                                        class="card-img-bottom" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="file" name="image" class="form-control">
                                                </div>
                                            </div>
                                        </div>
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
                                        Additional Information
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <textarea rows="4" type="text" name="description" class="form-control">{{ $item->additional_info }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('artshow.index') }}" class="btn btn-primary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
