@extends('layouts.app')
@section('title', __('Create New Host'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">Sig Host</h1>
        <div class="d-flex justify-content-center">
            <div class="card">
            <form action="/sigsignin" method="post">
                @csrf
                <div class="row p-3">
                    <x-form.input-read-only ident="UserRegId" lt="User Reg-ID" value="{{ $user->reg_id }}" />
                    <x-form.input-read-only ident="SigHostName" lt="Name / Nickname" value="{{ $user->name }}" />
                    <div class="py-2">
                        <x-form.checkbox ident="HostHide" lt="Hide" />
                    </div>
                </div>
                <div class="row px-3 pt-1 pb-3">
                    <x-form.text ident="HostDescription" lt="Beschreibung" />
                </div>
                <div class="p-3">
                    <button type="submit" class="btn btn-success">
                        Submit
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
@endsection
