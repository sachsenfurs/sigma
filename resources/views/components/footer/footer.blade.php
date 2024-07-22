<footer {{ $attributes->class(['d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top']) }}>
    <p class="col-4 mb-0 text-body-secondary">
        <span style="cursor: pointer; font-size:0.75em" data-bs-toggle="modal" data-bs-target="#creditsModal">&Sigma; - made by awesome people</span>
    </p>

    @cache('footer')
    @foreach(\App\Models\Info\Social::footerText()->get() AS $social)
        <a href="{{ $social->link_localized }}" class="col-4 text-center text-body text-decoration-none">
            {{ $social->link_name_localized }}
        </a>
    @endforeach

    <ul class="nav col-4 justify-content-end list-unstyled d-flex">
        @foreach(\App\Models\Info\Social::footerIcon()->get() AS $social)
            <li class="ms-3"><a class="text-body-secondary" target="_blank" href="{{ $social->link_localized }}">
                    <x-social-icon :icon="$social->icon"/>
                </a></li>
        @endforeach
    </ul>
    @endcache
</footer>
