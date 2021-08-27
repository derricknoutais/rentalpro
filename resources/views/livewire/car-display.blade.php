<div class="container" x-data="dashboard()">
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Immatriculation
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Title
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cars as $car)
                                @if ($loop->odd)
                                    <!-- Odd row -->
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $car->immatriculation }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            Regional Paradigm Technician
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <button wire:click="addCarToDisplay({{ $car }})" class="text-indigo-600 hover:text-indigo-900">Voir Détails</button>
                                        </td>
                                    </tr>
                                @else
                                    <!-- Even row -->
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $car->immatriculation }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            Product Directives Officer
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <button wire:click="addCarToDisplay({{ $car }})" class="text-indigo-600 hover:text-indigo-900">Voir Détails</button>
                                        </td>
                                    </tr>

                                @endif
                            @endforeach


                            <!-- More people... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- This example requires Tailwind CSS v2.0+ -->
    @if ($carToDisplay)
        <div class="fixed inset-0 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <!-- Background overlay, show/hide based on slide-over state. -->
                <div class="absolute inset-0" aria-hidden="true">
                    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <!--
                            Slide-over panel, show/hide based on slide-over state.

                            Entering: "transform transition ease-in-out duration-500 sm:duration-700"
                                From: "translate-x-full"
                                To: "translate-x-0"
                            Leaving: "transform transition ease-in-out duration-500 sm:duration-700"
                                From: "translate-x-0"
                                To: "translate-x-full"
                        -->
                        <div class="w-screen max-w-2xl">
                            <div class="flex flex-col h-full overflow-y-scroll bg-white shadow-xl">
                                <div class="px-4 py-6 sm:px-6">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">
                                            Détails
                                        </h2>
                                        <div class="flex items-center ml-3 h-7">
                                            <button type="button"
                                                wire:click="closeDisplay"
                                                class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:ring-2 focus:ring-indigo-500">
                                                <span class="sr-only">Close panel</span>
                                                <!-- Heroicon name: outline/x -->
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Main -->
                                <div class="divide-y divide-gray-200">
                                    <div class="pb-6">
                                        <div class="h-24 bg-indigo-700 sm:h-20 lg:h-28"></div>
                                        <div class="flow-root px-4 -mt-12 sm:-mt-8 sm:flex sm:items-end sm:px-6 lg:-mt-15">
                                            <div>
                                                <div class="flex -m-1">
                                                    <div class="inline-flex overflow-hidden border-4 border-white rounded-lg">
                                                        <img class="flex-shrink-0 w-24 h-24 sm:h-40 sm:w-40 lg:w-48 lg:h-48"
                                                            src="{{ $carToDisplay->photo_url }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-6 sm:ml-6 sm:flex-1">
                                                <div>
                                                    <div class="flex items-center">
                                                        <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ $carToDisplay->immatriculation }}
                                                        </h3>
                                                        <span
                                                            class="ml-2.5 bg-green-400 flex-shrink-0 inline-block h-2 w-2 rounded-full">
                                                            <span class="sr-only">Online</span>
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-500">{{ ucfirst($carToDisplay->etat) }}</p>
                                                </div>
                                                <div class="flex flex-wrap mt-5 space-y-3 sm:space-y-0 sm:space-x-3">
                                                    <button type="button"
                                                        class="inline-flex items-center justify-center flex-shrink-0 w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:flex-1">
                                                        Faire Louer
                                                    </button>
                                                    <a href="/voiture/{{ $carToDisplay->id }}"
                                                        class="inline-flex items-center justify-center flex-1 w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Voir Plus d'Informations
                                                    </a>
                                                    <span class="inline-flex ml-3 sm:ml-0">
                                                        <div class="relative inline-block text-left">
                                                            <button type="button"
                                                                class="inline-flex items-center p-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                id="options-menu-button" aria-expanded="false"
                                                                aria-haspopup="true">
                                                                <span class="sr-only">Open options menu</span>
                                                                <!-- Heroicon name: solid/dots-vertical -->
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path
                                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                                </svg>
                                                            </button>

                                                            <!--
                                                                Dropdown panel, show/hide based on dropdown state.

                                                                Entering: "transition ease-out duration-100"
                                                                From: "transform opacity-0 scale-95"
                                                                To: "transform opacity-100 scale-100"
                                                                Leaving: "transition ease-in duration-75"
                                                                From: "transform opacity-100 scale-100"
                                                                To: "transform opacity-0 scale-95"
                                                            -->
                                                            <div class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                                role="menu" aria-orientation="vertical"
                                                                aria-labelledby="options-menu-button" tabindex="-1">
                                                                <div class="py-1" role="none">
                                                                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700"
                                                                        role="menuitem" tabindex="-1"
                                                                        id="options-menu-item-0">View profile</a>
                                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700"
                                                                        role="menuitem" tabindex="-1"
                                                                        id="options-menu-item-1">Copy profile link</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-5 sm:px-0 sm:py-0">
                                        <dl class="space-y-8 sm:divide-y sm:divide-gray-200 sm:space-y-0">
                                            <div class="sm:flex sm:px-6 sm:py-5">
                                                <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                                                    Bio
                                                </dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:ml-6 sm:col-span-2">
                                                    <p>
                                                        {{ $carToDisplay->marque }} {{ $carToDisplay->type }}
                                                    </p>
                                                </dd>
                                            </div>
                                            <div class="sm:flex sm:px-6 sm:py-5">
                                                <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                                                    Prix de Location
                                                </dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:ml-6 sm:col-span-2">
                                                    {{ $carToDisplay->prix }} F CFA
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@section('js')
<script>
    function dashboard(){
        return {
            carModal : false,
        }
    }
</script>
@endsection
