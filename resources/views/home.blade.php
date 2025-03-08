@extends('layouts.app')
@section('title', __("Home"))
@section('content')
<div class="container">
{{--    <h2>{{ __("Before the convention") }}</h2>--}}
    @if($preConMode)
        <div class="row row-cols-1 row-cols-md-3 mx-auto align-items-stretch justify-content-center" style="max-width: 970px">
            <x-home-signup-card :title="__('SIG Sign Up')" img="/images/signup/sigfox.png" :href="route('sigs.create')">
                {{ __("Submit your Events, Workshops, Presentations and more!") }}
            </x-home-signup-card>
            @if(app(\App\Settings\DealerSettings::class)->enabled AND auth()->user()->can("create", \App\Models\Ddas\Dealer::class))
                <x-home-signup-card :title="__('Dealer\'s Den Sign Up')" img="/images/signup/dealerfox.png" :href="route('dealers.create')">
                    {{ __("Would you like to sell your art at the con?") }}
                </x-home-signup-card>
            @endif
            @if(app(\App\Settings\ArtShowSettings::class)->enabled)
                <x-home-signup-card :title="__('Art Show Item Sign Up')" img="/images/signup/artshowfox.png" :href="route('artshow.create')">
                    {{ __("Submit your art for exhibition or auction") }}
                </x-home-signup-card>
            @endif
        </div>
    @else
        <div class="row mt-2">
            <div class="col-12 col-lg-8 order-0">
                <h2 class="py-2"><i class="bi bi-calendar-week icon-link"></i> {{ __("Your Upcoming Events") }}</h2>
                <livewire:sig.upcoming-timeslots />
            </div>
            <div class="col-12 col-lg-4 order-3 order-lg-1">
                @if (!auth()->user()->routeNotificationForTelegram())
                    <div class="container py-3">
                        <h4><i class="bi bi-telegram icon-link"></i> {{ __("Telegram Connection") }}</h4>
                        <p>{{ __("Haven't connected your Telegram Account yet?") }}</p>
                        <a class="btn btn-primary btn-lg mx-auto" href="{{ route("user-settings.edit") }}" role="button">
                            {{ __("Connect it now") }}
                        </a>
                    </div>
                @endif
                <div class="container py-3">
                    <h4><i class="bi bi-bell-fill icon-link"></i> {{ __("Notifications") }}</h4>
                    <p>{{ __("Do you want to modify how you recive notifications?") }}</p>
                    <a class="btn btn-primary btn-lg" href="{{ route("user-settings.edit") }}" role="button">
                        {{ __("Modify them here") }}
                    </a>
                </div>
            </div>
            <div class="col-12 order-2">
                <h2 class="py-2"><i class="bi bi-heart-fill icon-link"></i> {{ __("Favorite Events") }}</h2>
                <livewire:sig.favorite-events />
            </div>
        </div>

    @endif
</div>

@endsection
