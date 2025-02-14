<div>
    <x-modal.livewire-modal id="hostModal" action="storeHost">
        <x-slot:title>
            {{ __("Register as Host") }}
        </x-slot:title>
        <div class="row g-3">
            <div class="col-12">
                <x-form.livewire-input name="form.name" :label="__('Host Name')" />
            </div>
        </div>
    </x-modal.livewire-modal>


    <button class="btn card h-100 w-100 text-center justify-content-center btn-success mt-3" style="min-height: 10rem" wire:click="createHost">
        <div class="fs-2">
            <i class="bi bi-plus-lg icon-link"></i>
            {{ __("Register as Host") }}
        </div>
    </button>
</div>
