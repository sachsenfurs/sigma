@extends('layouts.app')
@section('title', __("New Artshow Item"))

@section('content')    <div class="container">
    <h1 class="pt-2 pb-5 text-center">
        Anmeldung Artshow
    </h1>
    <form method="POST">
        @csrf
    <div class="row pt-4 pb-2 justify-content-center">
        <x-form.input-half ident="ArtistName" pht="KenthArt" lt="Artist Name"/>
        <x-form.input-half ident="ArtistItem" pht="Handpaws" lt="Item Name" />
    </div>
    <div class="row py-2 justify-content-center">
        <x-form.text-l ident="ArtistItemDescription" lt="Description" />
    </div>
    <div class="row py-2 justify-content-center">
        <x-form.input-half ident="ArtistItemStartbid" lt="Start Bid"/>
        <x-form.input-half ident="ArtistItemCharity" lt="Charity Ammount" />
    </div>
</form>

</div>
@endsection