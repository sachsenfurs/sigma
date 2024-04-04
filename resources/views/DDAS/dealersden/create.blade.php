@extends('layouts.app')
@section('title', __('Dealer\'s Den'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            {{ __('Dealers Den Sign Up') }}
        </h1>
        <div class="row justify-content-center px-md-5">
            <div class="col-md-7">
                <form action="{{ route('dealersden.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">{{ __('Dealer Infos') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col">
                                            {{ __('Dealer') }}
                                        </div>
                                        <div class="col mb-3">
                                            <input name="DealerName" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            {{ __('Art-Channle/-Group') }}
                                        </div>
                                        <div class="col mb-3">
                                            <input name="DealerGalerie" value="{{ old('DealerGalerie')}}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            {{ __('Contact Ways') }}
                                        </div>
                                        <div class="col mb-3">
                                            <select name="DealerContactType" class="form-control">
                                                <option value="telegram" selected>{{ __('Telegram')}}</option>
                                                <option value="phone">{{ __('Phone')}}</option>
                                                <option value="email">{{ __('Email')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            {{ __('Contact') }}
                                        </div>
                                        <div class="col mb-3">
                                            <input name="DealerContact" value="{{ old('DealerContact')}}" class="form-control" />
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col">
                                            {{ __('Dealer Space') }}
                                        </div>
                                        <div class="col mb-3">
                                            <select name="DealerSpace" class="form-control">
                                                <option value="0">0 Tische</option>
                                                <option value="1" selected>1 Tisch</option>
                                                <option value="2">2 Tische</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col mb-3">
                                            <x-form.image ident="DealerLogo" value="{{ old('DealerLogo')}}" lt="{{ __('Dealer Logo')}}"></x-form.image>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                        <x-form.text ident="DealerSort" value="{{ old('DealerSort')}}" lt="{{ __('Sortiment') }}" />
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
