@extends('layouts.app')
@section('title', "Benutzer Einstellungen bearbeiten")
@section('content')
<div class="col-12 col-md-3 mx-auto">
    <div class="card">
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
                                        <input class="form-check-input" type="checkbox" name="notification-mail-{{ $notification }}" value="1" @if (in_array('mail', $value)) checked @endif>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <input class="form-check-input" type="checkbox" name="notification-telegram-{{ $notification }}" value="1" @if (in_array('telegram', $value)) checked @endif @if (!auth()->user()->telegram_user_id) disabled @endif>
                                    </div>
                                    <div class="col-4 col-md-4 d-none">
                                        <input class="form-check-input" type="checkbox" name="notification-database-{{ $notification }}" value="1" checked>
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
