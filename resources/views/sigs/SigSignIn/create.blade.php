@extends('layouts.app')
@section('title', __('Sig Anmeldung'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">Sig Anmeldung</h1>

        <form action="/sigsignin" method="POST">
            @csrf
            <div class="card" style="width: 42rem;">
                <div class="card-header">
                    <h4>Persönliche Informationen</h4>
                </div>
                <div class="card-body">
                    @if ($user->reg_id)
                        <div class="row pt-2 pb-3">
                            <x-form.input-read-only ident="UserRegId" lt="User Reg-Nummer" value="{{ $user->reg_id }}" />
                        </div>
                        <div class="row py-2">
                            <x-form.input-read-only ident="SigMail" lt="E-Mail Addresse" value="{{ $user->email }}" />
                        </div>
                        @if ($sighost)
                            <div class="row py-2">
                                <x-form.input-read-only ident="SigHostName" lt="Name / Nickname"
                                    value="{{ $sighost->name }}" />
                            </div>
                            <div class="row py-2">
                                <x-form.input-read-only ident="SigTG" lt="Telegram-@" value="{{ $user->telegram_user_id }}"
                                    value="{{ $sighost->telegram_add }}" />
                            </div>
                        @else
                            <div class="row py-2">
                                <x-form.input-read-only ident="SigHostName" lt="Name / Nickname"
                                    value="{{ $user->name }}" />
                            </div>
                            <div class="row py-2">
                                <x-form.input ident="SigTG" lt="Telegram-@" value="{{ $user->telegram_user_id }}"
                                    size="-3" />
                            </div>
                        @endif
                    @else
                        <div class="row py-2">
                            <x-form.input ident="UserRegID" lt="User Reg-ID" />
                        </div>
                        <div class="row py-2">
                            <x-form.input ident="SigMail" lt="E-Mail Addresse" />
                        </div>
                        <div class="row py-2">
                            <x-form.input ident="SigHostName" lt="Name / Nickname" />
                        </div>
                        <div class="row py-2">
                            <x-form.input ident="SigTG" lt="Telegram-@" />
                        </div>
                    @endif
                </div>
            </div>
            <h2 class="py-2 text-center">Programmplanung</h2>
            <p class="py-1">Hier kannst du deine eigene SIG (Special Interest Group) anmelden, welche dann in unser
                Programm
                aufgenommen wird.</p>
            <p class="py-1">Dabei kann es sich um einen Workshop, Präsentation oder Erfahrungsaustausch in sämtlichen
                Bereichen handeln.</p>
            <div class="row py-2">
                <x-form.input ident="SigName" lt="SIG Name" size="-4" />
            </div>
            <div class="row py-2">
                <x-form.text ident="SigDescriptionDE" lt="SIG Beschreibung fürs Conbook (GER)" size="-6" />
            </div>
            <div class="row py-2">
                <x-form.text ident="SigDescriptionEN" lt="SIG Beschreibung fürs Conbook (ENG)" size="-6" />
            </div>
            <div class="row pt-3 pb-4">
                <p class="h5">Sprache</p>
                <div class="col">
                    <x-form.select ident="SigHostLang" lt="Sig Host Sprache">
                        <option value="de">Deutsch</option>
                        <option value="en">English</option>
                    </x-form.select>
                </div>
                <div class="col">
                    <x-form.select ident="SigAttendeeLang" lt="Sig Teilnehmer Sprache">
                        <option value="0">Deutsch</option>
                        <option value="1">English</option>
                        <option value="2">Deutsch & English</option>
                    </x-form.select>
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
                <x-form.text ident="SigAddInfo" />
            </div>

            <button type="submit" class="justify-center btn btn-primary">
                Submit
            </button>
        </form>
    </div>
@endsection