@extends('layouts.app')
@section('title', __('SIG Sign In'))

@section('content')

    <div class="container">
        <h2>{{ __('SIG Sign Up') }}</h2>
        <form action="{{ route("signup.store") }}" method="POST">
            @csrf
            <div class="card mt-3">
                <div class="card-header">
                    {{ __("General Information") }}
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __("SIG Name") }}">
                                <label for="name">{{ __("SIG Name") }}</label>
                            </div>

                        </div>
                        <div class="col-md-2 col-12">
                            <div class="form-floating">
                                <select name="duration" id="duration" class="form-select">
                                    @foreach(range(30, 360, 30) AS $mins)
                                        <option value="{{ $mins }}" {{ old('duration') == $mins ? "selected":"" }}>{{ $mins/60 }} h</option>
                                    @endforeach
                                </select>
                                <label for="duration">{{ __("Duration") }} *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <span class="col-form-label">{{ __("Language") }}</span>
                            <fieldset class="d-flex gap-2 flex-wrap">
                                <div class="btn-group" role="group">
                                    <input type="checkbox" class="btn-check" id="lang1" autocomplete="off" name="lang_de" {{ old('lang_de') ? "checked":"" }}>
                                    <label class="btn btn-outline-success" for="lang1">{{ __("German") }}</label>

                                    <input type="checkbox" class="btn-check" id="lang2" autocomplete="off" name="lang_en" {{ old('lang_en') ? "checked":"" }}>
                                    <label class="btn btn-outline-success" for="lang2">{{ __("English") }}</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">{{ __("Description for the Schedule/Conbook") }}:</label>
                            <textarea class="form-control" rows="7" id="description" name="description"> {{ old('description') }}</textarea>
                            <p class="text-warning">{{ __("This text will be visible for everyone and will be printed in hundreds of conbooks!") }}</p>
                        </div>
                    </div>

                </div>
                <div class="card-header">
                    {{ __("Organizational Information") }}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <p class="mb-1">{{ __("What tools do you need?") }}<span class="text-secondary d-block">{{ __("Projector, laptop, speakers, ...?") }}</span></p>
                            <p class="mb-1">{{ __("When should your event take place?") }}<span
                                    class="text-secondary d-block">{{ __("Wednesday evening, Saturday night, ...?") }}</span></p>
                            <p class="mb-1">{{ __("Is this event involving fursuit interaction?") }}<span
                                    class="text-secondary d-block">{{ __("Do you need cooling fans, water, ...?") }}</span></p>
                            <p class="mb-1">{{ __("How much space do you need?") }}<span class="text-secondary d-block">{{ __("Small Room, Big Room, Stage, ...?") }}</span></p>
                            <p class="mb-1">{{ __("Is signup in advance necessary or desired?") }}</p>
                            <p style="font-size: 0.8em"
                               class="text-secondary">{{ __("We plan 30 minutes preparation time for you. The room will be at your disposal beforehand. If you need more time, please let us know!") }}</p>
                        </div>
                        <div class="col-md-8 col-12">
                            <textarea class="form-control" style="height: 100%; min-height: 300px" placeholder="{{ __("I Need...") }}" name="additional_infos">{{ old('additional_infos') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container text-center p-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">{{ __("Submit") }}</button>
            </div>
        </form>
    </div>

@endsection
