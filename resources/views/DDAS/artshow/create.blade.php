@extends('layouts.app')
@section('title', __('New Artshow Item'))

@section('content') 
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            {{ __('Artshow Item Sign Up') }}
        </h1>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <form action="{{ route('artshow.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="text-center">
                                {{ __('Personal Information') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($artist != null)
                                <div class="row">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col mb-3">
                                                {{ __('Name / Nickname') }}:
                                            </div>
                                            <div class="col">
                                                {{ $artist->name }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                {{ __('Social Media') }}:
                                            </div>
                                            <div class="col">
                                                {{ $artist->social }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col">
                                        <x-form.input ident="ArtistName" pht="{{ $user->name }}" lt="Artist Name" />
                                    </div>
                                    <div class="col">
                                        <x-form.input ident="ArtistWeb" pht="{{ $user->social }}" lt="Social Media" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="text-center">
                                {{ __('New Item') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row pb-2">
                                <div class="col">
                                    <x-form.input ident="ArtistItemName" pht="Handpaws" lt="Item Name" />
                                </div>
                                <div class="col">
                                    <x-form.image ident="ArtistItemImage" lt="Image" />
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col">
                                    <x-form.input ident="ArtistItemStartBid" lt="Start Bid" />
                                </div>
                                <div class="col">
                                    <x-form.input ident="ArtistItemCharity" lt="Charity Ammount" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-form.text ident="ArtistItemDescriptionDE" lt="Beschreibung" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-form.text ident="ArtistItemAdditionalInfo" lt="Description" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="row">
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                            <div class="col-md-6">
                                <a href="/sigsignin" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
