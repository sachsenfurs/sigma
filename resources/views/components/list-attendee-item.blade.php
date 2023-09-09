@props([
    'name' => "",
    'avatar' => null,
])
<div class="row mb-1">
    @if($avatar)
        <div class="col-4 col-md-2" style="max-height: 100%">
            <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded" style="object-fit: cover; max-height: 30vw" alt="">
        </div>
    @endif
    <div class="@if($avatar)col-8 col-md-10 @else col-md-12 text-left @endif">
        @if ($avatar)
            {{ $name }}
        @else
            <li>{{ $name }}</li>
        @endif
    </div>
</div>