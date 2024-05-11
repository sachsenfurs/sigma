@props([
    'title' => "",
    'type' => "submit",
    'id' => "modal",
    'action' => null,
])
<div wire:ignore.self id="{{ $id }}" tabindex="-1" {{ $attributes->class(['modal fade']) }} aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                @if($type == "submit")
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="{{$action}}">{{ __('Save') }}</button>
                @elseif($type == "confirm")
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('No') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="{{$action}}">{{ __('Yes') }}</button>
                @elseif($type == "alert")
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('OK') }}</button>
                @endif
            </div>
        </div>
    </div>
    @script
    <script>
        $wire.on('showModal', (id) => $('#'+id[0]).modal('show'));
        $wire.on('hideModal', (id) => $('#'+id[0]).modal('hide'));
    </script>
    @endscript
</div>
