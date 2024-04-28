@extends('layouts.app')
@section('title', __('New Artshow Item'))

@section('content')

    <div class="container">
        <h2>{{ __("Submitted Items") }}</h2>
        <div class="row row-cols-1 row-cols-lg-2 g-3">
            <div class="col">
                <button class="btn card h-100 w-100 text-center justify-content-center btn-success" style="min-height: 10rem" data-bs-toggle="modal" data-bs-target="#itemModal">
                    <div class="fs-2">
                        <i class="bi bi-plus-lg"></i>
                        {{ __("Add Item") }}
                    </div>
                </button>
            </div>

            @foreach(\App\Models\DDAS\ArtshowItem::own()->get() AS $item)
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col align-self-center">
                                    {{ $item->name }}
                                </div>
                                <div class="col text-end">
                                    <span @class(['badge', 'bg-success' => ($item->sold OR $item->approved), 'bg-warning' => !$item->approved])>
                                        @if($item->sold)
                                            {{ __("Sold") }}
                                        @else
                                            @if($item->approved)
                                                {{ __("Approved") }}
                                            @else
                                                {{ __("Approval pending") }}
                                            @endif
                                        @endif
                                    </span>
                                    <div class="dropdown d-inline p-0 m-0 ms-1">
                                        <button class="btn lh-1 p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical fs-4"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#itemModal" data-id="{{ $item->id }}">
                                                    <i class="bi bi-pencil"></i> {{ __("Edit") }}
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-trash"></i> {{ __("Remove") }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md col-12">
{{--                                    <label for="" class="form-label fw-bold">{{ __("Description") }}</label>--}}
                                    <p class="card-text">
                                        {{ $item->description }}
                                    </p>
                                </div>
                                <div class="col-12 col-md-auto align-top px-2 text-center">
                                    <img src="{{ $item->image_file }}" alt="" class="rounded" style="max-height: 10rem">
                                </div>
                            </div>
                        </div>
                        @if($item->auction)
                            <div class="card-footer m-0 p-0">
                                <ul class="list-group list-group-horizontal">
                                    <li class="list-group-item flex-fill border-0 border-end">
                                        <div class="fw-bold">
                                            {{ __("Starting Bid") }}:
                                        </div>
                                        <i class="bi bi-currency-euro"></i> {{ $item->starting_bid }}
                                    </li>
                                    <li class="list-group-item flex-fill border-0 border-end">
                                        <div class="fw-bold">
                                            {{ __("Charity Percentage") }}:
                                        </div>
                                        <i class="bi bi-percent"></i> {{ round($item->charity_percentage) }}
                                    </li>
                                    <li class="list-group-item flex-fill border-0">
                                        <div class="fw-bold">
                                            {{ __("Last Bid") }}:
                                        </div>
                                        <i class="bi bi-currency-euro"></i> {{ $item->artshow_bids_count }}
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>

        <x-modal :title="__('Add Item')" id="itemModal" class="modal-xl" method="POST" :action="route('artshow.store')" x-data="{auction: true, starting_bid:0, test:0}" data-editurl="/artshow/">
            <div class="card-body">
                <div class="row g-3 align-items-start">
                    <div class="col-ld-7 col-12 col-xl-8">
                        <div class="row g-3 d-flex">
                            <div class="col-12">
                                <x-form.input-floating :placeholder="__('Item Name')" name="name" required />
                            </div>
                            <fieldset>
                                <legend class="col-12 fs-5">
                                    <input type="checkbox" class="form-check-input" name="auction" id="auction" x-model="auction">
                                    <label for="auction">{{ __("Up for auction") }}</label>
                                </legend>
                                <div class="row row-cols-2" x-show="auction">
                                    <div class="col">
                                        <x-form.alpine-input type="number" group-text="EUR" min="0" name="starting_bid" :label="__('Starting Bid')" x-bind:required="auction" />
                                    </div>
                                    <div class="col">
                                        <x-form.alpine-input type="number" group-text="%" min="0" max="100" name="charity_percentage" :label="__('Charity Percentage')" x-bind:required="auction" />
                                    </div>
                                </div>
                            </fieldset>
                            <div class="col-12" style="height: 100%" x-data="input">
                                <x-form.alpine-input type="textarea" :label="__('Description')" name="description" rows="5">
                                    <span class="small">{{ __("Visible for everyone") }}</span>
                                </x-form.alpine-input>
                            </div>
                            <div class="col-12" style="height: 100%">
                                <x-form.alpine-input type="textarea" :label="__('Description')" name="additional_info" rows="3">
                                    <span class="small">{{ __("Only visible for staff. Here you can put some details or fun facts for the auction!") }}</span>
                                </x-form.alpine-input>
                            </div>
                        </div>
                    </div>
                    <div class="col-ld-5 col-12 col-xl-4">
                        <label class="container rounded bg-body border p-3" style="cursor: pointer" for="selectImage">
                            {{ __("Image") }}
                            <div class="text-center" style="">
                                <img id="preview" src="#" alt="" class="rounded" style="display: none; width: 100%; height: 100%;  object-fit: cover"/>
                            </div>
                        </label>
                        <x-form.input-image name="image" id="selectImage" />
                    </div>
                </div>
            </div>
        </x-modal>
    </div>
@endsection
