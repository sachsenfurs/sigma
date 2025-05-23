<div class="row row-cols-1 row-cols-xxl-2 g-3 align-items-stretch">
    <div class="col-12 w-100">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search" wire:loading.remove wire:target="search, artist"></i>
                        <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="search, artist"></div>
                    </span>
                    <x-form.input-floating wire:model.live.debounce="search" :placeholder="__('Search')" wire:keydown.debounce="resetPage"></x-form.input-floating>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group col-12 col-md-6 h-100">
                    <label class="input-group-text" for="inputGroupSelect01">
                        {{ __("Artist") }}
                    </label>
                    <select wire:model.live="artist" class="form-select" id="inputGroupSelect01" wire:change.debounce="resetPage">
                        <option value="0">- {{ __("All") }} -</option>
                        @foreach($this->artists AS $artist)
                            @php($count = $this->itemsWithoutArtistFilter->filter(fn($i) => $i->artist->id == $artist->id)->count())
                            <option value="{{ $artist->id }}" @disabled($count == 0)>
                                {{ $artist->name }}
                                @if($this->search)
                                    ({{ $count }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @forelse($this->itemsPaginated AS $item)
        <div class="col" wire:loading.remove wire:target="search, artist">
            <div class="card h-100" style="cursor: pointer" wire:click.throttle.1000ms="showItem({{$item->id}})">
                <div class="row g-0 h-100">
                    <div class="col p-4 d-grid">
                        <div class="card-title d-flex flex-wrap align-items-start gap-1">
                            <h3 class="me-2 w-100">{{ $item->name }}</h3>
                            <h6 class="text-muted"><i class="bi bi-palette icon-link"></i> {{ $item->artist->name }}</h6>
                        </div>
                        <div class="card-text small">
                            {!! $item->description_localized !!}
                        </div>
                    </div>
                    <div class="col-auto text-center">
                        <div class="align-self-start justify-content-end p-3">
                            <img oncontextmenu="return false;" tabindex="0" src="{{ $item->image_url }}" @class(["rounded img-fluid shadow", "blur-hover" => $item->rating == \App\Enums\Rating::NSFW]) style="max-height: 10em" alt="" id="itemImage{{$item->id}}" loading="lazy">
                            @if($item->rating == \App\Enums\Rating::NSFW)
                                <div class="mt-3">
                                    <span class="badge border border-danger-subtle bg-danger-subtle text-danger opacity-50 fs-6">{{ \App\Enums\Rating::NSFW->name() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer m-0 p-0">
                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item flex-fill border-0 border-end">
                            <div class="fw-bold">{{ __("Starting Bid") }}</div>
                            <i class="bi bi-currency-euro icon-link"></i> {{ $item->starting_bid }}
                        </li>
                        <li class="list-group-item flex-fill border-0 border-end">
                            <div class="fw-bold">
                                {{ __("Charity Percentage") }}
                            </div>
                            <i class="bi bi-percent icon-link"></i> {{ $item->charity_percentage }}
                        </li>
                        <li @class([
                                "list-group-item flex-fill border-0",
                                "text-success" => $item->isHighestBidder(),
                                "text-danger" => $item->userOutbid(),
                                ])>
                            <div class="fw-bold">
                                {{ __("Current Bid") }}
                            </div>
                            <i class="bi bi-currency-euro icon-link"></i>
                            {{ \Illuminate\Support\Number::format($item->highestBid?->value ?? 0) ?: "-" }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @empty
        <x-infocard class="mt-3 w-100" wire:loading.remove wire:target="search, artist">{{ __("Nothing found") }}</x-infocard>
    @endforelse

    <div class="w-100" wire:loading.remove wire:target="search, artist">
        {{ $this->itemsPaginated->links() }}
    </div>

    <x-modal.livewire-modal class="modal-xl" id="itemModal" type="alert" button-text-ok="Close" x-data="{ confirm: $wire.$entangle('form.confirm') }">
        @if($currentItem)
            <x-slot:title>
                {{ $currentItem->name }}
            </x-slot:title>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="align-self-start justify-content-end p-3">
                        <img src="{{ $currentItem->image_url }}" class="rounded img-fluid" style="max-height: 40%" alt="" id="previewImage{{$currentItem->id}}">
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row p-2">
                        <div class="col">{{ __("Artist") }}:</div>
                        <div class="col">
                            @if($currentItem->artist->social)
                                <a href="{{ $currentItem->artist->social }}" class="text-decoration-none" target="_blank">
                                    <i class="bi bi-box-arrow-up-right icon-link"></i>
                                    {{ $currentItem->artist->name }}
                                </a>
                            @else
                                {{ $currentItem->artist->name }}
                            @endif
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col">{{ __("Starting Bid") }}:</div>
                        <div class="col"><i class="bi bi-currency-euro icon-link"></i> {{ \Illuminate\Support\Number::format($currentItem->starting_bid) }}</div>
                    </div>
                    <div class="row p-2">
                        <div class="col">{{ __("Current Bid") }}:</div>
                        <div class="col"><i class="bi bi-currency-euro icon-link"></i> {{ \Illuminate\Support\Number::format($currentItem->highestBid?->value ?? 0) ?: "-" }}</div>
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
                                {{ __("No bids are currently being accepted for this item") }}
                            </div>
                        @else
                            <div class="row p-2 align-items-baseline">
                                <div class="col">{{ __("Your Bid") }}:</div>
                                <div class="col">
                                    <x-form.livewire-input type="number" :group-text="config('app.currency_symbol')" name="form.value" min="{{ $currentItem->minBidValue() }}" required/>
                                    <div class="form-text">{{ __("At least :value", ['value' => \Illuminate\Support\Number::currency($currentItem->minBidValue()) ]) }}</div>
                                </div>
                                <div class="col-12 pt-2">
                                    <label class="text-muted" style="font-size: 0.75em">
                                        <input type="checkbox" name="confirm" x-model="confirm" wire:model="form.confirm">
                                        {!! \App\Services\PageHookService::resolve(
                                            "artshow.items.dialog.rules",
                                            __("I hereby confirm that I have informed myself about the rules and conditions of the Artshow and I am aware that a bid is binding")
                                        )  !!}
                                    </label>
                                    <x-form.input-error name="form.confirm"/>
                                </div>
                                <div class="col-12 mt-2">
                                    <button class="btn btn-primary w-100" :disabled="!confirm" wire:click.throttle.500ms="submitBid">
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
                                    <i class="bi bi-currency-euro icon-link"></i> {{ \Illuminate\Support\Number::format($bid->value) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </x-modal.livewire-modal>
</div>
