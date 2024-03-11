@extends('layouts.app')
@section('title', __('New Artshow Item'))

@section('content') <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            {{ __('Artshow Item Sing In') }}
        </h1>
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">
                    {{ __('New Item') }}
                </h3>
            </div>
            <div class="card-body">
                <form action="/artshow" method="POST">
                    @csrf
                    <div class="row pt-4 pb-2 justify-content-center">
                        <x-form.input ident="ArtistName" pht="{{ $user->name }}" lt="Artist Name" />
                        <x-form.input ident="ArtistItem" pht="Handpaws" lt="Item Name" />
                    </div>
                    <div class="row py-2 justify-content-center">
                        <x-form.text ident="ArtistItemDescriptionDE" lt="Beschreibung" />
                    </div>
                    <div class="row py-2 justify-content-center">
                        <x-form.text ident="ArtistItemDescriptionEN" lt="Description" />
                    </div>
                    <div class="row py-2 justify-content-center">
                        <x-form.input ident="ArtistItemStartbid" lt="Start Bid" />
                        <x-form.input ident="ArtistItemCharity" lt="Charity Ammount" />
                    </div>
                    <div class="row py-2 justify-content-center">
                        <x-form.upload ident="ArtistItemImage" lt="Image" />
                    </div>
                    <button type="submit" class="justify-center btn btn-primary">{{ __('Add Entry') }}</button>
                </form>
            </div>

        </div>
    @endsection
