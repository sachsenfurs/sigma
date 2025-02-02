@if($getRecord()->avatar_thumb)
    <x-filament::avatar
        src="{{ $getRecord()->avatar_thumb }}"
        class="ml-0.5"
    />
@else
    <x-filament::icon
        icon="heroicon-o-user-circle"
        class="h-9 w-9"
    />
@endif

<span class="text-sm ml-2 my-auto">
    {{ $getRecord()->name }}
</span>
