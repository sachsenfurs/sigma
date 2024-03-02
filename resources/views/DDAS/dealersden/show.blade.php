@extends('layouts.app')
@section('title', __('Dealer\'s Den'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            Dealer Details
        </h1>
        <div class="d-flex justify-content-center p-3">
            <a href="{{ route('dealersden.index') }}" class="btn btn-primary">Back to Dealer List</a>
        </div>
        <div class="justify-center px-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">{{ $dealer->name }} Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <img src="{{ $dealer->icon_file }}" alt="{{ $dealer->name }}" width="120">
                        </div>
                        <div class="col-md-6 py-2">
                            <a href="{{ $dealer->gallery_link }}">
                                {{ $dealer->name }} Social-Media
                            </a>
                        </div>
                        <div class="col-md-12 py-2">
                            <p>{{ $dealer->info }}</p>
                        </div>
                        <hr>
                        <div class="col-md-12 py-2">
                            <p>{{ $dealer->info_en }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
