@extends('layouts.app')


@section('content')
    {{-- <reporting-general inline-template :contrats="{{ $contrats }}">
        <div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="mt-5 text-center">Reporting General</h1>
                    </div>
                </div>
            </div>
            <div class="mt-5 container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#hebdomadaire" @click="selectWeeklyContracts()">Reporting Hebdomadaire</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#mensuel" @click="selectMonthlyContracts()" >Reporting Mensuel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="#annuel" data-toggle="tab" @click="selectYearlyContracts()">Reporting Annuel</a>
                            </li>
                        </ul>

                            <div class="row tab-pane fade" id="hebdomadaire" role="tabpanel" v-if="reporting_hebdomadaire.show">
                                <div class="col-12">
                                    <GChart type="ColumnChart" :data="reporting_hebdomadaire.nombre_locations" :options="reporting_hebdomadaire.nombre_locations_options" />
                                </div>
                            </div>
                            <div class="row tab-pane fade show active" id="mensuel" role="tabpanel">
                                <div class="col-12">
                                    Mensuel
                                </div>
                            </div>
                            <div class="row tab-pane fade" id="annuel" role="tabpanel" v-if="reporting_annuel.show">
                                <div class="col-12">
                                    <GChart type="ColumnChart" :data="reporting_annuel.nombre_locations" :options="reporting_annuel.nombre_locations_options"/>
                                </div>
                                <div class="col-12">
                                    <GChart type="ColumnChart" :data="reporting_annuel.revenus" :options="reporting_annuel.revenus_options" />
                                </div>
                            </div>

                    </div>

                </div>
            </div>
        </div>
    </reporting-general> --}}

    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="container mx-auto bg-gray-100">

        <div class="w-full my-10 overflow-y-auto">
            <h1 class="text-2xl">Plan des Chambres</h1>
            <div class="mt-5">
                @livewire('room-display', compact('contractables'))
            </div>

        </div>


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

