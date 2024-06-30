@props([
    'noerror' => false
])
@include('layouts/head')

<body>
    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/logo.png" alt="{{ config('app.name') }}">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li>
                        <a @class(['nav-link px-3', 'active' => Route::is('schedule.listview')]) href="{{ route('schedule.listview') }}">
                            <i class="bi bi-calendar-week"></i> {{ __('Event Schedule') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a @class(["nav-link dropdown-toggle px-3", 'active' => Route::is("sigs.*") || Route::is("hosts.*") || Route::is("locations.*")]) href="#" id="sigMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-view-list"></i> {{ __("Events")}}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="sigMenu">
                            <li>
                                <a @class(['dropdown-item', 'active' => Route::is("hosts.index")]) href="{{ route("hosts.index")}}">
                                    <i class="bi bi-person-circle"></i> {{ __("Hosts") }}
                                </a>
                            </li>
                            <li>
                                <a @class(['dropdown-item', 'active' => Route::is("locations.index")]) href="{{ route("locations.index") }}">
                                    <i class="bi bi-geo-alt"></i> {{ __("Locations") }}
                                </a>
                            </li>
                            <li>
                                <a @class(['dropdown-item', 'active' => Route::is("sigs.index")]) href="{{ route("sigs.index") }}">
                                    <i class="bi bi-person-lines-fill"></i> {{ __("My Events") }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    @can("viewAny", \App\Models\LostFoundItem::class)
                        <li>
                            <a @class(['nav-link px-3', 'active' => Route::is('lostfound.index')]) href="{{ route('lostfound.index') }}">
                                <i class="bi bi-box2"></i> {{ __('Lost & Found') }}
                            </a>
                        </li>
                    @endcan

                    @php(
                        $items = collect([
                            app(\App\Settings\DealerSettings::class)->enabled ? __("Dealer's Den") : null,
                            app(\App\Settings\ArtShowSettings::class)->enabled ? __("Art Show") : null,
                        ])
                        ->filter(fn($e) => $e != null)
                    )
                    @if($items->count() > 0)
                        <li class="nav-item dropdown">
                            <a @class(["nav-link dropdown-toggle px-3", 'active' => Route::is("artshow.*")]) href="#" id="artMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-palette"></i> {{ $items->join(" & ") }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="artMenu">
                                @if(app(\App\Settings\DealerSettings::class)->enabled)
                                    <li>
                                        <a @class(['dropdown-item', 'active' => Route::is("dealers.index")]) href="{{ route("dealers.index")}}">
                                            <i class="bi bi-cart"></i> {{ __("Dealers Den Overview")}}
                                        </a>
                                    </li>
                                @endif
                                @if(app(\App\Settings\ArtShowSettings::class)->enabled)
                                    <li>
                                        <a @class(['dropdown-item', 'active' => Route::is("artshow.index")]) href="{{ route("artshow.index") }}">
                                            <i class="bi bi-easel"></i> {{ __("Art Show Overview")}}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-link px-3">
                            <a href="{{ route('notifications.index') }}">
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <i class="bi bi-bell-fill"></i>
                                @else
                                    <i class="bi bi-bell"></i>
                                @endif
                            </a>
                        </li>
                        <li class="nav-link px-3 ">
                            <a href="{{ route('chats.index') }}">
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <i class="bi bi-mailbox2-flag"></i>
                                @else
                                    <i class="bi bi-mailbox2"></i>
                                @endif
                            </a>
                        </li>
                    @endauth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img class="align-middle me-2" style="height: 1em; margin-top: -4px"
                                src="/icons/{{ App::getLocale() }}-flag.svg" alt="[{{ App::getLocale() }}]">
                        </a>
                        <ul class="dropdown-menu">
                            @foreach (config('app.locales') as $locale => $name)
                                @if (App::getLocale() != $locale)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('lang.set', $locale) }}">
                                            <i class="bi">
                                                <img class="" src="/icons/{{ $locale }}-flag.svg" style="height: 1em; margin-top: -2px" alt="[{{ $locale }}]">
                                            </i>
                                            <span class="align-middle mx-1">{{ $name }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>


                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle align-self-center" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    @if(Auth::user()->canAccessPanel(\Filament\Facades\Filament::getPanel()))
                                        <a class="dropdown-item" href="{{ \Filament\Facades\Filament::getUrl() }}">
                                            {{ __('Administration') }}
                                        </a>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user-settings.edit') }}">
                                            {{ __("User Settings") }}
                                        </a>
                                    </li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if (!$noerror AND $errors->any())
            @foreach ($errors->all() as $error)
                <div class="container">
                    <div class="alert alert-danger" role="alert">
                        <h4>{{ $error }}</h4>
                    </div>
                </div>
            @endforeach
        @endif
        @if (session('error'))
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-title">{{ session()->get('error') }}</h4>
                </div>
            </div>
        @endif
        @if (session('success'))
            <div class="container">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-title">{{ session()->get('success') }}</h4>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
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
                        <li class="ms-3"><a class="text-body-secondary" target="_blank" href="{{ $social->link_localized }}"><x-social-icon :icon="$social->icon" /></a></li>
                    @endforeach
                </ul>
            @endcache
        </footer>
        <!-- Modal -->
        <div class="modal fade" id="creditsModal" tabindex="-1" aria-labelledby="creditsModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h1 class="modal-title fs-5" id="creditsModalLabel">Credits</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <a class="icon-link" href="https://github.com/sachsenfurs/sigma" target="_blank"><i class="bi bi-github"></i> SIGMA on GitHub</a>
                            <ul>
                                <li>
                                    <a href="https://fullcalendar.io/" class="text-decoration-none" target="_blank">FullCalendar</a> -
                                    <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank" class="text-decoration-none">Non-Commercian License</a>
                                </li>
                                <li>
                                    <a href="https://www.cheetagonzita.com/" class="text-decoration-none" target="_blank">Artworks by Zita</a>
                                </li>
                            </ul>




                        </div>
                        <h5>Contributors</h5>
                        <div class="d-flex flex-wrap pb-3 gap-2">
                            <a href="https://github.com/Kidran" target="_blank" class="text-decoration-none">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                    <img class="rounded-circle me-1" width="24" height="24" src="https://avatars.githubusercontent.com/u/63103731?s=24" loading="lazy" alt="">
                                    Kidran
                                </span>
                            </a>

                            <a href="https://github.com/Lytrox" target="_blank" class="text-decoration-none">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                    <img class="rounded-circle me-1" width="24" height="24" src="https://avatars.githubusercontent.com/u/9468383?s=24" loading="lazy" alt="">
                                    Lytrox
                                </span>
                            </a>

                            <a href="https://github.com/CyberSpaceDragon" target="_blank" class="text-decoration-none">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                    <img class="rounded-circle me-1" width="24" height="24" src="https://avatars.githubusercontent.com/u/58507164?s=24" loading="lazy" alt="">
                                    CyberSpaceDragon
                                </span>
                            </a>

                            <a href="https://github.com/kacecfox" target="_blank" class="text-decoration-none">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                    <img class="rounded-circle me-1" width="24" height="24" src="https://avatars.githubusercontent.com/u/30048300?s=24" loading="lazy" alt="">
                                    Kacec
                                </span>
                            </a>

                            <a href="https://github.com/d3xter-dev" target="_blank" class="text-decoration-none">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                    <img class="rounded-circle me-1" width="24" height="24" src="https://avatars.githubusercontent.com/u/62628584?s=24" loading="lazy" alt="">
                                    Dexter
                                </span>
                            </a>
                            <a href="https://github.com/Kenthanar" target="_blank" class="text-decoration-none">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                    <img class="rounded-circle me-1" width="24" height="24" src="https://avatars.githubusercontent.com/u/100375107?s=24" loading="lazy" alt="">
                                    Kenthanar
                                </span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

    </div>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{--  Just to make sure Alpine.js is loaded on every page without Livewire components  --}}
    @livewireScripts
</body>

</html>
