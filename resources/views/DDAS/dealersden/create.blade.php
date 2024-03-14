@extends('layouts.app')
@section('title', __('Dealer\'s Den'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            Anmeldung Dealer's Den
        </h1>

        <div class="justify-center px-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Anmeldeformular</h3>
                </div>
                <form action="{{ route('dealersden.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row pt-4 pb-2 justify-content-center">
                            <x-form.input ident="DealerName" pht="Kenthart" lt="Dealer Name" />
                        </div>
                        <div class="row py-2 justify-content-center">
                            <x-form.input ident="DealerGalerie" pht="https://t.me/Kenths_Kreative_Corner"
                                lt="Art-Channel/-Group" />
                        </div>
                        <div class="row py-2 justify-content-center">
                            <x-form.select ident="DealerContactType" lt="Contact Way">
                                <option>Telegram</option>
                                <option>Phone</option>
                                <option>E-Mail</option>
                            </x-form.select>
                            <x-form.input ident="DealerContact" lt="Contact" />
                        </div>
                        <div class="row py-2 justify-content-center">
                            <x-form.text ident="DealerSort" lt="Sortiment" pht="Fullsuits, Partials, Art-Works" />
                        </div>
                        <div class="row py-2 justify-content-center">
                            <x-form.image ident="DealerLogo" lt="Logo" size="-5"></x-form.image>
                            <x-form.select ident="DealerSpace" lt="Space">
                                <option>0 Tische</option>
                                <option>1 Tisch</option>
                                <option>2 Tische</option>
                            </x-form.select>
                        </div>
                    </div>
                    <div class="card-footer">

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    </form>
            </div>
        </div>
    </div>
@endsection
