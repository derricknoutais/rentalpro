<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app2.css') }}" rel="stylesheet">
    {{-- Font Awesome  --}}
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous"> --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Rental Pro">
    <link rel="apple-touch-icon" href="/images/icons/app-icon-256x256.png" size="256x256">
</head>
<body >
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                @auth
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ Auth::user()->compagnie->nom }}
                </a>
                @endauth
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item mx-3">
                                <a href="/contrats" class="nav-link">
                                    <i class="fas fa-file-contract"></i>
                                    Contrats
                                </a>
                            </li>
                            <li class="nav-item mx-3">
                                <a href="/clients" class="nav-link">
                                    <i class="fas fa-user-tie"></i>
                                    Clients
                                </a>
                            </li>
                            @if (Auth::user()->compagnie->type === 'véhicule')
                                <li class="nav-item mx-3">
                                    <a href="/voitures" class="nav-link">
                                        <i class="fas fa-car"></i>
                                        Voitures
                                    </a>
                                </li>
                                <li class="nav-item mx-3">
                                    <a href="/maintenances" class="nav-link">
                                        <i class="fas fa-tools"></i>
                                        Maintenances
                                    </a>
                                </li>
                                <li class="nav-item dropdown mx-3">
                                    <a class="nav-link dropdown-toggle" role="button" href="#" data-toggle="dropdown">
                                        <i class="fas fa-chart-bar"></i>
                                        Reporting
                                        <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="/reporting">General
                                        </a>
                                        <a href="/reporting/voitures" class="dropdown-item">Par Voiture</a>
                                        <a href="/reporting/par-client/reporting/par-voiture/reporting/par-voiture" class="dropdown-item">Par Client</a>
                                    </div>

                                </li>
                            @endif
                        </ul>
                    @endauth


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else

                            <li class="nav-item dropdown mr-3">
                                <a id="navbarDropdown" class="nav-link fa-stack" href="#" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="fa-stack has-badge" :data-count="notifications.length">
                                      <i class="fa  fa-stack-2x"></i>
                                      <i class="fa fa-bell fa-stack-1x"></i>
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" v-if="notifications">
                                    <a :href="notif.lien" class="dropdown-item" v-for="notif in notifications">@{{ notif.message }}</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}

                                    <span class="caret">

                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <a href="/mes-paramètres" class="dropdown-item">Settings</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @include('flash::message')
        <main>
            @yield('content')
        </main>
    </div>
    {{-- <div id="app" class="tw-bg-screen">

        <div class="tw-w-1/12 tw-h-screen tw-border tw-border-gray-300">
            <ul>
                <li>kfljgfkldj</li>
                <li>kfljgfkldj</li>
                <li>kfljgfkldj</li>
                <li>kfljgfkldj</li>
                <li>kfljgfkldj</li>
            </ul>
        </div>

    </div> --}}

    @yield('js')
</body>
</html>
