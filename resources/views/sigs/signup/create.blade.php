@extends('layouts.app')
@section('title', __('SIG Sign In'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">{{ __('SIG Sign In') }}</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="/sigs/signup" method="POST">
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
                                                <input type="text" name="UserRegID" value="{{ old('UserRegID')}}" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                {{ __('Name / Nickname') }}
                                            </div>
                                            <div class="col">
                                                <input type="text" name="SigHostName" value="{{ old('SigHostName')}}" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                {{ __('E-Mail Addresse') }}
                                            </div>
                                            <div class="col">
                                                <input type="text" name="SigMail" value="{{ old('SigMail')}}" class="form-control" />
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
                                            <input type="text" name="SigName" value="{{ old('SigName')}}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Languages SIG') }}
                                        </div>
                                        <div class="col">
                                            <select name="SigLang" class="form-control">
                                                <option selected value="0">{{ __('German') }}</option>
                                                <option value="1">{{ __('English') }}</option>
                                                <option value="2">{{ __('German & English') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col">
                                            <x-form.text ident="SigDescriptionDE" value="{{ old('SigDescriptionDE')}}" lt="{{ __('Description') }}" />
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
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Fursuit Support (Water, Fans, ...)') }}
                                        </div>
                                        <div class="col-4">
                                            <select name="SigNeedsFurrySupport" class="form-control">
                                                <option selected value="0">{{ __('No') }}</option>
                                                <option value="1">{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Medic') }}
                                        </div>
                                        <div class="col-4">
                                            <select name="SigNeedsMedic" class="form-control">
                                                <option selected value="0">{{ __('No') }}</option>
                                                <option value="1">{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Security') }}
                                        </div>
                                        <div class="col-4">
                                            <select name="SigNeedsSecu" class="form-control">
                                                <option selected value="0">{{ __('No') }}</option>
                                                <option value="1">{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ __('Other Stuff') }}
                                        </div>
                                        <div class="col-4">
                                            <select name="SigNeedsOther" class="form-control">
                                                <option selected value="0">{{ __('No') }}</option>
                                                <option value="1">{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-form.text ident="additional_infos"
                                                lt="{{ __('Additional Informations') }}" value="{{ old('additional_infos')}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="/sigs/signup" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
