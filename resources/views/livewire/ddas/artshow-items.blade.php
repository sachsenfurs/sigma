<div class="row row-cols-1 row-cols-xxl-2 g-3 align-items-stretch">
    @foreach($items AS $item)
        <div class="col">
            <div class="card h-100" style="cursor: pointer" wire:click="showItem({{$item->id}})">
                <div class="row g-0 h-100">
                    <div class="col p-4 d-grid">
                        <div class="card-title d-flex flex-wrap align-items-center gap-1">
                            <h3 class="me-2 w-100">{{ $item->name }}</h3>
                            <h6 class="text-muted"><i class="bi bi-palette"></i> {{ $item->artist->name }}</h6>
                        </div>
                        <div class="card-text">
                            {!! $item->description_localized !!}
                        </div>
                    </div>
                    <div class="col-auto text-center">
                        <div class="align-self-start justify-content-end p-3">
                            <img src="{{ $item->image_url }}" class="img-fluid" style="max-height: 10em" alt="">
                        </div>
                    </div>
                </div>
                <div class="card-footer m-0 p-0">
                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item flex-fill border-0 border-end">
                            <div class="fw-bold">{{ __("Starting Bid") }}</div>
                            <i class="bi bi-currency-euro"></i> {{ $item->starting_bid }}
                        </li>
                        <li class="list-group-item flex-fill border-0 border-end">
                            <div class="fw-bold">
                                {{ __("Charity Percentage") }}
                            </div>
                            <i class="bi bi-percent"></i> {{ $item->charity_percentage }}
                        </li>
                        <li @class([
                                "list-group-item flex-fill border-0",
                                "text-success" => $item->isHighestBidder(),
                                "text-danger" => $item->userOutbid(),
                                ])>
                            <div class="fw-bold">
                                {{ __("Current Bid") }}
                            </div>
                            <i class="bi bi-currency-euro"></i>
                            {{ \Illuminate\Support\Number::format($item->highestBid?->value ?? 0) ?: "-" }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @endforeach

    {{ $items->links() }}


        <x-modal.livewire-modal class="modal-xl" id="itemModal" type="alert" button-text-ok="Close" x-data="{ confirm: $wire.$entangle('form.confirm') }">
            @if($currentItem)
                <x-slot:title>
                    {{ $currentItem->name }}
                </x-slot:title>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="align-self-start justify-content-end p-3">
                            <img src="{{ $currentItem->image_url }}" class="img-fluid" style="max-height: 40%" alt="">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="row p-2">
                            <div class="col">{{ __("Artist") }}:</div>
                            <div class="col">
                                @if($currentItem->artist->social)
                                    <a href="{{ $currentItem->artist->social }}" target="_blank">{{ $currentItem->artist->name }}</a>
                                @else
                                    {{ $currentItem->artist->name }}
                                @endif
                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col">{{ __("Starting Bid") }}:</div>
                            <div class="col"><i class="bi bi-currency-euro"></i> {{ \Illuminate\Support\Number::format($currentItem->starting_bid) }}</div>
                        </div>
                        <div class="row p-2">
                            <div class="col">{{ __("Current Bid") }}:</div>
                            <div class="col"><i class="bi bi-currency-euro"></i> {{ \Illuminate\Support\Number::format($currentItem->highestBid?->value ?? 0) ?: "-" }}</div>
                        </div>
                        @if($currentItem->isHighestBidder(auth()->user()))
                            <div class="text-success fs-5 text-center p-1 my-2">
                                {{ __("You are currently the highest bidder!") }}
                            </div>
                        @else
                            @if($currentItem->userOutbid())
                                <div class="text-danger text-center p-1 my-2">
                                    {{ __("You have been outbid!") }}
                                </div>
                            @endif
                            @if(!$currentItem->bidPossible())
                                <div class="text-danger text-center p-1 my-2 fs-5">
                                    {{ __("No more bids are currently being accepted for this item") }}
                                </div>
                            @else
                                <div class="row p-2 align-items-baseline">
                                    <div class="col">{{ __("Your Bid") }}:</div>
                                    <div class="col">
                                        <x-form.livewire-input type="number" group-text="€" name="form.value" min="{{ $currentItem->minBidValue() }}" required/>
                                        <div class="form-text">{{ __("At least :value EUR", ['value' => $currentItem->minBidValue() ]) }}</div>
                                    </div>
                                    <div class="col-12 pt-2">
                                        <label class="text-muted" style="font-size: 0.75em">
                                            <input type="checkbox" name="confirm" x-model="confirm" wire:model="form.confirm">
                                            {{ __("I hereby confirm that I have informed myself about the rules and conditions of the Artshow and I am aware that a bid is binding") }}
                                        </label>
                                        <x-form.input-error name="form.confirm"/>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary w-100" :disabled="!confirm" wire:click="submitBid">
                                            {{ __("Submit Bid") }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="row p-2 text-muted">
                            <div class="col-12 p-2 text-center">
                                {{ __("Bids") }} ({{ $currentItem->artshowBids->count() }})
                            </div>
                            @foreach($currentItem->latestBids AS $bid)
                                <div @class(["row", "text-success" => $bid->user_id == auth()->id()])>
                                    <div class="col">
                                        {{ $bid->created_at->diffForHumans() }}
                                    </div>
                                    <div class="col text-end">
                                        <i class="bi bi-currency-euro"></i> {{ \Illuminate\Support\Number::format($bid->value) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </x-modal.livewire-modal>
</div>
