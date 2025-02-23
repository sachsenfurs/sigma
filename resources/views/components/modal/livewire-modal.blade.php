@props([
    'title' => "",
    'type' => "submit",
    'id' => "modal",
    'action' => null,
    'buttonTextOk' => "OK",
])
<div wire:ignore.self id="{{ $id }}" tabindex="-1" class="modal fade" aria-modal="true" role="dialog" {{ $attributes->except("class") }}>
    <div {{ $attributes->class(['modal-dialog']) }}>
        <div class="modal-content" @if($action) wire:keydown.debounce.enter="{{$action}}" @endif>
            <div class="modal-header">
                <div class="row">
                    <div class="col-12"><h5 class="modal-title">{{ $title }}</h5></div>
                    <div class="col-12">{{ $subtitle ?? "" }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                @if($type == "submit")
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:click.debounce="{{$action}}" wire:loading.attr="disabled">{{ __('Save') }}</button>
                @elseif($type == "confirm")
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('No') }}</button>
                    <button type="button" class="btn btn-primary" wire:click.debounce="{{$action}}" wire:loading.attr="disabled">{{ __('Yes') }}</button>
                @elseif($type == "alert")
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __($buttonTextOk) }}</button>
                @endif
            </div>
        </div>
    </div>
    @script
    <script>
        $wire.on('showModal', function(id) {
            bootstrap.Modal.getInstance(document.getElementById(id))?.hide();
            new bootstrap.Modal(document.getElementById(id)).show();
            document.getElementById(id).addEventListener('shown.bs.modal', (event) => event.target.querySelector('input,.btn-primary').focus());
        });
        $wire.on('hideModal', (id) => bootstrap.Modal.getInstance(document.getElementById(id)).hide());
    </script>
    @endscript
</div>
