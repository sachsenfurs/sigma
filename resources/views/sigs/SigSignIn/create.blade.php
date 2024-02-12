@extends('layouts.app')
@section('title', __('Sig Anmeldung'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">Sig Anmeldung</h1>

        <form action="/sigsignin" method="POST">
            @csrf
            <div class="row p-2">
                {{ $user->name }}
            </div>
            @if ($user->reg_id)
                <div class="row pt-2 pb-3">
                    <x-form.input-read-only ident="UserRegId" lt="User Reg-ID" value="{{ $user->reg_id }}"/>
                </div>
                <div class="row py-2">
                    <x-form.input-full ident="SigMail" lt="E-Mail Addresse" value="{{ $user->email }}" />
                </div>
                @if($sighost)
                <div class="row py-2">
                    <x-form.input-full ident="SigHostName" lt="Name / Nickname" value="{{ $sighost->name }}" />
                </div>
                @else
                <div class="row py-2">
                    <p>Please Add a Sig-Host</p>
                </div>
                @endif
                <div class="row py-2">
                    <x-form.input-full ident="SigTG" lt="Telegram-@" value="{{ $user->telegram_user_id }}" />
                </div>
            @else
                <div class="row py-2">
                    <x-form.input-xs ident="UserRegID" lt="User Reg-ID"/>
                </div>
                <div class="row py-2">
                    <x-form.input-full ident="SigMail" lt="E-Mail Addresse" />
                </div>
                <div class="row py-2">
                    <x-form.input-full ident="SigHostName" lt="Name / Nickname" />
                </div>
                <div class="row py-2">
                    <x-form.input-full ident="SigTG" lt="Telegram-@" />
                </div>
            @endif
            <hr class="py-4">
            <h2 class="py-2 text-center">Programmplanung</h2>
            <p class="py-1">Hier kannst du deine eigene SIG (Special Interest Group) anmelden, welche dann in unser
                Programm
                aufgenommen wird.</p>
            <p class="py-1">Dabei kann es sich um einen Workshop, Präsentation oder Erfahrungsaustausch in sämtlichen
                Bereichen handeln.</p>
            <div class="row py-2">
                <x-form.input-full ident="SigName" lt="SIG Name" />
            </div>
            <div class="row py-2">
                <x-form.text-l ident="SigDescriptionDE" lt="SIG Beschreibung fürs Conbook (GER)" />
            </div>
            <div class="row py-2">
                <x-form.text-l ident="SigDescription" lt="SIG Beschreibung fürs Conbook (ENG)" />
            </div>
            <div class="row pt-3 pb-4">
                <p class="h5">Sprache</p>
                <p class="fst-italic text-body-secondary">Mehrfachauswahl möglich</p>
                <div class="col">
                    <x-form.checkbox ident="SigLanguageDE" lt="Deutsch" />
                </div>
                <div class="col">
                    <x-form.checkbox ident="SigLanguageEN" lt="English" />
                </div>
            </div>
            <hr class="py-4">
            <h3>Ich benötige...</h3>
            <p>Was benötigst du von uns?</p>
            <p>Schreibe uns gerne unten mehr dazu!</p>
            <div class="row py-2">
                <div class="col">
                    <x-form.checkbox ident="SigNeedsFurSuport" lt="Fursuit Support (Wasser, Lüfter, ...)" />
                    <x-form.checkbox ident="SigNeedsSecu" lt="Security" />
                </div>
                <div class="col">
                    <x-form.checkbox ident="SigNeedsMedic" lt="Medic" />
                    <x-form.checkbox ident="SigNeedsOther" lt="Sonstiges (Bitte unten ausgeben!)" />
                </div>
            </div>
            <h3>Zusätzliche Informationen</h3>
            <div class="row py-2">
                <x-form.text-l ident="SigAddInfo" />
            </div>

            <button type="submit" class="justify-center">
                Submit
            </button>
        </form>
    </div>
@endsection
