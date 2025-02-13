<div>
    @if(auth()->user()->dealers()->count() > 0)
        <h2 class="mt-3">{{ __("Status") }}</h2>
        @foreach(auth()->user()->dealers AS $dealer)
            <div class="card h-100 mt-3">
                <div class="row g-0 h-100">
                    <div class="col p-4 d-grid">
                        <div class="card-title d-flex flex-wrap align-items-center gap-1">
                            <h3 class="me-2 w-100">{{ $dealer->name }}
                                <span @class([
                                    'badge',
                                    match($dealer->approval) {
                                        \App\Enums\Approval::PENDING => 'bg-warning text-black',
                                        \App\Enums\Approval::APPROVED => 'bg-success',
                                        \App\Enums\Approval::REJECTED => 'bg-danger',
                                        default => "",
                                    }
                                ])>
                                    {{ $dealer->approval->name() }}
                                </span>
                            </h3>
                            @foreach($dealer->tags AS $tag)
                                <span class="badge bg-dark fs-6 text-secondary">{{ $tag->name_localized }}</span>
                            @endforeach
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
                    <div class="col-auto text-center">
                        <div class="align-self-start justify-content-end p-3">
                            <img src="{{ $dealer->icon_file_url }}" class="img-fluid" style="max-height: 10em" alt="">
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

        <x-modal.livewire-modal action="createDealer" class="modal-xl modal-fullscreen-lg-down">
            <x-slot:title>
                {{ __("General Information") }}
            </x-slot:title>
            <div class="row g-3 align-items-start">
                <div class="col-md-5 col-7 col-lg-3">
                    <label class="container rounded bg-body border p-3" style="cursor: pointer" for="selectImage">
                        {{ __('Image (optional)') }}
                        <div class="text-center" style="aspect-ratio: 1">
                            <div wire:loading.class="spinner-border m-5" wire:target="form.icon_file"></div>
                            @if($form->icon_file)
                                <img src="{{ $form->icon_file?->temporaryUrl() }}" wire:loading.remove wire:target="form.icon_file" alt="" class="rounded"
                                     style="width: 100%; height: 100%;  object-fit: cover"/>
                            @endif
                        </div>
                    </label>
                    <input type="file" accept="image/*" class="form-control" wire:model="form.icon_file" id="selectImage" wire:loading.attr="disabled">
                    {{--                    <x-form.input-image name="image" id="selectImage" style="display:none"/>--}}
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
                        </div>
                        <div class="col-12 col-xl-6" style="height: 100%">
                            <x-form.livewire-input type="textarea" name="form.additional_info" :label="__('Organizational Information')" rows="6"/>
                        </div>
                    </div>
                </div>

            </div>
        </x-modal.livewire-modal>
    @endif
</div>
