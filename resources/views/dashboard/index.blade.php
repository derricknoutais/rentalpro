@extends('layouts.app')


@section('content')
    <div class="container mx-auto bg-gray-100">

    @if ($compagnie->isHotel() || $compagnie->isVehicules())
        <div class="w-full my-10 overflow-y-auto">
            <h1 class="text-2xl">Plan des Chambres</h1>
            <div class="mt-5">
                {{-- <Visualiseur-Contractable></Visualiseur-Contractable> --}}
                @livewire('room-display', compact('contractables', 'compagnie'))
            </div>

        </div>
    @endif

    {{-- Header --}}
    <Reporting inline-template
        @hide-modal="hideModal()"
        {{-- :reporting_props={{ $reporting }} --}}
    >
        <div class="flex">
            {{-- Carte Reporting Graph --}}
            <div class="overflow-hidden bg-white shadow sm:rounded-lg w-1/2 mr-2">
                <div class="flex ">
                    <div class="px-4 py-2 sm:px-6">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Salut {{ Auth::user()->name }}, </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Voici ce qui se passe a {{ Auth::user()->compagnie->nom }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-4 py-5 sm:px-6 w-full">

                    <div class="flex px-5 pb-12 pt-3 shadow-xs w-full">
                        <div class="w-1/4">
                            <h3 class="text-2xl">Aujourd'hui {{ Auth::user()->compagnie->nom }} a vendu
                                {{-- @{{ report }}  --}}
                            </h3>
                        </div>
                        <div class="w-3/4">
                            <chart
                                {{-- type="line"
                                :labels="['Rouge', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange']"
                                label="# of Votes"
                                :data="[12, 19, 10, 15, 12, 13]" --}}
                            >
                            </chart>
                        </div>

                    </div>
                </div>
            </div>

            <div class="overflow-hidden bg-blue-50 shadow sm:rounded-lg w-1/2 ml-2">
                <div class="flex justify-between items-center">
                    <div class="px-4 py-2 sm:px-6">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Reservations </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Toutes les Reservations Vehicules / Chambres / Maintenance</p>
                    </div>
                    <div class="px-4 py-2 sm:px-6">
                        <a href="/reservation" type="button"
                            class="block rounded-md bg-blue-300 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm
                            hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                            focus-visible:outline-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-300 px-4 w-full">

                    <div class="flex pb-12 shadow-xs w-full">
                        <div class="">
                            <div class="sm:flex sm:items-center">
                                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                </div>
                            </div>
                            <div class="mt-8 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead>
                                                <tr>
                                                    <th scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">Nom</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Voiture / Chambre</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Du</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Au</th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                @foreach ($reservations as $resa)
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                            {{ $resa->client->nom() }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $resa->contractable->nom() }}</td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $resa->du }}</td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $resa->au }}</td>
                                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">,
                                                                    Lindsay Walton</span></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Reporting>

    {{-- Card --}}
        <livewire:dashboard-cards/>
        {{-- <dl class="grid grid-cols-1 mt-5 overflow-hidden bg-white divide-y divide-gray-200 rounded-lg shadow md:grid-cols-3 md:divide-y-0 md:divide-x">
            <div class="px-4 py-5 sm:p-10">
                <dt class="text-base font-normal text-gray-900">
                    Paiement Annuel
                </dt>
                <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                    <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                        {{ number_format($dashboard['paiements_annuels'], 0, ',', '.') }}
                        <span class="ml-2 text-sm font-medium text-gray-500">
                            contre {{ number_format($dashboard['last_year_payments'], 0, ',', '.') }}
                        </span>
                    </div>
                    @if ($dashboard['paiements_annuels'] > $dashboard['last_year_payments'] )
                        <div
                            class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <!-- Heroicon name: solid/arrow-sm-up -->
                            <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">
                                Augmentation de
                            </span>
                            {{ ( ($dashboard['paiements_annuels'] - $dashboard['last_year_payments']) / $dashboard['paiements_annuels'] ) * 100 }}
                        </div>
                    @else
                        <div
                            class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                            <!-- Heroicon name: solid/arrow-sm-down -->
                            <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">
                                Baisse de
                            </span>
                            {{ ( number_format( ( $dashboard['last_year_payments'] - $dashboard['paiements_annuels'] )/ $dashboard['paiements_annuels']  * 100, 0, ',', '.' )) }}%
                        </div>
                    @endif
                </dd>
            </div>

            <div class="px-4 py-5 sm:p-10">
                <dt class="text-base font-normal text-gray-900">
                    Taux de Recouvrement
                </dt>
                <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                    <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                        {{ number_format($dashboard['payment_rate'], 1, ',', '.'  )}} %
                        <span class="ml-2 text-sm font-medium text-gray-500">
                            contre
                            {{ number_format($dashboard['last_year_payment_rate'], 1, ',', '.'  )}} %
                        </span>
                    </div>

                    @if ($dashboard['payment_rate'] > $dashboard['last_year_payment_rate'] )
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-up -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">
                            Augmentation de
                        </span>
                        {{ ( number_format(  $dashboard['payment_rate'] - $dashboard['last_year_payment_rate'] , 1, ',', '.' )) }}%
                    </div>
                    @else
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-down -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">
                            Baisse de
                        </span>
                        {{ ( number_format( $dashboard['last_year_payment_rate'] / $dashboard['payment_rate']  * 100, 0, ',', '.' )) }}%
                    </div>
                    @endif
                </dd>
            </div>

            <div class="px-4 py-5 sm:p-10">
                <dt class="text-base font-normal text-gray-900">
                    Jours de Locations
                </dt>
                <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                    <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                        {{ $dashboard['nb_locations'] }}
                        <span class="ml-2 text-sm font-medium text-gray-500">
                            contre {{ $dashboard['last_year_nb_locations'] }}
                        </span>
                    </div>

                    @if ($dashboard['nb_locations'] > $dashboard['last_year_nb_locations'] )
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-up -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">
                            Augmentation de
                        </span>
                        {{ number_format(( $dashboard['nb_locations'] - $dashboard['last_year_nb_locations'] ) / $dashboard['nb_locations']  * 100 , 1, ',', '.') }}
                    </div>
                    @else
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-down -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">
                            Baisse de
                        </span>
                        {{ ( number_format( ($dashboard['last_year_nb_locations'] - $dashboard['nb_locations']) / $dashboard['nb_locations']  * 100, 0, ',', '.' )) }}%
                    </div>
                    @endif
                </dd>
            </div>
        </dl> --}}
        {{-- <div class="flex">
            <div class="w-1/2 mt-10" style="height: 50vh">
                <livewire:livewire-line-chart :line-chart-model="$columnChartModel" />
            </div>
            <div class="w-1/2 mt-10" style="height: 50vh">
                <div id='calendar' class=""></div>
            </div>

        </div> --}}
        <div class="w-full my-10 overflow-y-auto" style="height: 30vh">

            <livewire:documents-expiration-module />
        </div>

        <div class="w-full my-10 overflow-y-auto" style="height: 50vh">
            <livewire:car-display />
        </div>

    </div>
@endsection
<script>

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        if(calendarEl){
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                dateClick: function() {
                    alert('a day has been clicked!');
                },
                events: '/my-feeds'
            });
            calendar.setOption('locale', 'fr');
            calendar.render();
        }
    });

</script>

