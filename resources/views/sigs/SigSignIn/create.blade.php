@extends('layouts.app')
@section('title', __('SIG Sign In'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">{{ __('SIG Sign In') }}</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="/sigsignin" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">{{ __('Personal Informations') }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($user->reg_id)
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                {{ __('User Reg-ID') }}:
                                            </div>
                                            <div class="col-6">
                                                {{ $user->reg_id }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                {{ __('Name / Nickname') }}:
                                            </div>
                                            <div class="col-6">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                {{ __('E-Mail Addresse') }}:
                                            </div>
                                            <div class="col-6">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="row mb-3">
                                            <div class="col">
                                                {{ __('User Reg-ID') }}
                                            </div>
                                            <div class="col">
                                                <input type="text" name="UserRegID" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                {{ __('Name / Nickname') }}
                                            </div>
                                            <div class="col">
                                                <input type="text" name="SigHostName" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                {{ __('E-Mail Addresse') }}
                                            </div>
                                            <div class="col">
                                                <input type="text" name="SigMail" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="text-center">{{ __('Program planning') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <p class="text-center">
                                    {{ __('Here you can register your own SIG (Special Interest Group), which will then be included in our program.') }}
                                </p>
                                <p class="text-center">
                                    {{ __('This can be a workshop, presentation or exchange of experiences in any area.') }}
                                </p>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('SIG Name') }}
                                        </div>
                                        <div class="col">
                                            <input type="text" name="SigName" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Language Host') }}
                                        </div>
                                        <div class="col">
                                            <select name="SigHostLang" class="form-control">
                                                <option value="de">{{ __('German') }}</option>
                                                <option value="en">{{ __('English') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Languages SIG') }}
                                        </div>
                                        <div class="col">
                                            <select name="SigLang" class="form-control">
                                                <option value="de">{{ __('German') }}</option>
                                                <option value="en">{{ __('English') }}</option>
                                                <option value="de-en">{{ __('German & English') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col">
                                            <x-form.text ident="SigDescriptionDE" lt="{{ __('Description') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="text-center">{{ __('Additional Informations') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-center">{{ __('What do you need from us?') }}</h4>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Please select the things you need from us.') }} <br>
                                            {{ __('If you need something else, please specify it below.') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-center">{{ __('I Need...') }}</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-form.checkbox ident="SigNeedsFurrySupport"
                                                lt="{{ __('Fursuit Support (Water, Fans, ...)') }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-form.checkbox ident="SigNeedsSecu" lt="Security" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-form.checkbox ident="SigNeedsMedic" lt="Medic" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-form.checkbox ident="SigNeedsOther"
                                                lt="{{ __('Other (Please enter below!)') }}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-form.text ident="additional_infos" lt="{{ __('Additional Informations') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
        </form>
    </div>
    </div>
    </div>
@endsection
