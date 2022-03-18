@extends('layouts.printA4')


@section('sheet1')
    <header class="flex justify-center">
        <img src="/img/logosta.png" alt="" class="w-1/2">
    </header>
    <main x-data="print({{ $contrat->total() }})">
        <h1 class="mt-6 text-3xl font-semibold text-center">Contrat Location Nº {{ $contrat->numéro }}</h1>


        {{-- CLIENT --}}
        <div class="flex justify-between mt-6">
            <div class="w-2/3 mr-3">
                <p class="mt-6 text-xl">
                    <span class="font-semibold underline">Objet:</span>
                    <span>Location </span>
                    <span>{{ $contrat->contractable->immatriculation }} </span>
                </p>
                <p class="mt-6 text-lg underline">Termes Contrat </p>
                <ol>
                    <li class="ml-6 text-sm">Le véhicule sera restitué à l'heure indiquée sur le contrat.</li>
                    <li class="ml-6 text-sm">Le véhicule devra être restitué dans le même état qu'il a été pris; faute de quoi le locataire
                        endossera les
                        charges afférentes aux dommages éventuels.</li>
                </ol>
            </div>
            <div class="flex justify-end w-1/3 px-4 pt-4 mt-12 border border-gray-800">
                <p class="flex flex-col text-lg">
                    <span class="text-xl font-semibold underline">Client:</span>
                    <span class="">{{ $contrat->client->nom .  ' '  . $contrat->client->prenom }} </span>
                    <span>{{ $contrat->client->phone1 }}</span>
                </p>
            </div>
        </div>

        <div class="row">

            <ol class="mt-1">
                <li class="ml-6 text-sm">Les images enregistrées dans le système et envoyées au client par e-mail feront office de réference de
                    l'état du véhicule.</li>
                <li class="ml-6 text-sm">S.T.A se réserve le droit de récuperer le véhicule loué pour tout retard de paiement.</li>
                <li class="ml-6 text-sm">S.T.A se réserve le droit de récuperer le véhicule loué au cas où une personne autre que le client est
                    aperçu entrain de conduire ce véhicule.</li>
                <li class="ml-6 text-sm">Toute prolongation  devra être notifiée 24 heures avant échéance du contrat actuel</li>
            </ol>
        </div>

        {{-- TABLEAU --}}
        <div class="flex flex-col mt-6">
            <div class="-my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-800">
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



        {{-- Arrêté la facture --}}
        <p class="mt-6 text-xl underline">Arrêté la présente facture à la somme de:</p>
        <p class="mt-2 text-lg">
            <span x-text="wn" class="capitalize"></span>
            <span class="">Francs CFA</span>
        </p>



    </main>
@endsection

@section('sheet2')
    {{-- CONTRAT --}}
    <p class="text-2xl font-semibold underline">Niveau de Carburant</p>
    <ul class="flex justify-between mt-3">
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">1/8</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">1/4</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">3/8</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">1/2</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">5/8</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">3/4</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">7/8</label>
        </li>
        <li class="flex text-2xl ">
            <div class="px-2 py-2 my-2 border border-black rounded-full"></div>
            <label for="" class="ml-1">Full</label>
        </li>
    </ul>
    <div class="flex">
        <div class="w-1/2">
            <p class="mt-12 text-2xl font-semibold underline">État des Véhicules</p>
            <img src="/img/condition vehicule.png" class="w-full mt-6">
        </div>
        <div class="w-1/2">
            <p class="mt-12 text-2xl font-semibold underline">État des Accessoires</p>
            <ul class="mt-12 ml-12 text-2xl">
                @foreach ($contrat->contractable->accessoires as $acc)
                    <li> {{ $acc->pivot->quantité }} {{ $acc->type }}</li>
                @endforeach
            </ul>

        </div>

    </div>
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
