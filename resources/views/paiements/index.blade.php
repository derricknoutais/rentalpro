@extends('layouts.app')


@section('content')
    <paiements-index :paiements_prop="{{ $paiements }}" inline-template>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold text-gray-900">Paiements</h1>
                    <p class="mt-2 text-sm text-gray-700">La Liste de tous les Paiements enregistrés</p>
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
                                Immatriculation</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="paiement in paiements">
                            <td
                                class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                                @{{ paiement.created_at }}
                                <dl class="font-normal lg:hidden">
                                    <dt class="sr-only">Title</dt>
                                    <dd class="mt-1 truncate text-gray-700">Front-end Developer</dd>
                                    <dt class="sr-only sm:hidden">Email</dt>
                                    <dd class="mt-1 truncate text-gray-500 sm:hidden">lindsay.walton@example.com</dd>
                                </dl>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">@{{ paiement.montant }}</td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <a target="_blank" :href="'/contrat/' + paiement.payable.id"
                                    v-if="paiement.payable">@{{ paiement.payable.numéro }}</a>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <a target="_blank" :href="'/contractables/' + paiement.payable.contractable.id"
                                    v-if="paiement.payable">@{{ paiement.payable.contractable.immatriculation }}</a>
                            </td>
                        </tr>

                        <!-- More people... -->
                    </tbody>
                </table>
            </div>
        </div>
    </paiements-index>
@endsection
