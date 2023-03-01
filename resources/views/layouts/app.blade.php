<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.css">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app2.css') }}" rel="stylesheet">
    @livewireStyles
    {{-- Font Awesome  --}}
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous"> --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Rental Pro">
    <link rel="apple-touch-icon" href="/images/icons/app-icon-256x256.png" size="256x256">
</head>
<body>

    <div id="app" class="min-h-screen bg-gray-100" >

        <nav class="bg-gray-800" x-show="false">
            <div class="px-2 mx-auto max-w-7xl sm:px-4 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div class="flex items-center px-2 lg:px-0">
                        <div class="flex-shrink-0">
                            <img class="block w-auto h-8" src="/img/logo-simple.png" alt="Workflow">
                        </div>
                        <div class="hidden lg:block lg:ml-6">
                            <div class="flex space-x-4">
                                @auth
                                    <a href="/dashboard"
                                        class="px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-md">Tableau de Bord</a>
                                    <a href="/contrats"
                                        class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Contrats</a>
                                    <a href="/clients"
                                        class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Clients</a>
                                    <a href="/voitures"
                                        class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Voitures</a>
                                    <a href="/maintenances"
                                        class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Maintenances</a>
                                    <a href="/mes-paramètres"
                                        class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Paramètres</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <div class="flex lg:hidden">
                        <!-- Mobile menu button -->
                        <button type="button"
                            class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <!--
                                Icon when menu is closed.
                                Heroicon name: outline/menu
                                Menu open: "hidden", Menu closed: "block"
                            -->
                            <svg class="block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!--
                                Icon when menu is open.

                                Heroicon name: outline/x

                                Menu open: "block", Menu closed: "hidden"
                            -->
                            <svg class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="hidden lg:block lg:ml-4">
                        <div class="flex items-center">
                            <button type="button"
                                class="flex-shrink-0 p-1 text-gray-400 bg-gray-800 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                                <span class="sr-only">View notifications</span>
                                <!-- Heroicon name: outline/bell -->
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <!-- Profile dropdown -->
                            <div class="relative flex-shrink-0 ml-4">
                                <div>
                                    <button type="button"
                                        @click="toggleUserOptions"
                                        class="flex text-sm text-white bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="w-8 h-8 rounded-full"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                            alt="">
                                    </button>
                                </div>
                                <!--
                                    Dropdown menu, show/hide based on menu state.

                                    Entering: "transition ease-out duration-100"
                                        From: "transform opacity-0 scale-95"
                                        To: "transform opacity-100 scale-100"
                                    Leaving: "transition ease-in duration-75"
                                        From: "transform opacity-100 scale-100"
                                        To: "transform opacity-0 scale-95"
                                -->
                                <div class="absolute right-0 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1
                                    ring-black ring-opacity-5 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" .
                                    v-show="test">
                                    <!-- Active: "bg-gray-100", Not Active: "" -->
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                                        id="user-menu-item-0">Your Profile</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                                        id="user-menu-item-1">Settings</a>
                                    <a
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                    >
                                        Logout
                                    </a>
                                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="lg:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="#" class="block px-3 py-2 text-base font-medium text-white bg-gray-900 rounded-md">Dashboard</a>
                    <a href="#"
                        class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Team</a>
                    <a href="#"
                        class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Projects</a>
                    <a href="#"
                        class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Calendar</a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="w-10 h-10 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">Tom Cook</div>
                            <div class="text-sm font-medium text-gray-400">tom@example.com</div>
                        </div>
                        <button type="button"
                            class="flex-shrink-0 p-1 ml-auto text-gray-400 bg-gray-800 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                            <span class="sr-only">View notifications</span>
                            <!-- Heroicon name: outline/bell -->
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                    </div>
                    <div class="px-2 mt-3 space-y-1">
                        <a href="#"
                            class="block px-3 py-2 text-base font-medium text-gray-400 rounded-md hover:text-white hover:bg-gray-700">Your
                            Profile</a>
                        <a href="#"
                            class="block px-3 py-2 text-base font-medium text-gray-400 rounded-md hover:text-white hover:bg-gray-700">Settings</a>
                        <a href="#"
                            class="block px-3 py-2 text-base font-medium text-gray-400 rounded-md hover:text-white hover:bg-gray-700">Sign
                            out</a>
                    </div>
                </div>
            </div>
        </nav>
        @include('flash::message')

        <main class="">
            @yield('content')
        </main>
    </div>
</body>
    @yield('js')
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="/ressources/js/apex.js" defer></script>
    @livewireChartsScripts

</html>
