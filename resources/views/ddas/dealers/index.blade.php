@extends('layouts.app')
@section('title', __("Dealer's Den Overview"))

@section('content')
    <div class="container">

        @if(app(\App\Settings\DealerSettings::class)->show_dealers_date->isAfter(now()))
            <x-infocard>
                {{ __("Dealers are not published yet") }}
            </x-infocard>
        @else
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('dealers', () => (
                        @json(\App\Http\Resources\DealerResource::collection(\App\Models\Ddas\Dealer::approved()->with("sigLocation")->orderBy("name")->get()))
                    ));
                });
            </script>

            <div x-data="{dealers, filter: 0}">
        {{--        @can('create', \App\Models\Ddas\Dealer::class)--}}
        {{--            <a href="{{ route("dealers.create") }}">--}}
        {{--                <button class="btn text-center p-5 btn-success">--}}
        {{--                    <div class="fs-4">--}}
        {{--                        <i class="bi bi-list-check"></i>--}}
        {{--                        {{ __("Register as Dealer") }}--}}
        {{--                    </div>--}}
        {{--                </button>--}}
        {{--            </a>--}}
        {{--        @endcan--}}

                <h2 class="mt-3">{{ __("Dealer List") }}</h2>

                <div class="row row-cols-auto g-2 my-3 align-items-stretch justify-content-around">
                    <div class="col flex-grow-1">
                        <input id="filterAll" class="btn-check" x-model="filter" type="radio" name="filter" value="0" />
                        <label class="btn btn-outline-primary d-flex" for="filterAll">{{ __("Show All") }}</label>
                    </div>
                    @foreach(\App\Models\Ddas\DealerTag::used()->get() AS $tag)
                        <div class="col-auto flex-grow-1">
                            <input id="filter{{$tag->id}}" class="btn-check" x-model="filter" type="radio" name="filter" value="{{$tag->id}}" />
                            <label class="btn btn-outline-primary d-flex h-100" for="filter{{$tag->id}}">{{ $tag->name_localized }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="row row-cols-1 row-cols-lg-2 g-3 align-items-stretch">
                    <template x-for="dealer in dealers().filter(d => (filter==0 || d.tags.find(t => t.id == filter)))">
                        <div class="col">
                            <div class="card h-100">
                                <div class="row g-0 h-100">
                                    <div class="col p-4 d-grid">
                                        <div class="card-title d-flex flex-wrap align-items-center gap-1">
                                            <h3 class="me-2 w-100" x-text="dealer.name"></h3>
                                            <template x-for="tag in dealer.tags">
                                                <span class="badge bg-dark fs-6 text-secondary" x-text="tag.name_localized"></span>
                                            </template>
                                        </div>
                                        <div class="card-text" x-text="dealer.info"></div>
                                        <div class="mt-3" x-show="dealer.gallery_link">
                                            <a x-bind:href="dealer.gallery_link" target="_blank">
                                                <button type="button" class="btn btn-dark">
                                                    <i class="bi bi-link-45deg icon-link"></i> <span x-text="dealer.gallery_link_name"></span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <div x-show="dealer.icon_file" class="align-self-start justify-content-end p-3">
                                            <img x-bind:src="dealer.icon_file" class="img-fluid" style="max-height: 10em" alt="">
                                        </div>
                                        <div class="mx-1 p-4 d-block text-center">
                                            <i x-show="dealer.location" class="bi bi-geo-alt icon-link"></i>
                                            <span x-text="dealer.location"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        @endif

    </div> <!-- end container -->
@endsection
