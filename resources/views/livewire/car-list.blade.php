<div>
    <div class="mt-4">
        <!-- Dropdown menu on small screens -->
        <div class="sm:hidden">
            <label for="current-tab" class="sr-only">Select a tab</label>
            <select id="current-tab" name="current-tab"
                class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option>Tout</option>

                <option>Disponible</option>

                <option selected>Loué</option>

                <option>Maintenance</option>

                <option>Vendu</option>
            </select>
        </div>
        <!-- Tabs at small breakpoint and up -->
        <div class="hidden sm:block">
            <nav class="flex -mb-px space-x-8">
                <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
                <a href="/voitures"
                    {{-- wire:click="searchByState('all')" --}}
                    class="px-1 pb-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    Tout
                </a>

                <a href="/voitures?etat=disponible"
                    {{-- wire:click="searchByState('disponible')" --}}
                    class="px-1 pb-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    Disponible
                </a>

                <button wire:click="searchByState('loue')"
                    {{-- wire:click="searchByState('loué')" --}}
                    class="px-1 pb-4 text-sm font-medium text-indigo-600 border-b-2 border-indigo-500 whitespace-nowrap"
                    aria-current="page">
                    Loué
                </button>

                <button wire:click="searchByState('maintenance')"
                    class="px-1 pb-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    En Maintenance
                </button>

                <button wire:click="searchByState('vendu')"
                    class="px-1 pb-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    Vendu
                </button>
            </nav>
        </div>
    </div>
    <div class="overflow-hidden bg-white shadow sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @foreach ($voitures as $voiture)
            <li>
                <a href="/voiture/{{ $voiture->id }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                {{ $voiture->immatriculation }}
                            </p>
                            <div class="flex flex-shrink-0 ml-2">
                                <p
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                    {{ $voiture->etat }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    <!-- Heroicon name: solid/users -->
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                    {{ $voiture->marque }} {{ $voiture->type }}
                                </p>
                                <p class="flex items-center mt-2 text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                    <!-- Heroicon name: solid/location-marker -->
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Remote
                                </p>
                            </div>
                            <div class="flex items-center mt-2 text-sm text-gray-500 sm:mt-0">
                                <!-- Heroicon name: solid/calendar -->
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>
                                    Closing on
                                    <time datetime="2020-01-07">January 7, 2020</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
