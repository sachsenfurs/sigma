<div>
    <x-modal.livewire-modal id="hostModal" action="storeHost">
        <x-slot:title>
            {{ __("Register as Host") }}
        </x-slot:title>
        <div class="row g-3">
            <div class="col-12">
                <x-form.livewire-input name="form.name" :label="__('Host Name')" wire:keydown.enter="storeHost" />
            </div>
        </div>
    </x-modal.livewire-modal>


    <button class="btn btn-primary" wire:click="createHost">{{ __("Register as Host") }}</button>
</div>
