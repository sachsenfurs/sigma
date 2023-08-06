@include("layouts/head")
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                        @auth
                            <!-- Visible in any case -->
                            <li><a class="nav-link {{ Route::is("public.tableview") ? "active" : "" }}" href="{{ route("public.tableview") }}">Timetable</a></li>
                            <!-- End visible in any case -->
                            @can('manage_events')
                                <li><a class="nav-link {{ Route::is("timetable.index") ? "active" : "" }}" href="{{ route("timetable.index") }}">Manage Timetable</a></li>
                                <li><a class="nav-link {{ Route::is("sigs.index") ? "active" : "" }}" href="{{ route("sigs.index") }}">SIGs</a></li>
                            @endcan
                            @can('manage_hosts')
                                <li><a class="nav-link {{ Route::is("hosts.index") ? "active" : "" }}" href="{{ route("hosts.index") }}">Hosts</a></li>
                            @endcan
                            @can('manage_locations')
                                <li><a class="nav-link {{ Route::is("locations.index") ? "active" : "" }}" href="{{ route("locations.index") }}">Locations</a></li>
                            @endcan

                        @else
                            <li><a class="nav-link {{ Route::is("public.tableview") ? "active" : "" }}" href="{{ route("public.tableview") }}">Timetable</a></li>
                        @endauth
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
