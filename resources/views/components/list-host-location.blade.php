@props([
    'title' => "",
    'link' => "",
    'avatar' => null,
    'instance' => null,
    'hide' => false,
    'edit_link' => "",
])
@if(!$hide OR auth()?->user()?->can("update", $instance))
    <div class="card mt-3">
        <div @class(['row g-0', 'bg-secondary' => $hide])>
            @if($avatar)
                <div class="col-md-4" style="max-height: 100%">
                    <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded-start d-none d-md-block placeholder" style="object-fit: cover; max-height: 15em" alt="" loading="lazy">
                    <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded-top d-md-none placeholder" style="object-fit: cover; max-height: 12em" alt="" loading="lazy">
                </div>
            @endif
            <div class="@if($avatar)col-md-6 @else col-md-10 @endif">
                <div class="card-body">
                    <h2 class="card-title">
                        @if($instance instanceof \App\Models\SigHost)
                            <i class="bi bi-person-circle"></i>
                        @endif
                        @if($instance instanceof \App\Models\SigLocation)
                            <i class="bi bi-geo-alt"></i>
                        @endif
                        {{ $title }}
                    </h2>
                    <p class="card-text">
                        <x-markdown>
                            {{ $slot }}
                        </x-markdown>
                    </p>
                    <a href="{{ $link }}" class="stretched-link"> </a>
                </div>

            </div>
            <div class="col-md-2 align-middle">
                <div class="container d-flex h-100 w-100">
                    <div class="align-self-center w-100 m-3" style="text-align: right">
                        <div class="display-6">{{ $instance?->getPublicSigEventCount() }}</div>
                        {{ Str::plural("Event", $instance?->getPublicSigEventCount()) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can("update", $instance)
        <div class="card-footer">
            <div class="w-100 container p-2">
                <a href="{{ $edit_link }}" class="">
                    <i class="bi bi-pen"></i> {{ __("Edit") }}
                </a>
            </div>
        </div>
    @endcan
@endif
