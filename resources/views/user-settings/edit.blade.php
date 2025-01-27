@extends('layouts.app')
@section('title', __("Edit User Settings"))
@section('content')
<div class="col-12 col-md-8 mx-auto">
    @if (!auth()->user()->telegram_user_id)
        <div class="card m-3">
            <div class="row m-3">
                <div class="col-12 col-md-12 text-center">
                    <h2>{{ __("Notifications") }}</h2>
                    <p>{{ __("Connect your account with telegram to enable notifications") }}</p>
                </div>
                <div class="col mx-auto text-center">
                    <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="{{ app(\App\Settings\AppSettings::class)->telegram_bot_name }}" data-size="large" data-auth-url="{{ route("telegram.connect") }}" data-request-access="write"></script>
                </div>
            </div>
        </div>
    @else
        <div class="card m-3">
            <div class="row m-3">
                <div class="col-12 col-md-12 text-center">
                    <h2>{{ __("Notifications") }}</h2>
                    <div class="row p-3">
                        <div class="col-2"><i class="bi bi-check-circle-fill text-success" style="font-size: 42px"></i></div>
                        <div class="col-10"><p class="p-0">{{ __("You have successfully connected your Telegram account") }}</p></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card m-3">
        <form action="{{ route('user-settings.update') }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-header text-center">
                <h5 class="card-title m-2">{{ __("Personal settings") }}</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="row m-2">
                        <div class="col-7 col-md-7 text-center text-md-start pb-md-0 pb-3 my-auto">

                        </div>
                        <div class="col-5 col-md-5">
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    <i class="bi bi-envelope" title="{{ __("Email Notifications") }}"></i>
                                </div>
                                <div class="col-6 col-md-6">
                                    <i class="bi bi-telegram" title="{{ __("Telegram Notifications") }}"></i>
                                </div>
                                <div class="col-4 col-md-4 d-none">
                                    <i class="bi bi-database" title="{{ __("Database Notifications") }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($notificationType as $notification => $value)
                        <div class="row m-2">
                            <div class="col-7 col-md-7 text-center text-md-start pb-md-0 pb-3 my-auto">
                                {{ __($notification) }}
                            </div>
                            <div class="col-5 col-md-5">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input" type="checkbox" name="{{ $notification }}[]" value="mail" @if (in_array('mail', $value)) checked @endif>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input" type="checkbox" name="{{ $notification }}[]" value="telegram" @if (in_array('telegram', $value)) checked @endif @if (!auth()->user()->telegram_user_id) disabled @endif>
                                    </div>
                                </div>
                                <!--
                                    <select class="form-select" id="notification_{{ $notification }}" name="notification_{{ $notification }}">
                                        <option value="telegram" @if ($value == 'telegram') selected @endif>Telegram</option>
                                        <option value="mail" @if ($value == 'mail') selected @endif>{{ __("Email") }}</option>
                                    </select>
                                -->
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
