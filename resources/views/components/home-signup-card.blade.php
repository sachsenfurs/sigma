@props([
    'title' => "",
    'href' => "",
    'img' => "",
])
<div {{ $attributes->class(['p-2']) }}>
    <div class="col card btn btn-primary h-100">
        <div class="row px-3 p-sm-3">
            <div class="col-8 col-md-12">
                <h4 class="py-3 p-md-0" style="align-content:center; height: 2.5em">{{ $title }}</h4>
                <div class="d-md-none py-2">
                    {{ $slot }}
                </div>
                <a href="{{ $href }}" class="card-link stretched-link"></a>
            </div>
            <div class="col-4 col-md-12 align-content-center p-2">
                <img class="img-fluid rounded" src="{{ $img }}" alt="">
            </div>
            <div class="col-12 d-none d-md-block p-3">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
