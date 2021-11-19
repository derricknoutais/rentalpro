<div class="flex flex-col">
    {{-- Filtres --}}
    <div class="flex flex-col">
        <div class="flex">
            <div class="flex flex-col justify-end">
                <label for="">Période du</label>
                <input type="date" wire:model="filtres1.date_du" class="px-6 py-2 rounded-md">
            </div>
            <div class="flex flex-col justify-end ml-5">
                <label for="">Au</label>
                <input type="date" wire:model="filtres1.date_au" class="px-6 py-2 rounded-md">
            </div>
            <div class="flex flex-col justify-end ml-5">
                <label for="">Voitures</label>
                <select wire:model="filtres1.voiture_selectionnee" class="px-6 py-2 rounded-md">
                    <option value="*">Toutes</option>
                    @foreach ($voitures as $voiture)
                        <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end ml-5">
                <button wire:click="filtrer('filtres1')" class="px-6 py-2 bg-green-300 rounded-md" >Filter</button>
            </div>
        </div>
        <div class="flex">
            <div class="flex flex-col justify-end">
                <label for="">Période du</label>
                <input type="date" wire:model="filtres2.date_du" class="px-6 py-2 rounded-md">
            </div>
            <div class="flex flex-col justify-end ml-5">
                <label for="">Au</label>
                <input type="date" wire:model="filtres2.date_au" class="px-6 py-2 rounded-md">
            </div>
            <div class="flex flex-col justify-end ml-5">
                <label for="">Voitures</label>
                <select wire:model="filtres2.voiture_selectionnee" class="px-6 py-2 rounded-md">
                    <option value="*">Toutes</option>
                    @foreach ($voitures as $voiture)
                        <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end ml-5">
                <button wire:click="filtrer('filtres2')" class="px-6 py-2 bg-green-300 rounded-md" >Filter</button>
            </div>
        </div>
    </div>
    <dl class="grid grid-cols-1 mt-5 overflow-hidden bg-white divide-y divide-gray-200 rounded-lg shadow md:grid-cols-3 md:divide-y-0 md:divide-x">
        {{-- Paiements Période du {{ $date_du }} au {{ $date_au }} --}}
        {{-- First Card --}}
        <div class="px-4 py-5 sm:p-10">
            <dt class="text-base font-normal text-gray-900">
                Paiements
            </dt>
            <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    {{ number_format( $filtres1['paiements']['periode'], 0, ',', '.' ) }}
                    <span class="ml-2 text-sm font-medium text-gray-500">
                        contre {{ number_format( $filtres2['paiements']['periode'], 0, ',', '.' ) }}
                    </span>
                </div>
                {{-- @if ($dashboard['paiements_annuels'] > $dashboard['last_year_payments'] ) --}}
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-up -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">
                            Augmentation de
                        </span>
                        {{-- {{ ( ($dashboard['paiements_annuels'] - $dashboard['last_year_payments']) / $dashboard['paiements_annuels'] ) * 100 }} --}}
                    </div>
                {{-- @else --}}
                    <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
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
                        {{-- {{ ( number_format( ( $dashboard['last_year_payments'] - $dashboard['paiements_annuels'] )/ $dashboard['paiements_annuels']  * 100, 0, ',', '.' )) }}% --}}
                    </div>
                {{-- @endif --}}
            </dd>
        </div>
        {{-- Second Card --}}
        <div class="px-4 py-5 sm:p-10">
            <dt class="text-base font-normal text-gray-900">
                Taux de Recouvrement
            </dt>
            <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    {{ number_format($filtres1['taux_recouvrement']['periode'], 1, ',', '.'  )}} %
                    <span class="ml-2 text-sm font-medium text-gray-500">
                        contre
                        {{ number_format($filtres2['taux_recouvrement']['periode'], 1, ',', '.')}} %
                    </span>
                </div>

                {{-- @if ($dashboard['payment_rate'] > $dashboard['last_year_payment_rate'] ) --}}
                <div
                    class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                    <!-- Heroicon name: solid/arrow-sm-up -->
                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">
                        Augmentation de
                    </span>
                    {{-- {{ ( number_format(  $dashboard['payment_rate'] - $dashboard['last_year_payment_rate'] , 1, ',', '.' )) }}% --}}
                </div>
                {{-- @else --}}
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
                    {{-- {{ ( number_format( $dashboard['last_year_payment_rate'] / $dashboard['payment_rate']  * 100, 0, ',', '.' )) }}% --}}
                </div>
                {{-- @endif --}}
            </dd>
        </div>
        {{-- Third Card --}}
        <div class="px-4 py-5 sm:p-10">
            <dt class="text-base font-normal text-gray-900">
                Jours de Locations
            </dt>
            <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    {{ $filtres1['jours_location']['periode'] }}
                    <span class="ml-2 text-sm font-medium text-gray-500">
                        contre {{ $filtres2['jours_location']['periode'] }}
                    </span>
                </div>
                {{-- @if ($dashboard['nb_locations'] > $dashboard['last_year_nb_locations'] ) --}}
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-up -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">
                            Augmentation de
                        </span>
                        {{-- {{ number_format(( $dashboard['nb_locations'] - $dashboard['last_year_nb_locations'] ) / $dashboard['nb_locations']  * 100 , 1, ',', '.') }} --}}
                    </div>
                {{-- @else --}}
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
                        {{-- {{ ( number_format( ($dashboard['last_year_nb_locations'] - $dashboard['nb_locations']) / $dashboard['nb_locations']  * 100, 0, ',', '.' )) }}% --}}
                    </div>
                {{-- @endif --}}
            </dd>
        </div>

    </dl>
</div>
