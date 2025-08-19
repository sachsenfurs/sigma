<div>
    @if(auth()->user()->dealers()->count() > 0)
        <h2 class="mt-3">{{ __("Dealer's Den Sign Up") }} - {{ __("Status") }}</h2>
        @foreach(auth()->user()->dealers AS $dealer)
            <div class="card mt-3">
                <div class="card-body p-4">
                    <div class="row flex-nowrap">
                        @if($dealer->icon_file_url)
                            <div class="col-auto">
                                <div class="col-auto text-center">
                                    <div class="align-self-start justify-content-end p-3">
                                        <img src="{{ $dealer->icon_file_url }}" class="img-fluid" style="max-height: 10em" alt="">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col fs-3 align-items-center">
                                        {{ $dealer->name }}
                                    </div>
                                    <div class="col fs-4 text-end align-content-center">
                                         <span @class([ 'badge', $dealer->approval->style() ])>
                                             {{ $dealer->approval->name() }}
                                         </span>
                                        @canany(['update', 'delete'], $dealer)
                                            <div class="dropdown d-inline p-0 m-0 ms-1">
                                                <button class="btn lh-1 p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical fs-4 icon-link"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('update', $dealer)
                                                        <li>
                                                            <a class="dropdown-item" href="#" wire:click.debounce="editDealer({{ $dealer->id }})">
                                                                <i class="bi bi-pencil icon-link"></i> {{ __("Edit") }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('delete', $dealer)
                                                        <li>
                                                            <a class="dropdown-item" href="#" wire:click="removeDealer({{  $dealer->id }})">
                                                                <i class="bi bi-trash icon-link"></i> {{ __("Remove") }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                                @if($dealer->tags->count())
                                    <div class="col-12">
                                        @foreach($dealer->tags AS $tag)
                                            <span class="badge bg-dark fs-6 text-secondary">{{ $tag->name_localized }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="card-text">{{ $dealer->info_localized }}</div>

                            <div class="card m-2">
                                <div class="card-body text-secondary">
                                    {{ $dealer->additional_info }}
                                </div>
                            </div>

                            @if($dealer->gallery_link)
                                <div class="mt-3">
                                    <a href="{{ $dealer->gallery_link }}" target="_blank">
                                        <button type="button" class="btn btn-dark">
                                            <i class="bi bi-link-45deg"></i> <span>{{ $dealer->gallery_link_name }}</span>
                                        </button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    @else
        <h2 class="mt-3">{{ __("Sign up") }}</h2>

        <button class="btn card h-100 w-100 text-center justify-content-center btn-success mt-3" style="min-height: 10rem" wire:click="newDealer">
            <div class="fs-2">
                <i class="bi bi-plus-lg icon-link"></i>
                {{ __("Register as Dealer") }}
            </div>
        </button>
    @endif

    <x-modal.livewire-modal id="removeDealerConfirm" action="deleteDealer" type="confirm" :title="__('Revoke Dealer Application')">
        {{ __("Really delete it?") }}
    </x-modal.livewire-modal>

    <x-modal.livewire-modal action="createUpdateDealer" class="modal-xl modal-fullscreen-lg-down">
        <x-slot:title>
            {{ __("General Information") }}
        </x-slot:title>
        <div class="row g-3 align-items-start">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-5 col-7 col-lg-3">
                        <label class="container rounded bg-body border p-3" style="cursor: pointer" for="selectImage">
                            {{ __('Image (optional)') }}
                            <div class="text-center" style="aspect-ratio: 1">
                                <div wire:loading.class="spinner-border m-5" wire:target="form.icon_file"></div>
                                @if($form->icon_file)
                                    <img src="{{ $form->icon_file?->temporaryUrl() }}"
                                        wire:loading.remove wire:target="form.icon_file" alt="" class="rounded"
                                        style="width: 100%; height: 100%; object-fit: cover" />
                                @elseif($form->icon_file_url)
                                    <img src="{{ $form->icon_file_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover" />
                                @endif
                            </div>
                            <x-form.input-error name="form.icon_file" />
                        </label>
                        <input type="file" accept="image/*" class="form-control" wire:model="form.icon_file" id="selectImage" wire:loading.attr="disabled">
                    </div>
                    <div class="col-md-7 col-12 col-lg-9">
                        <div class="row g-3 d-flex">
                            <div class="col-12">
                                <div class="form-floating">
                                    <x-form.livewire-input type="text" name="form.name" :placeholder="__('Dealers Name')" required/>
                                    <label for="form.name">{{ __('Dealers Name') }}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <x-form.livewire-input type="text" name="form.gallery_link" :placeholder="__('Gallery Link, Website, ...')" required/>
                                    <label for="form.name">{{ __('Gallery Link, Website, ...') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-xl-6" style="height: 100%">
                                <x-form.livewire-input type="textarea" name="form.info" :label="__('Dealer Information (What do you offer, ..?)')" rows="6"/>
                                <span class="text-muted small">{{ __("This text will be visible for everyone and will be printed in hundreds of conbooks!") }}</span>
                            </div>
                            <div class="col-12 col-xl-6" style="height: 100%">
                                <p class="form-label">{{ __("Select applicable") }}</p>
                                <div class="row px-2">
                                    @foreach(\App\Models\Ddas\DealerTag::all() AS $tag)
                                        <div class="form-check col-6">
                                            <input type="checkbox" class="form-check-input" id="tag_{{$tag->id}}" value="{{ $tag->id }}" wire:model="form.tags">
                                            <label for="tag_{{$tag->id}}" class="form-check-label">{{ $tag->name_localized }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <x-form.livewire-input type="textarea" name="form.additional_info" :label="__('Organizational Information')" rows="6"/>
                <p class="text-muted small p-1">
                    {{ __("How much space do you need? Do you need a power socket? Tell us what you need and we'll do our best to satisfy you!") }}
                </p>
            </div>

        </div>
    </x-modal.livewire-modal>
</div>
