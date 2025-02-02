@props([
    'tabs' => [],
    'activeIndex' => 0,
])
<div {{ $attributes->merge(['class' => 'sticky-top container-fluid p-0']) }}>
    <ul class="nav nav-underline navbar-nav-scroll d-flex bg-body flex-nowrap pt-2" id="tab" role="tablist">
        @foreach($tabs AS $index => $tab)
            @isset($__laravel_slots[$tab])
                <li class="nav-item flex-fill" role="presentation">
                    <button @class(["nav-link w-100 h-100", "active" => $loop->index == $activeIndex]) id="{{ \Illuminate\Support\Str::kebab($tab) }}-tab"
                            data-bs-toggle="tab" data-bs-target="#{{ \Illuminate\Support\Str::kebab($tab) }}-tab-pane" type="button" role="tab"
                            aria-controls="{{ \Illuminate\Support\Str::kebab($tab) }}-tab-pane" aria-selected="{{ $loop->index == $activeIndex ? "true":"false" }}">
                        <h4>
                            {{ __($tab) }}
                            @if($__laravel_slots[$tab]?->attributes['badge'] ?? false)
                                <span @class(["badge", $__laravel_slots[$tab]?->attributes['badgeClass'] ?? ""])>{{ $__laravel_slots[$tab]?->attributes['badge'] }}</span>
                            @endif
                        </h4>
                    </button>
                </li>
            @endisset
        @endforeach
    </ul>
</div>
<div class="tab-content" id="tabContent">
    @foreach($tabs AS $tab)
        <div @class(["tab-pane fade", "show active" => $loop->index == $activeIndex]) id="{{ \Illuminate\Support\Str::kebab($tab) }}-tab-pane"
             role="tabpanel" aria-labelledby="{{ \Illuminate\Support\Str::kebab($tab) }}-tab" tabindex="{{ $loop->index }}">
            {{ $__laravel_slots[$tab] ?? "" }}
        </div>
    @endforeach
</div>
