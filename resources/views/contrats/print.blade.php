@extends('layouts.printA4')


@section('content')
    <header class="flex justify-center">
        <img src="/img/logosta.png" alt="" class="w-1/2">
    </header>
    <main x-data="print({{ $contrat->total() }})">
        <h1 class="mt-6 text-3xl font-semibold text-center">Contrat Location Nº {{ $contrat->numéro }}</h1>
        {{-- CLIENT --}}
        <div class="flex justify-between mt-12">
            <div class="mr-6">
                <p class="mt-6 text-xl">
                    <span class="font-semibold underline">Objet:</span>
                    <span>Location </span>
                    <span>{{ $contrat->contractable->immatriculation }} </span>
                </p>
            </div>
            <div class="flex justify-end w-1/3 px-4 py-4 mt-6 border border-gray-800">
                <p class="flex flex-col mt-2 text-lg">
                    <span class="text-xl font-semibold underline">Client:</span>
                    <span class="">{{ $contrat->client->nom .  ' '  . $contrat->client->prenom }} </span>
                    <span>{{ $contrat->client->phone1 }}</span>
                </p>
            </div>
        </div>

        {{-- TABLEAU --}}
        <div class="flex flex-col mt-12">
            <div class="-my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-400">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 font-medium tracking-wider text-left text-gray-100 uppercase text-md">
                                        Nbre Jours</th>
                                    <th scope="col"
                                        class="px-6 py-3 font-medium tracking-wider text-left text-gray-100 uppercase text-md">
                                        Montant Journalier</th>
                                    <th scope="col"
                                        class="px-6 py-3 font-medium tracking-wider text-left text-gray-100 uppercase text-md">
                                        Total</th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap">{{ number_format($contrat->nombre_jours, 0, ',' , '.') }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-md whitespace-nowrap">{{ number_format($contrat->prix_journalier, 0, ',', '.' ) }} F CFA</td>
                                    <td class="px-6 py-4 text-gray-500 text-md whitespace-nowrap">{{ number_format($contrat->total(), 0, ',', '.') }} F CFA</td>
                                </tr>

                                <!-- Even row -->
                                <tr class="border border-gray-200 bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-semibold text-right text-gray-500 text-md whitespace-nowrap">Paiements Perçus
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-500 text-md whitespace-nowrap">{{ $contrat->payé() }} F CFA</td>
                                </tr>
                                <!-- Even row -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-semibold text-right text-gray-500 text-md whitespace-nowrap">Solde
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-500 text-md whitespace-nowrap">{{ $contrat->solde() }} F CFA</td>
                                </tr>

                                <!-- More people... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <p class="mt-6 text-xl underline">Arrêté la présente facture à la somme de:</p>
        <p class="mt-2 text-lg">
            <span x-text="wn" class="capitalize"></span>
            <span class="">Francs CFA</span>
        </p>


    </main>
@endsection

{{-- @push('script-print') --}}
    <script>
        function print (total){
            return {
                wn : writtenNumber(total)
            }
        }
    </script>
{{-- @endpush --}}
