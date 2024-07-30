<footer {{ $attributes->class(['d-flex flex-wrap justify-content-between align-items-start py-3 my-2 border-top']) }}>
    <p class="col mb-0 text-body-secondary text-nowrap p-0 px-2">
        <span style="cursor: pointer; font-size:0.75em" data-bs-toggle="modal" data-bs-target="#creditsModal">&Sigma; - Credits</span>
    </p>

    @cache('footer')
    @foreach(\App\Models\Info\Social::footerText()->get() AS $social)
        <a href="{{ $social->link_localized }}" class="col text-center text-body text-nowrap text-muted text-decoration-none p-1" style="font-size: 0.9em">
            {{ $social->link_name_localized }}
        </a>
    @endforeach

    <ul class="nav col justify-content-end list-unstyled d-flex px-2">
        @foreach(\App\Models\Info\Social::footerIcon()->get() AS $social)
            <li class="ms-3"><a class="text-body-secondary" target="_blank" href="{{ $social->link_localized }}">
                    <x-social-icon :icon="$social->icon"/>
                </a></li>
        @endforeach
    </ul>
    @endcache
</footer>
