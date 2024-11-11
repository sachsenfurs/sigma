<div x-data="{ show: true }">
    @if(Str::length($getState()) > 150)
        <div x-init="show = false" @click.stop="show = !show" x-show="!show" class="text-xs text-wrap">
            {{ Str::limit($getState(), 150) }}
        </div>
    @endif
    <div x-show="show" class="text-wrap text-xs">
        {{ $getState() }}
    </div>
</div>
