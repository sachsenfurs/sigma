@props([
    'name' => "",
    'avatar' => null,
])
<div class="row">
    @if($avatar)
        <div class="col-md-2" style="max-height: 100%">
            <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded-top" style="object-fit: cover; max-height: 30vw" alt="">
        </div>
    @endif
    <div class="@if($avatar)col-md-10 @else col-md-12 @endif">
        {{ $name }}
    </div>
</div>