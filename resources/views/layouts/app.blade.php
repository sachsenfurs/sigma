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
                    <!-- Visible in any case -->
                    <li>
                        <a class="nav-link px-3 {{ Route::is('public.listview') ? 'active' : '' }}"
                            href="{{ route('public.listview') }}">
                            <i class="bi bi-calendar-week"></i> {{ __('Event Schedule') }}
                        </a>
                    </li>
                    <!-- End visible in any case -->

                    <li>
                        <a class="nav-link px-3 {{ Route::is('hosts.index') ? 'active' : '' }}"
                            href="{{ route('hosts.index') }}">
                            <i class="bi bi-person-circle"></i> {{ __('Hosts') }}
                        </a>
                    </li>

                    <li>
                        <a class="nav-link px-3 {{ Route::is('locations.index') ? 'active' : '' }}"
                            href="{{ route('locations.index') }}">
                            <i class="bi bi-geo-alt"></i> {{ __('Locations') }}
                        </a>
                    </li>

                    <li>
                        <a class="nav-link px-3 {{ Route::is('lostfound.index') ? 'active' : '' }}"
                            href="{{ route('lostfound.index') }}">
                            {{ __('Lost & Found') }}
                        </a>
                    </li>

                    @if (auth()?->user()?->isSigHost())
                        <li>
                            <a class="nav-link px-3 {{ Route::is('mysigs.index') ? 'active' : '' }}"
                                href="{{ route('mysigs.index') }}">
                                <i class="bi bi-view-list"></i> {{ __('My Events') }}
                            </a>
                        </li>
                    @endif

                    @if (auth()?->user())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="SignInDropdownMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-briefcase"></i> {{ __("Sign Up's")}}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="SignInDropdownMenu">
                            <li>
                                <a class="dropdown-item {{ Route::is("artshow.index") ? "active" : "" }}" href="{{ route("artshow.index") }}">
                                    <i class="bi bi-cash-stack"></i> {{ __("Artshow Item Sign Up")}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ Route::is("dealersden.index") ? "active" : ""}}" href="{{ route("dealersden.index")}}">
                                    <i class="bi bi-cash-stack"></i> {{ __("Dealers Den Sign Up")}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ Route::is("sigs.signup.create") ? "activ" : ""}}" href="{{ route("sigs.signup.create")}}">
                                    <i class="bi bi-chat-left"></i> {{ __("SIG Sign Up")}}
                                </a>
                            </li>
                        </ul>
                    </li>

                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
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
                                            <i class="bi"><img class=""
                                                    src="/icons/{{ $locale }}-flag.svg"
                                                    style="height: 1em; margin-top: -2px"
                                                    alt="[{{ $locale }}]"></i>
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
        @if ($errors->any())
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

            <a href="//sachsenfurs.de/east" class="col-4 text-center text-body text-decoration-none">
                EAST Website
            </a>

            <ul class="nav col-4 justify-content-end list-unstyled d-flex">
                <li class="ms-3"><a class="text-body-secondary" target="_blank" href="//t.me/eastinfo{{ (\Illuminate\Support\Facades\App::getLocale() == "en") ? "_en" : "" }}"><i class="bi bi-telegram"></i></a></li>
                <li class="ms-3"><a class="text-body-secondary" target="_blank" href="//x.com/sachsenfurs"><i class="bi bi-twitter-x"></i></a></li>
                <li class="ms-3"><a class="text-body-secondary" target="_blank" href="//instagram.com/sachsenfurs"><i class="bi bi-instagram"></i></a></li>
            </ul>
        </footer>
        <!-- Modal -->
        <div class="modal fade" id="creditsModal" tabindex="-1" aria-labelledby="creditsModallLabel" aria-hidden="true">
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
