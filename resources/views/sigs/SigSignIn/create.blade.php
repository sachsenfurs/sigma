@extends('layouts.app')
@section('title', __('SIG Sign In'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">{{ __('SIG Sign In') }}</h1>

        <div class="card">
            <form action="/sigsignin" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="text-center">{{ __('SIG Sign In') }}</h3>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">{{ __('Personal Informations') }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($user->reg_id)
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('User Reg-ID') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    {{ $user->reg_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3 pe-md-0">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('Name / Nickname') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($user->telegram_user_id)
                                        <div class="col-md-4 mb-3 pe-md-0">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col">
                                                        {{ __('Telegram-@') }}
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col">
                                                        {{ $sighost->telegram_add }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-4 mb-3 pe-md-0">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col">
                                                        {{ __('Telegram-@') }}
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col">
                                                        <input type="text" name="SigTG" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('E-Mail Addresse') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('User Reg-ID') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    <input type="text" name="UserRegID" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3 pe-md-0">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('Name / Nickname') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    <input type="text" name="SigHostName" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3 pe-md-0">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('Telegram-@') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    <input type="text" name="SigTG" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="col">
                                                    {{ __('E-Mail Addresse') }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col">
                                                    <input type="text" name="SigMail" class="form-control" />
                                                </div>
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
                            <div class="row">
                                <div class="col-md-4 mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('SIG Name') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <input type="text" name="SigName" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Language Host') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <select name="SigHostLang" class="form-control">
                                                    <option value="de">{{ __('German') }}</option>
                                                    <option value="en">{{ __('English') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Languages SIG') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
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
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('SIG Description for the Conbook (GER)') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <textarea type="text" name="SigDescriptionDE" rows="4" class="form-control"
                                                    placeholder="{{ __('SIG Description for the Conbook (GER)') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('SIG Description for the Conbook (ENG)') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <textarea type="text" name="SigDescriptionEN" rows="4" class="form-control"
                                                    placeholder="{{ __('SIG Description for the Conbook (ENG)') }}"></textarea>
                                            </div>
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
                            <div class="row">
                                <div class="col-md-5 mb-3 pe-md-0">
                                    <div class="card">
                                        <div class="card-header">
                                            {{ __('What do you need from us?') }}
                                        </div>
                                        <div class="card-body">
                                            {{ __('Please select the things you need from us.') }} <br>
                                            {{ __('If you need something else, please specify it below.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('I Need...') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <x-form.checkbox ident="SigNeedsFurSuport"
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col">
                                                {{ __('Additional Informations') }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col">
                                                <textarea type="text" name="SigAddInfo" rows="4" class="form-control"
                                                    placeholder="{{ __('Additional Informations') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
