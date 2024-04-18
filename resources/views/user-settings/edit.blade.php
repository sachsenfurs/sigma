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
                    @foreach ($notificationType as $notification => $value)
                        <div class="row m-2">
                            <div class="col-12 col-md-7 text-center text-md-start pb-md-0 pb-3 my-auto">
                                {{ __($notification) }}
                            </div>
                            <div class="col-12 col-md-5">
                                <select class="form-select" id="notification_{{ $notification }}" name="notification_{{ $notification }}">
                                    <option value="telegram" @if ($value == 'telegram') selected @endif>Telegram</option>
                                    <option value="email" @if ($value == 'email') selected @endif>{{ __("Email") }}</option>
                                </select>
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
