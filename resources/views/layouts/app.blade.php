@include("layouts/head")
<body>
    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/logo.png" alt="{{ config('app.name' ) }}">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <!-- Visible in any case -->
                    <li>
                        <a class="nav-link px-3 {{ Route::is("public.listview") ? "active" : "" }}" href="{{ route("public.listview") }}">
                            <i class="bi bi-calendar-week"></i> {{ __("Event Schedule") }}
                        </a>
                    </li>
                    <!-- End visible in any case -->
                    @canany(["manage_events", "post"])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3" href="#" id="adminDropdownMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear"></i> {{ __("Administration") }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdownMenu">
                                @can("manage_events")
                                    <li>
                                        <a class="dropdown-item {{ Route::is("timetable.index") ? "active" : "" }}" href="{{ route("timetable.index") }}">
                                            <i class="bi bi-list"></i> {{ __("Manage Event Schedule") }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ Route::is("sigs.index") ? "active" : "" }}" href="{{ route("sigs.index") }}">
                                            <i class="bi bi-easel"></i> SIGs
                                        </a>
                                    </li>
                                @endcan
                                @can("post")
                                        <li>
                                            <a class="dropdown-item {{ Route::is("posts.index") ? "active" : "" }}" href="{{ route("posts.index") }}">
                                                <i class="bi bi-megaphone"></i> @lang("Announcements")
                                            </a>
                                        </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    <li>
                        <a class="nav-link px-3 {{ Route::is("hosts.index") ? "active" : "" }}" href="{{ route("hosts.index") }}">
                            <i class="bi bi-person-circle"></i> {{ __("Hosts") }}
                        </a>
                    </li>

                    <li>
                        <a class="nav-link px-3 {{ Route::is("locations.index") ? "active" : "" }}" href="{{ route("locations.index") }}">
                            <i class="bi bi-geo-alt"></i> {{ __("Locations") }}
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="DdasDropdownMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-geo-alt"></i> {{ __("Anmeldung DD & AS")}}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="DdasDropdownMenu">
                            <li>
                                <a class="dropdown-item {{ Route::is("artshow.index") ? "active" : "" }}" href="{{ route("artshow.index") }}">
                                    <i class="bi bi-list"></i> {{ __("ArtShow")}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ Route::is("dealersden.index") ? "active" : ""}}" href="{{ route("dealersden.index")}}">
                                    <i class="bi bi-list"></i> {{ __("Dealer's Den")}}
                                </a>
                            </li>
                        </ul>
                    </li>


                    @if (auth()?->user()?->isSigHost())
                        <li>
                            <a class="nav-link px-3 {{ Route::is("mysigs.index") ? "active" : "" }}" href="{{ route("mysigs.index") }}">
                                <i class="bi bi-view-list"></i> {{ __("My Events") }}
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="align-middle me-2" style="height: 1em; margin-top: -4px" src="/icons/{{ App::getLocale() }}-flag.svg" alt="[{{ App::getLocale() }}]">
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(config("app.locales") AS $locale=>$name)
                                @if(App::getLocale() != $locale)
                                    <li>
                                        <a class="dropdown-item" href="{{ route("lang.set", $locale) }}">
                                            <i class="bi"><img class="" src="/icons/{{ $locale }}-flag.svg" style="height: 1em; margin-top: -2px" alt="[{{ $locale }}]"></i>
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
                            <a id="navbarDropdown" class="nav-link dropdown-toggle align-self-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @canany(['manage_users', 'manage_settings'])
                                    @can('manage_users')
                                         <li>
                                             <a class="dropdown-item" href="{{ route('users.index') }}">
                                                 {{ __("Manage Users") }}
                                             </a>
                                         </li>
                                    @endcan
                                    @can('manage_settings')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('user-roles.index') }}">
                                                {{ __("Manage Roles") }}
                                            </a>
                                        </li>
                                    @endcan

                                    <li><hr class="dropdown-divider"></li>

                                @endcanany
                                <li>
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
        @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="container">
                    <div class="alert alert-danger" role="alert">
                        <h4>{{ $error }}</h4>
                    </div>
                </div>
            @endforeach
        @endif
        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-title">{{session()->get("error")}}</h4>
                </div>
            </div>

        @endif
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-title">{{session()->get("success")}}</h4>
                </div>
            </div>

        @endif
        @yield('content')
    </main>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</body>
</html>
