
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Documents Vehicules</h1>
            <p class="mt-2 text-sm text-gray-700">Une liste de tous les documents et leur date d'expiration.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">

        </div>
    </div>
    <div class="flex flex-col mt-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Immatriculation
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date Expiration
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nombre Jours Restant
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">

                            @foreach ($voitures as $voiture)
                                @foreach ($voiture->documents as $document)
                                    <tr
                                        @if(  ( $nbreJours = now()->diffInDays( \Carbon\Carbon::parse($document->pivot->date_expiration), false) ) <= 0 )
                                            class="text-white bg-red-500"
                                        @elseif(( $nbreJours = now()->diffInDays( \Carbon\Carbon::parse($document->pivot->date_expiration), false) ) <= 31 )
                                            class="text-white bg-orange-500"
                                        @else
                                            class="text-white bg-green-500"
                                        @endif
                                    >
                                        <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-6">{{ $voiture->immatriculation
                                            }}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap">{{ $document->type }}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap">{{ $document->pivot->date_expiration }}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap">{{ $nbreJours }}</td>
                                    </tr>
                                @endforeach
                            @endforeach


                            <!-- More people... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

