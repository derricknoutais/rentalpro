@extends('layouts.printA5')


@section('sheet1')
    <header class="flex flex-col justify-center items-center">
        <img src="/img/orishainn_logo.png" alt="" class="w-1/2">
        <p class="text-">
            Hotel - Bar
        </p>
        <p class="">
            Ruelle Canal Olympia apres la Cite Rose
        </p>
        <p class="">
            077.59.92.90 / 066.88.22.77
        </p>
    </header>
    <main x-data="print({{ $contrat->total() }})">
        <h1 class="mt-3 text-3xl font-semibold text-left underline">Recu Nº {{ $contrat->numéro }}</h1>

        {{-- SSD --}}

        {{-- CLIENT --}}
        <div class="flex justify-end mt-5">
            <div class="w-3/5 mt-5">
                <p>Periode du : {{ $contrat->du->format('d/m/Y') }} au: {{ $contrat->au->format('d/m/Y') }}</p>
            </div>
            <div class="flex w-2/5 justify-end mt-1 border border-black p-3">
                <span class="text-xl font-medium underline">Client:</span>
                <p class="flex flex-col text-lg">
                    <span class="">{{ $contrat->client->nom . ' ' . $contrat->client->prenom }} </span>
                    <span>{{ $contrat->client->phone1 }}</span>
                </p>
            </div>
        </div>




        {{-- TABLEAU --}}
        <div class="flex flex-col mt-6">
            <div class="-my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden border-b border-gray-200 shadow ">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-yellow-600">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-1 font-medium tracking-wider text-left text-gray-100 uppercase text-md">
                                        Nbre Jours</th>
                                    <th scope="col"
                                        class="px-6 py-1 font-medium tracking-wider text-left text-gray-100 uppercase text-md">
                                        Offre</th>
                                    <th scope="col"
                                        class="px-6 py-1 font-medium tracking-wider text-left text-gray-100 uppercase text-md">
                                        Total</th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap">{{
                                        number_format($contrat->nombre_jours, 0, ',' , '.') }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-md whitespace-nowrap">{{
                                        number_format($contrat->prix_journalier, 0, ',', '.' ) }} F CFA</td>
                                    <td class="px-6 py-4 text-gray-500 text-md whitespace-nowrap">{{
                                        number_format($contrat->total(), 0, ',', '.') }} F CFA</td>
                                </tr>

                                <!-- 1/2 Journee -->
                                @if ($contrat->demi_journee)
                                <tr class="border border-gray-200 bg-white">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-medium text-right text-gray-500 text-md whitespace-nowrap">
                                        Option 1/2 Journee</td>
                                    <td class="px-6 py-4 font-medium text-gray-500 text-md whitespace-nowrap">{{
                                        $contrat->demi_journee }} F CFA</td>
                                </tr>
                                @endif

                                <!-- 1/2 Journee -->
                                @if ($contrat->montant_chauffeur)
                                <tr class="border border-gray-200 bg-white">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-medium text-right text-gray-500 text-md whitespace-nowrap">
                                        Option Chauffeur</td>
                                    <td class="px-6 py-4 font-medium text-gray-500 text-md whitespace-nowrap">{{
                                        $contrat->montant_chauffeur }} F CFA</td>
                                </tr>
                                @endif

                                @if ($contrat->montant_chauffeur || $contrat->demi_journee)
                                <tr class="border border-gray-200 bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-medium text-right text-gray-500 text-md whitespace-nowrap">
                                        Montant Total</td>
                                    <td class="px-6 py-4 font-medium text-gray-500 text-md whitespace-nowrap">{{
                                        $contrat->total() }} F CFA
                                    </td>
                                </tr>
                                @endif



                                <!-- Paiements -->
                                <tr class="border border-gray-200 bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-medium text-right text-gray-500 text-md whitespace-nowrap">
                                        Paiements Perçus
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-500 text-md whitespace-nowrap">{{
                                        $contrat->payé() }} F CFA</td>
                                </tr>
                                <!-- Even row -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-md whitespace-nowrap"></td>
                                    <td class="px-6 py-4 font-medium text-right text-gray-500 text-md whitespace-nowrap">
                                        Solde
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-500 text-md whitespace-nowrap">{{
                                        $contrat->solde() }} F CFA</td>
                                </tr>

                                <!-- More people... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Arrêté la facture --}}
        <p class="mt-6 text-xl underline">Arrêté la présente facture à la somme de:</p>
        <p class="mt-2 text-lg">
            <span x-text="wn" class="capitalize"></span>
            <span class="">Francs CFA</span>
        </p>

        <div class="flex justify-end mt-12 mr-12">
            <p>Le Responsable</p>
        </div>



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
