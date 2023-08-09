@include("layouts/head")
<body>
    <div id="app">
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
                            <a class="nav-link px-3 {{ Route::is("public.tableview") ? "active" : "" }}" href="{{ route("public.tableview") }}">
                                <i class="bi bi-calendar-week"></i> Timetable
                            </a>
                        </li>
                        <!-- End visible in any case -->
                        @canany(["manage_events", "manage_locations"])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle px-3" href="#" id="adminDropdownMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear"></i> Administration
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="adminDropdownMenu">
                                    @can("manage_events")
                                        <li>
                                            <a class="dropdown-item {{ Route::is("timetable.index") ? "active" : "" }}" href="{{ route("timetable.index") }}">
                                                <i class="bi bi-list"></i> Manage Timetable
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ Route::is("sigs.index") ? "active" : "" }}" href="{{ route("sigs.index") }}">
                                                <i class="bi bi-easel"></i> SIGs
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        <li>
                            <a class="nav-link px-3 {{ Route::is("hosts.index") ? "active" : "" }}" href="{{ route("hosts.index") }}">
                                <i class="bi bi-person-circle"></i> Hosts
                            </a>
                        </li>

                        <li>
                            <a class="nav-link px-3 {{ Route::is("locations.index") ? "active" : "" }}" href="{{ route("locations.index") }}">
                                <i class="bi bi-geo-alt"></i> Locations
                            </a>
                        </li>


                        @if (auth()?->user()?->isSigHost())
                            <li>
                                <a class="nav-link px-3 {{ Route::is("mysigs.index") ? "active" : "" }}" href="{{ route("mysigs.index") }}">
                                    <i class="bi bi-view-list"></i> My Events
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @can('manage_users')
                                         <a class="dropdown-item" href="{{ route('users.index') }}">Manage Users</a>
                                    @endcan
                                    @can('manage_settings')
                                        <a class="dropdown-item" href="{{ route('user-roles.index') }}">Manage Roles</a>
                                    @endcan

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
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
    </div>
</body>
</html>
