@extends('layouts.printA4')

@section('sheet1')
    <header class="flex justify-center">
        <img src="/img/logosta.png" alt="" class="w-1/2">
    </header>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto mt-12">
                <h1 class="text-base font-semibold text-gray-900">Paiements</h1>
                <p class="mt-2 text-sm text-gray-700">Paiements du {{ Carbon\Carbon::today()->format('d-m-Y') }}</p>
            </div>
        </div>
        <div class="-mx-4 mt-8 sm:-mx-0">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                            Date</th>
                        <th scope="col"
                            class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                            Montant
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                            Type</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($paiements_espece as $paiement)
                        <tr>
                            <td
                                class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                                {{ $paiement->created_at->format('h:i') }}
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ $paiement->type_paiement }}
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->numéro }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->contractable->immatriculation }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ $paiement->montant }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"
                            class="w-full max-w-0 py-4 pl-4 pr-3 text-right  text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                            TOTAL ESPECE
                        </td>

                        <td class="hidden font-bold px-3 py-4 text-sm text-gray-500 lg:table-cell">
                            {{ $paiements_espece->sum('montant') }}</td>
                    </tr>
                    @foreach ($paiements_airtelmoney as $paiement)
                        <tr>
                            <td
                                class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                                {{ $paiement->created_at->format('h:i') }}

                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ $paiement->type_paiement }}
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->numéro }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->contractable->immatriculation }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ $paiement->montant }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"
                            class="w-full max-w-0 py-4 pl-4 pr-3 text-right  text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                            TOTAL AIRTEL MONEY
                        </td>

                        <td class="hidden px-3 py-4 font-bold text-sm text-gray-500 lg:table-cell">
                            {{ $paiements_airtelmoney->sum('montant') }}</td>
                    </tr>

                    <!-- More people... -->
                </tbody>
            </table>
        </div>
    </div>
@endsection
