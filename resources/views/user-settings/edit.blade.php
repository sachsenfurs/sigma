@extends('layouts.app')
@section('title', __("Edit User Settings"))
@section('content')
<div class="col-12 col-md-8 mx-auto">
    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-auto align-content-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="rounded-circle img-thumbnail" style="max-height: 7rem" alt="">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 5rem"></i>
                    @endif
                </div>
                <div class="col align-content-center">
                    <h3>{{ auth()->user()->name }}</h3>
                    <p class="text-muted">
                        {{ __("Reg Number").": ".auth()->user()->reg_id }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="card-title">
                <h4>{{ __("Notifications") }}</h4>
            </div>
            <div class="row p-3">
                @if (!auth()->user()->routeNotificationForTelegram())
                    <div class="col mx-auto text-center">
                        <p>{{ __("Connect your account with telegram to enable notifications") }}</p>
                        <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="{{ app(\App\Settings\AppSettings::class)->telegram_bot_name }}" data-size="large" data-auth-url="{{ route("telegram.connect") }}" data-request-access="write"></script>
                    </div>
                @else
                    <div class="col-auto"><i class="bi bi-check-circle-fill text-success fs-1"></i></div>
                    <div class="col align-content-center">{{ __("You have successfully connected your Telegram account") }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <form action="{{ route('user-settings.update') }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-header text-center">
                <h5 class="card-title m-2">{{ __("Personal settings") }}</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="row m-2">
                        <div class="col-7 text-center text-md-start pb-md-0 pb-3 my-auto">

                        </div>
                        <div class="col-5 col-md-5">
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    <i class="bi bi-envelope fs-5" title="{{ __("Email Notifications") }}"></i>
                                </div>
                                <div class="col-6 col-md-6">
                                    <i class="bi bi-telegram fs-5" title="{{ __("Telegram Notifications") }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($notificationType as $notification => $data)
                        <div class="row m-2">
                            <div class="col-7 text-center text-md-start pb-md-0 pb-3 my-auto">
                                {{ $data['name'] }}
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input fs-5" type="checkbox" name="{{ $notification }}[]" value="mail" @if (in_array('mail', $data['channels'])) checked @endif>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input fs-5" type="checkbox" name="{{ $notification }}[]" value="telegram" @if (in_array('telegram', $data['channels'])) checked @endif @if (!auth()->user()->routeNotificationForTelegram()) disabled @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary m-1">{{ __("Save") }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
