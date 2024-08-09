@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        <div class="card my-3 bg-info-subtle">
            @can("create", \App\Models\Ddas\ArtshowItem::class)
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            {{ __("Would you like to register something for the art show?") }}
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route("artshow.create") }}">
                                <button class="btn btn-primary">
                                    {{ __("Click here to register") }}
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        @if(app(\App\Settings\ArtShowSettings::class)->show_items_date->isAfter(now()))
            <x-infocard>
                {{ __("Art Show Items are not published yet") }}
            </x-infocard>
        @else
            @if($error)
                <x-infocard>
                    {{ $error }}
                </x-infocard>
            @else
                <livewire:ddas.artshow-items />
            @endif
        @endif
    </div>
@endsection
