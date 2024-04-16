@extends('layouts.app')
@section('title', "Benutzer Einstellungen bearbeiten")
@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('user-settings.update') }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-header">
                <h5 class="card-title m-2">{{ __("Personal settings") }}</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    @foreach ($notificationType as $notification => $value)
                        <div class="row m-1">
                            <div class="col-6">
                                {{ __($notification) }}
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="notification_{{ $notification }}" name="notification_{{ $notification }}">
                                    <option value="telegram" @if ($value == 'telegram') selected @endif>Telegram</option>
                                    <option value="email" @if ($value == 'email') selected @endif>{{ __("Email") }}</option>
                                </select>
                            </div>
                        </div>
                    @endforeach                    
                </div>        
            </div>
            <div class="card-footer">
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary m-1">{{ __("Save") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
