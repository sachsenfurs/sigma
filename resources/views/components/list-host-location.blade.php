@props([
    'title' => "",
    'link' => "",
    'avatar' => null,
    'instance' => null,
    'hide' => false,
])
@if(!$hide OR auth()?->user()?->can("update", $instance))
    <div class="card mt-3">
        <div @class(['row g-0', 'bg-secondary' => $hide])>
            @if($avatar)
                <div class="col-md-4" style="max-height: 100%">
                    <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded-start d-none d-md-block" style="object-fit: cover; max-height: 15em" alt="" loading="lazy">
                    <img src="{{ $avatar }}" class="img-fluid h-100 w-100 rounded-top d-md-none" style="object-fit: cover; max-height: 12em" alt="" loading="lazy">
                </div>
            @endif
            <div @class(["col-md-6" => $avatar, "col-md-10" => !$avatar])>
                <div class="card-body">
                    <h2 class="card-title d-flex">
                        @if($instance instanceof \App\Models\SigHost)
                            <i class="bi bi-person-circle pe-2"></i>
                        @endif
                        @if($instance instanceof \App\Models\SigLocation)
                            <i class="bi bi-geo-alt pe-2"></i>
                        @endif
                        {{ $title }}
                    </h2>
                    <div class="card-text">
                        {{ $slot != $title ? $slot : "" }}
                    </div>
                    <a href="{{ $link }}" class="stretched-link"> </a>
                </div>
            </div>
            @if(!$instance?->essential)
                <div class="col-md-2 align-middle">
                    <div class="container d-flex h-100 w-100">
                        <div class="align-self-center w-100 m-3" style="text-align: right">
                            <div class="display-6">{{ $instance?->public_sig_event_count }}</div>
                            {{ Str::plural("Event", $instance?->public_sig_event_count) }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
