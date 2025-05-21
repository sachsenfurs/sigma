@props([
    'noerror' => false
])
@include('layouts/head')

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap navbar2-wrapper">
            <div class="col-auto navbar2 collapse collapse-horizontal bg-dark-subtle h-100" id="navbarSupportedContent">
                <div class="d-flex flex-column flex-row flex-nowrap h-100 navbar2-items">
                    <div class="h-100 d-flex flex-column navbar2-padding">
                        <div class="navbar-brand">
                            <a href="{{ url('/') }}" class="d-flex align-items-center p-2 text-decoration-none">
                                <img src="/images/logo.png" alt="{{ config('app.name') }}">
                                <span class="text-light w-100 text-center fs-5 px-2 text-wrap fw-bold" style="word-wrap: anywhere">
                                    {{ app(\App\Settings\AppSettings::class)->event_name }}
                                </span>
                            </a>
                        </div>
                        <ul class="list-unstyled mb-auto nav-ul">
                            <li class="py-1">
                                <a @class(['btn btn-nav', 'active' => Route::is('home')]) href="{{ route('home') }}">
                                    <i class="bi bi-house icon-link"></i> {{ __('Home') }}
                                </a>
                            </li>

                            <li class="py-1">
                                <a @class(['btn btn-nav', 'active' => Route::is('schedule.listview')]) href="{{ route('schedule.listview') }}">
                                    <i class="bi bi-calendar2-range icon-link"></i> {{ __('Event Schedule') }}
                                </a>
                                <a @class(['btn btn-nav', 'active' => Route::is('schedule.calendarview')]) href="{{ route('schedule.calendarview') }}">
                                    <i class="bi bi-calendar-week icon-link"></i> {{ __("Calendar View") }}
                                </a>
                            </li>

                            <li>
                                <button @class(["btn btn-toggle align-items-center rounded py-2", 'collapsed' => !Route::is("sigs.*") && !Route::is("hosts.*") && !Route::is("locations.*")])
                                        data-bs-toggle="collapse" data-bs-target="#events-collapse" aria-expanded="{{ Route::is("sigs.*") || Route::is("hosts.*") || Route::is("locations.*") ? "true":"false" }}">
                                    <i class="bi bi-view-list icon-link"></i>
                                    {{ __("Events")}}
                                </button>
                                <div @class(["collapse", 'show' => Route::is("sigs.*") || Route::is("hosts.*") || Route::is("locations.*")]) id="events-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal">
                                        <li>
                                            <a @class(['rounded', 'active' => Route::is("hosts.index")]) href="{{ route("hosts.index")}}">
                                                <i class="bi bi-person-circle icon-link"></i> {{ __("Hosts") }}
                                            </a>
                                        </li>
                                        <li>
                                            <a @class(['rounded', 'active' => Route::is("locations.index")]) href="{{ route("locations.index") }}">
                                                <i class="bi bi-geo-alt icon-link"></i> {{ __("Locations") }}
                                            </a>
                                        </li>
                                        <li>
                                            <a @class(['rounded', 'active' => Route::is("sigs.index")]) href="{{ route("sigs.index") }}">
                                                <i class="bi bi-person-lines-fill icon-link"></i> {{ __("My Events") }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            @php(
                                $items = collect([
                                    app(\App\Settings\DealerSettings::class)->enabled ? __("Dealer's Den") : null,
                                    app(\App\Settings\ArtShowSettings::class)->enabled ? __("Art Show") : null,
                                ])
                                ->filter(fn($e) => $e != null)
                            )

                            @if($items->count() > 0)
                                <li class="py-1">
                                    <button @class(["btn btn-toggle rounded py-2", 'collapsed' => !Route::is("artshow.*") && !Route::is("dealers.*")])
                                            data-bs-toggle="collapse" data-bs-target="#ddas-collapse" aria-expanded="{{ Route::is("artshow.*") || Route::is("dealers.*") ? "true":"false" }}">
                                        <i class="bi bi-palette icon-link"></i>
                                        {{ $items->join(" & ") }}
                                    </button>

                                    <div @class(["collapse", 'show' => Route::is("artshow.*") || Route::is("dealers.*") ]) id="ddas-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal">
                                            @if(app(\App\Settings\DealerSettings::class)->enabled)
                                                <li>
                                                    <a @class(['btn btn-nav', 'active' => Route::is("dealers.index")]) href="{{ route("dealers.index")}}">
                                                        <i class="bi bi-cart icon-link"></i> {{ __("Dealer's Den Overview")}}
                                                    </a>
                                                </li>
                                            @endif
                                            @if(app(\App\Settings\ArtShowSettings::class)->enabled)
                                                <li>
                                                    <a @class(['btn btn-nav', 'active' => Route::is("artshow.index")]) href="{{ route("artshow.index") }}">
                                                        <i class="bi bi-easel icon-link"></i> {{ __("Art Show Overview")}}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            @guest
                                <li class="py-1">
                                    <a @class(['btn btn-nav', 'active' => Route::is('announcements')]) href="{{ route('announcements') }}">
                                        <div class="d-inline-flex w-100">
                                            <div class="col-auto">
                                                <i class="bi bi-megaphone icon-link"></i> {{ __('Announcements') }}
                                            </div>
                                            @cache('announcements_count', 300)
                                                @php($count = \App\Models\Post\Post::recent()->count())
                                                @if($count)
                                                    <div class="col ml-auto">
                                                        <div class="text-end ps-1"><span class="badge bg-dark border border-dark-subtle">{{ $count }}</span></div>
                                                    </div>
                                                @endif
                                            @endcache
                                        </div>
                                    </a>
                                </li>
                            @endguest

                            @auth
                                <li class="py-1">
                                    <a @class(['btn btn-nav', 'active' => Route::is("notifications.*")]) href="{{ route('notifications.index') }}">
                                        <div class="d-inline-flex w-100">
                                            <div class="col-auto flex-shrink-1">
                                                <i @class([ "bi icon-link", "bi-bell" ])></i>
                                                {{ __('Notifications') }}
                                            </div>
                                            @if(!Route::is("notifications.*"))
                                                <div class="col ml-auto text-end">
                                                    @php($count = \App\Models\Post\Post::recent()->count())
                                                    @if($count)
                                                        <span class="badge bg-dark border border-dark-subtle">{{ $count }}</span>
                                                    @endif

                                                    @php($count = auth()->user()->unreadNotifications->count())
                                                    @if($count)
                                                        <span class="badge bg-danger border border-danger-subtle">{{ $count }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                                @if(app(\App\Settings\ChatSettings::class)->enabled)
                                    <li class="py-1">
                                        <a @class(['btn btn-nav', 'active' => Route::is("chats.*")]) href="{{ route('chats.index') }}">
                                            @php($count = auth()->user()->unread_messages_count)
                                            <div class="d-inline-flex w-100">
                                                <div class="col-auto flex-shrink-1">
                                                    <i @class([ "bi  icon-link", "bi-mailbox2" => $count == 0, "bi-mailbox2-flag" => $count > 0 ])></i>
                                                    {{ __('Messages') }}
                                                </div>
                                                @if($count)
                                                    <div class="col ml-auto text-end">
                                                        <span class="badge bg-danger border border-danger">{{ $count }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endauth

                            @if(app(\App\Settings\AppSettings::class)->lost_found_enabled)
                                <li class="py-1">
                                    <a @class(['btn btn-nav', 'active' => Route::is('lostfound.index')]) href="{{ route('lostfound.index') }}">
                                        <i class="bi bi-box2 icon-link"></i> {{ __('Lost & Found') }}
                                    </a>
                                </li>
                            @endif
                        </ul>

                        @guest
                            <a class="p-3 fs-5" href="{{ route('oauthlogin_regsys') }}">Login</a>
                        @else
                            <div class="dropdown m-3 user-select-none">
                                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle text-wrap" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->avatar_thumb)
                                        <img src="{{ Auth::user()->avatar_thumb }}" alt="" width="32" height="32" class="rounded-circle me-2">
                                    @endif
                                    <strong>{{ Auth::user()->name }}</strong>
                                    <span class="text-muted px-1 ps-2 small">(#{{ auth()->user()->reg_id }})</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
                                    @if(Auth::user()->canAccessPanel(\Filament\Facades\Filament::getPanel()))
                                        <li>
                                            <a class="dropdown-item" href="{{ \Filament\Facades\Filament::getUrl() }}">
                                                {{ __('Administration') }}
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user-settings.edit') }}">
                                            {{ __('User Settings') }}
                                        </a>
                                    </li>
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
                            </div>
                        @endguest

                        <button class="p-3 nav-link dropdown-toggle rounded collapsed text-start d-flex align-items-center" data-bs-toggle="dropdown" data-bs-target="#home-collapse" aria-expanded="false">
                            <x-flag :language="App::getLocale()" height="1em" />
                        </button>

                        <ul class="dropdown-menu">
                            @foreach (config('app.locales') as $locale => $name)
                                @if (App::getLocale() != $locale)
                                    <li>
                                        <a class="dropdown-item align-items-center" href="{{ route('lang.set', $locale) }}">
                                            <x-flag :language="$locale" height="1em" />
                                            <span class="align-middle mx-1">{{ $name }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-auto flex-grow-1 flex-shrink-1 p-0 main-col d-grid">
                <nav class="navbar navbar-expand-lg shadow-sm bg-dark d-lg-none">
                    <div class="container-fluid text-wrap flex-nowrap">
                        <a class="navbar-brand m-0" href="{{ url('/') }}">
                            <img src="/images/logo.png" alt="{{ config('app.name') }}">
                        </a>
                        <span class="px-2 text-break text-center fs-5 d-none d-md-block">
                            @yield('title', __('Home'))
                        </span>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </nav>
                <main class="d-block py-4 px-lg-3 mb-auto">
                    @if (!$noerror AND ($errors->any() OR session('error')))
                        @foreach ([...$errors->all(), session()->get('error')] as $error)
                            @if($error)
                                <div class="container">
                                    <div class="alert alert-danger fs-4" role="alert">
                                        {{ $error }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    @if (session('success'))
                        <div class="container">
                            <div class="alert alert-success fs-4" role="alert">
                                {{ session()->get('success') }}
                            </div>
                        </div>
                    @endif
                    @yield('content')
                </main>
                <div class="container-lg align-content-end">
                    <x-footer.footer/>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{--  Just to make sure Alpine.js is loaded on every page without Livewire components  --}}
    @livewireScripts
    @livewireStyles
</body>

</html>
