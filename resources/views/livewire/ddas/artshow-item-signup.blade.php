<div>
    @if($registerArtist)
        <h2>{{ __("Create Artist Profile") }}</h2>

        <button class="btn card h-100 w-100 text-center justify-content-center btn-success" style="min-height: 10rem" wire:click="newArtist">
            <div class="fs-2">
                <i class="bi bi-plus-lg"></i>
                {{ __("Register as Exhibitor / Artist") }}
            </div>
        </button>

        <x-modal.livewire-modal id="artistModal" action="createArtist">
            <x-slot:title>
                {{ __("Register as Exhibitor / Artist") }}
            </x-slot:title>
            <div class="row g-3">
                <div class="col-12">
                    <x-form.livewire-input name="artistForm.name" :label="__('Artist Name')" />
                </div>
                <div class="col-12">
                    <x-form.livewire-input name="artistForm.social" :label="__('Gallery Link, Social Media, etc. (optional)')" />
                </div>
            </div>
        </x-modal.livewire-modal>
    @endif

    @if(!$registerArtist)
        <h2>{{ __("Submitted Items") }}</h2>
        <p class="text-secondary">{{ $artistProfile->name }} @if($artistProfile->social) - {{$artistProfile->social}} @endif</p>

        @if($this->artists->count() > 1)
            <div class="my-3">
                <label for="artistProfile">{{ __("Select Artist Profile") }}</label>
                <select name="artistProfile" id="artistProfile" class="form-select" wire:model.live="selectedArtistProfile">
                    @foreach($artists AS $artist)
                        <option value="{{$artist->id}}" wire:key="{{ $artist->id }}">{{ $artist->name }} @if($artist->social) - {{$artist->social}} @endif</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-lg-2 g-3">
            @foreach(($artistProfile->artshowItems ?? []) AS $item)
                <div class="col">
                    <div class="card" style="min-height: 15rem">
                        <div class="card-header align-content-center" style="min-height: 3.1rem">
                            <div class="row">
                                <div class="col align-self-center">
                                    {{ $item->name }}
                                </div>
                                <div class="col text-end">
                                        <span @class(['badge text-uppercase', 'bg-success' => ($item->sold OR $item->approved), 'bg-warning text-black' => !$item->approved])>
                                            @if($item->sold)
                                                {{ __("Sold") }}
                                            @else
                                                {{ $item->approved ? __("Approved") : __("Pending Approval") }}
                                            @endif
                                        </span>
                                    @canany(['edit', 'delete'], $item)
                                        <div class="dropdown d-inline p-0 m-0 ms-1">
                                            <button class="btn lh-1 p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical fs-4"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @can('update', $item)
                                                    <li>
                                                        <a class="dropdown-item" href="#" wire:click="editItem({{$item->id}})">
                                                            <i class="bi bi-pencil"></i> {{ __("Edit") }}
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete', $item)
                                                    <li>
                                                        <a class="dropdown-item" href="#" wire:click="deleteItem({{$item->id}})">
                                                            <i class="bi bi-trash"></i> {{ __("Remove") }}
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md col-12">
                                    <p class="card-text">
                                        {{ $item->description }}
                                    </p>
                                </div>
                                <div class="col-12 col-md-auto align-top px-2 text-center">
                                    <img src="{{ $item->imageUrl }}" alt="" class="rounded" style="max-height: 10rem">
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
                                            {{ __("Current Bid") }}:
                                        </div>
                                        <i class="bi bi-currency-euro"></i> {{ $item->artshow_bids_count ?? "-"}}
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @can("create", [\App\Models\DDAS\ArtshowItem::class, $artistProfile])
                <div class="col">
                    <button class="btn card h-100 w-100 text-center justify-content-center btn-success" style="min-height: 10rem" wire:click="newItem">
                        <div class="fs-2">
                            <i class="bi bi-plus-lg"></i>
                            {{ __("Add Item") }}
                        </div>
                    </button>
                </div>
            @endcan

        </div>


        <x-modal.livewire-modal type="confirm" id="confirmModal" :title="__('Confirm')" action="confirm('itemModal')">
            {{ __("Really delete it?") }}
        </x-modal.livewire-modal>

        <x-modal.livewire-modal class="modal-xl" id="itemModal" action="submit">
            <x-slot:title>
                @if($editArtshowItem)
                    {{ __('Edit Item') }}
                @else
                    {{ __('Submit Item') }}
                @endif
            </x-slot:title>

            <div class="row g-3 align-items-start">
                <div class="col-ld-7 col-12 col-xl-8">
                    <div class="row g-3 d-flex">
                        <div class="col-12">
                            <div class="form-floating">
                                <x-form.livewire-input type="text" name="form.name" placeholder="{{ __('Item Name') }}" required/>
                                <label for="form.name">{{ __('Item Name') }}</label>
                            </div>
                        </div>
                        <fieldset>
                            <legend class="col-12 fs-5">
                                <input type="checkbox" class="form-check-input" wire:model="form.auction" id="auction">
                                <label for="auction">{{ __('Up for auction') }}</label>
                            </legend>
                            <div class="row row-cols-2" x-show="$wire.form.auction">
                                <div class="col">
                                    <x-form.livewire-input type="number" group-text="EUR" name="form.starting_bid" :label="__('Starting Bid')" min="0" required/>
                                </div>
                                <div class="col">
                                    <x-form.livewire-input type="number" group-text="%" name="form.charity_percentage" :label="__('Charity Percentage')" min="0" max="100" required/>
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-12" style="height: 100%">
                            <x-form.livewire-input type="textarea" name="form.description" :label="__('Description')" rows="5" required/>
                            <span class="small">{{ __('Visible for everyone') }}</span>
                        </div>
                        <div class="col-12" style="height: 100%">
                            <x-form.livewire-input type="textarea" name="form.additional_info" label="Additional Information" rows="5" required/>
                            <span class="small">{{ __('Only visible for staff. Here you can put some details or fun facts for the auction!') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-ld-5 col-12 col-xl-4">
                    <label class="container rounded bg-body border p-3" style="cursor: pointer" for="selectImage">
                        @if(!$editArtshowItem?->imageUrl AND !$form->new_image?->temporaryUrl())
                            {{ __('Image') }}
                        @endif
                        <div class="text-center">
                            <img id="preview" src="{{ $form->new_image?->temporaryUrl() ?? $editArtshowItem->imageUrl ?? "#" }}" alt="" class="rounded"
                                 style="width: 100%; height: 100%; object-fit: cover">
                        </div>
                    </label>
                    <input type="file" accept="image/*" class="form-control" wire:model="form.new_image" id="selectImage">
                    <x-form.livewire-error name="form.new_image"/>
                    {{--                <script>--}}
                    {{--                    selectImage.onchange = evt => {--}}
                    {{--                        let preview = document.getElementById('preview');--}}
                    {{--                        preview.style.display = 'block';--}}
                    {{--                        const [file] = selectImage.files--}}
                    {{--                        if (file) {--}}
                    {{--                            preview.src = URL.createObjectURL(file)--}}
                    {{--                        }--}}
                    {{--                    }--}}
                    {{--                </script>--}}
                </div>
            </div>
        </x-modal.livewire-modal>
    @endif
</div>
