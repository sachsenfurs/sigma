<x-filament-panels::page>
    {{ $this->form }}

    <x-filament::button wire:click="submit">
        {{ __("Send") }}
    </x-filament::button>

</x-filament-panels::page>
