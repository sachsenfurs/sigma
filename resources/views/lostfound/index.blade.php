@extends('layouts.app')
@section('title', "Lost & Found")
@section('content')
    <div class="container">
        <div class="alert alert-info row m-1 mb-3 d-flex align-items-center">
            <div class="col-auto ">
                <i class="bi bi-exclamation-lg fs-4"></i>
            </div>
            <div class="col">

                {{ __("If you lost or found something, please reach out to Con Ops (Leitstelle)!") }}
            </div>
        </div>
        <div class="sticky-top container-fluid p-0">
            <ul class="nav nav-underline navbar-nav-scroll d-flex bg-body flex-nowrap pt-2" id="lostfoundTab" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 active" id="found-tab" data-bs-toggle="tab" data-bs-target="#found-tab-pane" type="button" role="tab" aria-controls="found-tab-pane" aria-selected="true">
                        <h3>{{ __("Found") }}</h3>
                    </button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100" id="lost-tab" data-bs-toggle="tab" data-bs-target="#lost-tab-pane" type="button" role="tab" aria-controls="lost-tab-pane" aria-selected="false">
                        <h3>{{ __("Lost") }}</h3>
                    </button>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="lostfoundTabContent">
            <div class="tab-pane fade" id="lost-tab-pane" role="tabpanel" aria-labelledby="lost-tab" tabindex="0">
                @forelse(\App\Models\LostFoundItem::lost()->get() AS $item)
                    <x-lostfound.card :item="$item"></x-lostfound.card>
                @empty
                    <div class="p-3">
                        {{ __("Nothing has been lost yet") }}
                    </div>
                @endforelse
            </div>

            <div class="tab-pane fade show active" id="found-tab-pane" role="tabpanel" aria-labelledby="found-tab" tabindex="0">
                @forelse(\App\Models\LostFoundItem::found()->get() AS $item)
                    <x-lostfound.card :item="$item"></x-lostfound.card>
                @empty
                    <div class="p-3">
                        {{ __("Nothing has been found yet") }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
