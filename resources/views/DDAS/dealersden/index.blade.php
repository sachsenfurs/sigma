@extends('layouts.app')
@section('title', __('Dealer\'s Den'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            Anmeldung Dealer's Den
        </h1>

        <div class="row pt-4 pb-2 justify-content-center">
            <x-form.input-half ident="DealerName" pht="Kenthart" lt="Dealer Name" />
            <x-form.select ident="DealerSpace" lt="Space">
                <option>1 Tisch</option>
                <option>2 Tische</option>
            </x-form.select>
        </div>
        <div class="row py-2 justify-content-center">
            <x-form.input-full ident="DealerGalerie" pht="https://t.me/Kenths_Kreative_Corner"
                lt="Art-Channel/-Group"></x-form.input>
        </div>
        <div class="row py-2 justify-content-center">
            <x-form.select ident="DealerContactType" lt="Contact Way">
                <option>Telegram</option>
                <option>Phone</option>
                <option>E-Mail</option>
            </x-form.select>
            <x-form.input-half ident="DealerContact" lt="Contact" />
        </div>
        <div class="row py-2 justify-content-center">
            <x-form.text ident="DealerSort" lt="Sortiment" pht="Fullsuits, Partials, Art-Works" />
        </div>
        <div class="row py-2 justify-content-center">
            <x-form.image ident="DealerLogo" lt="Logo"></x-form.image>
            <x-form.select ident="DealerWishes" lt="Wishes">
                <option>Powersupply</option>
                <option>no Table</option>
            </x-form.select>
        </div>
    </div>
@endsection
