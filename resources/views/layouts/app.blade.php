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
                            <a href="{{ url('/') }}" class="d-flex align-items-center p-3 text-decoration-none">
                                <img src="/images/logo.png" alt="{{ config('app.name') }}">
                            </a>
                        </div>
                        <ul class="list-unstyled mb-auto nav-ul">
                            <li class="py-2">
                                <a @class(['btn btn-nav rounded', 'active' => Route::is('home')]) href="{{ route('home') }}">
                                    <i class="bi bi-house"></i> {{ __('Home') }}
                                </a>
                            </li>

                            <li class="py-2">
                                <a @class(['btn btn-nav rounded', 'active' => Route::is('schedule.listview')]) href="{{ route('schedule.listview') }}">
                                    <i class="bi bi-calendar2-range"></i> {{ __('Event Schedule') }}
                                </a>
                                <a @class(['btn btn-nav rounded', 'active' => Route::is('schedule.calendarview')]) href="{{ route('schedule.calendarview') }}">
                                    <i class="bi bi-calendar-week"></i> {{ __("Calendar View") }}
                                </a>
                            </li>

                            <li>
                                <button @class(["btn btn-toggle align-items-center rounded", 'collapsed' => !Route::is("sigs.*") && !Route::is("hosts.*") && !Route::is("locations.*")])
                                        data-bs-toggle="collapse" data-bs-target="#events-collapse" aria-expanded="{{ Route::is("sigs.*") || Route::is("hosts.*") || Route::is("locations.*") ? "true":"false" }}">
                                    <i class="bi bi-view-list"></i>
                                    {{ __("Events")}}
                                </button>
                                <div @class(["collapse", 'show' => Route::is("sigs.*") || Route::is("hosts.*") || Route::is("locations.*")]) id="events-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal">
                                        <li>
                                            <a @class(['rounded', 'active' => Route::is("hosts.index")]) href="{{ route("hosts.index")}}">
                                                <i class="bi bi-person-circle"></i> {{ __("Hosts") }}
                                            </a>
                                        </li>
                                        <li>
                                            <a @class(['rounded', 'active' => Route::is("locations.index")]) href="{{ route("locations.index") }}">
                                                <i class="bi bi-geo-alt"></i> {{ __("Locations") }}
                                            </a>
                                        </li>
                                        <li>
                                            <a @class(['rounded', 'active' => Route::is("sigs.index")]) href="{{ route("sigs.index") }}">
                                                <i class="bi bi-person-lines-fill"></i> {{ __("My Events") }}
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
                                <li class="py-2">
                                    <button @class(["btn btn-toggle rounded", 'collapsed' => !Route::is("artshow.*") && !Route::is("dealers.*")])
                                            data-bs-toggle="collapse" data-bs-target="#ddas-collapse" aria-expanded="{{ Route::is("artshow.*") || Route::is("dealers.*") ? "true":"false" }}">
                                        <i class="bi bi-palette"></i>
                                        {{ $items->join(" & ") }}
                                    </button>

                                    <div @class(["collapse", 'show' => Route::is("artshow.*") || Route::is("dealers.*") ]) id="ddas-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal">
                                            @if(app(\App\Settings\DealerSettings::class)->enabled)
                                                <li>
                                                    <a @class(['btn btn-nav', 'active' => Route::is("dealers.index")]) href="{{ route("dealers.index")}}">
                                                        <i class="bi bi-cart"></i> {{ __("Dealers Den Overview")}}
                                                    </a>
                                                </li>
                                            @endif
                                            @if(app(\App\Settings\ArtShowSettings::class)->enabled)
                                                <li>
                                                    <a @class(['btn btn-nav', 'active' => Route::is("artshow.index")]) href="{{ route("artshow.index") }}">
                                                        <i class="bi bi-easel"></i> {{ __("Art Show Overview")}}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            <li class="py-2">
                                <a @class(['btn btn-nav rounded d-flex', 'active' => Route::is('announcements')]) href="{{ route('announcements') }}">
                                    <i class="bi bi-megaphone"></i> {{ __('Announcements') }}
                                    @cache('announcements_count', 300)
                                        @php($count = \App\Models\Post\Post::where('created_at', '>=', \Carbon\Carbon::now()->subHours(2))->count())
                                        @if($count)
                                            <div class="text-end w-100"><span class="badge bg-dark">{{ $count }}</span></div>
                                        @endif
                                    @endcache
                                </a>
                            </li>


                            <li class="py-2">
                                <a @class(['btn btn-nav', 'active' => Route::is('lostfound.index')]) href="{{ route('lostfound.index') }}">
                                    <i class="bi bi-box2"></i> {{ __('Lost & Found') }}
                                </a>
                            </li>
                        </ul>

                        @guest
                            <a class="p-3 fs-5" href="/login">Login</a>
                        @else
                            <div class="dropdown m-3">
                                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->avatar_thumb)
                                        <img src="{{ Auth::user()->avatar_thumb }}" alt="" width="32" height="32" class="rounded-circle me-2">
                                    @endif
                                    <strong>{{ Auth::user()->name }}</strong>
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


                        <button class="p-3 nav-link dropdown-toggle rounded collapsed text-start" data-bs-toggle="dropdown" data-bs-target="#home-collapse" aria-expanded="false">
                            <img class="align-middle me-2" style="height: 1em; margin-top: -4px"
                                 src="/icons/{{ App::getLocale() }}-flag.svg" alt="[{{ App::getLocale() }}]">
                        </button>

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

                    </div>

                </div>
            </div>
            <div class="col-auto flex-grow-1 flex-shrink-1 p-0 main-col d-grid">
                <nav class="navbar navbar-expand-lg shadow-sm bg-dark d-lg-none">
                    <div class="container-fluid">
                        <a class="navbar-brand m-0" href="{{ url('/') }}">
                            <img src="/images/logo.png" alt="{{ config('app.name') }}">
                        </a>
                        @yield('title')
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </nav>
                <main class="d-block py-4 px-lg-3 mb-auto">
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
                <div class="container align-content-end">
                    <x-footer.footer/>
                    <x-footer.credits-modal />
                </div>
            </div>
        </div>
    </div>



    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{--  Just to make sure Alpine.js is loaded on every page without Livewire components  --}}
    @livewireScripts
</body>

</html>


{{--            <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white" >--}}
{{--                <a href="/" class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">--}}
{{--                    <svg class="bi me-2" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>--}}
{{--                    <span class="fs-5 fw-semibold">List group</span>--}}
{{--                </a>--}}
{{--                <div class="list-group list-group-flush border-bottom scrollarea">--}}
{{--                    @for($i=0;$i<10;$i++)--}}
{{--                        <a href="#" class="list-group-item list-group-item-action py-3 lh-tight">--}}
{{--                            <div class="d-flex w-100 align-items-center justify-content-between">--}}
{{--                                <strong class="mb-1">List group item heading</strong>--}}
{{--                                <small>Wed</small>--}}
{{--                            </div>--}}
{{--                            <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>--}}
{{--                        </a>--}}
{{--                    @endfor--}}
{{--                </div>--}}
{{--            </div>--}}
