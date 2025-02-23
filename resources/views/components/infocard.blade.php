<div {{ $attributes->merge(['class' => 'container text-center']) }}>
    <div class="mt-3 card p-4 fs-4">
        {{ $slot }}
    </div>
</div>
