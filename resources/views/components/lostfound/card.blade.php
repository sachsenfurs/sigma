@props([
    'item'
])
<div class="card mt-3">
    <div class="row g-0 ">
        <div class=" col-md-8 ">
            <div class="card-body">
                <h3 class="card-title">
                    @if($item->status == "F")
                        <i class="bi bi-exclamation-square icon-link text-secondary pe-1"></i>
                    @elseif($item->status == "L")
                        <i class="bi bi-question-square icon-link text-secondary pe-1"></i>
                    @endif
                    {{ $item->title }}
                </h3>
                <p class="card-text">
                    {{ $item->description }}
                </p>

                <p class="text-muted small">
                    @if($item->lost_at)
                        {{ __("Lost"). ": " . $item->lost_at->diffForHumans() }}
                    @endif
                    @if($item->found_at)
                        {{ __("Found") . ": " . $item->found_at->diffForHumans() }}
                    @endif
                </p>
            </div>

        </div>
        <div class="col-md-4 align-middle">
            @if($item->image_url)
                <a href="{{ $item->image_url }}" target="_blank">
                    <img src="{{ $item->image_url }}" class="img-fluid object-fit-cover h-100 w-100 rounded-end d-none d-md-block" loading="lazy" alt="">
                    <img src="{{ $item->image_url }}" class="img-fluid object-fit-cover h-100 w-100 rounded-bottom d-xs-block d-md-none" loading="lazy" alt="">
                </a>
            @endif
        </div>
    </div>
</div>
