@props([
    'icon' => null,
    'subtitle' => "",
])
<div {{ $attributes->class(['col-12 col-md-6 col-xl-4']) }}>
    <div class="card bg-light-subtle h-100">
        <div class="card-body">
            <h5 class="card-title">
                @if($icon)
                    <i @class(['bi', $icon])></i>
                @endif
                {{ $slot }}
            </h5>
            <div class="card-subtitle px-1 py-2">
                <small>
                    {{ $subtitle }}
                </small>
            </div>
        </div>
    </div>
</div>
