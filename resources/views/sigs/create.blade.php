@extends('layouts.app', ['noerror' => true])
@section('title', __('SIG Sign Up'))

@section('content')
    <div class="container">

        <h2 class="py-2">{{ __('SIG Sign Up') }}</h2>

        @if(auth()->user()->sigHosts->count() == 0)
            <livewire:sig.create />
        @else
            <form action="{{ route("sigs.store") }}" method="POST">
                @csrf
                <div class="card mt-3">
                    <div class="card-header">
                        {{ __("General Information") }}
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-12">
                                <label for="sigHost" class="form-label">{{ __("Anmelden als") }}</label>
                                <select id="sigHost" class="form-select" name="sig_host_id">
                                    @foreach(auth()->user()->sigHosts AS $host)
                                        <option value="{{$host->id}}" @selected(old("sig_host_id"))>{{$host->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-12">
                                <x-form.input-floating name="name" :placeholder="__('SIG Name')" autofocus />
                            </div>
                            <div class="col-md-2 col-12">
                                <x-form.input-floating type="select" name="duration" :placeholder="__('Duration')">
                                    @foreach(range(30, 360, 30) AS $mins)
                                        <option value="{{ $mins }}" {{ old('duration') == $mins ? "selected":"" }}>{{ $mins/60 }} h</option>
                                    @endforeach
                                </x-form.input-floating>
                            </div>
                            <div class="col-md-4">
                                <span class="col-form-label">{{ __("Language") }}</span>
                                <fieldset class="d-flex gap-2 flex-wrap">
                                    <div class="btn-group" role="group">
                                        <input type="checkbox" class="btn-check" id="lang1" autocomplete="off" name="languages[]" value="de" @checked(in_array("de", old('languages', [])))>
                                        <label class="btn btn-outline-success" for="lang1">{{ __("German") }}</label>

                                        <input type="checkbox" class="btn-check" id="lang2" autocomplete="off" name="languages[]" value="en" @checked(in_array("en", old('languages', [])))>
                                        <label class="btn btn-outline-success" for="lang2">{{ __("English") }}</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">{{ __("Description for the Schedule/Conbook") }}:</label>
                                <x-form.input-error name="description"/>
                                <textarea @class(['form-control', 'border-danger' => $errors->has("description")]) rows="7" id="description" name="description"> {{ old('description') }}</textarea>
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
                                <textarea class="form-control" style="height: 100%; min-height: 300px" placeholder="{{ __("I Need...") }}" name="additional_info">{{ old('additional_info') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container text-center p-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">{{ __("Submit") }}</button>
                </div>
            </form>
        @endif
    </div>

@endsection
