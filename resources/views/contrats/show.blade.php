@extends('layouts.app')

@section('content')
    <contrat-show inline-template environment="{{ app()->environment() }}">
        <div class="flex flex-col mt-6">

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-2xl font-medium leading-6 text-gray-900">Contrat {{ $contrat->numéro }}</h3>
                    @if (
                        $contrat->activities &&
                            $contrat->activities->where('description', 'created')->first() &&
                            ($causer = $contrat->activities->where('description', 'created')->first()->causer))
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">Créé par {{ $causer->name }} le
                            {{ $contrat->created_at->format('d-m-Y') }}</p>
                    @endif
                    <a href="/contrat/{{ $contrat->id }}/check-out" class="px-1 py-0 mr-2 text-white bg-blue-500 btn">
                        Effectuer check-out
                    </a>
                    <a href="/contrat/{{ $contrat->id }}/print" class="px-1 py-0 mr-2 text-white bg-blue-500 btn">
                        Imprimer
                    </a>
                </div>
                <div class="px-4 py-5 border-t border-gray-200 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Client</dt>
                            @isset($contrat->client)
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a target="_blank" class="text-blue-600 hover:text-blue-700"
                                        href="/client/{{ $contrat->client->id }}">{{ $contrat->client->nom . ' ' . $contrat->client->prenom }}</a>
                                </dd>
                            @endisset
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Voiture / Chambre
                            </dt>

                            @isset($contrat->contractable)
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a target="_blank" class="text-blue-600 hover:text-blue-700"
                                        href="/contractables/{{ $contrat->contractable->id }}">
                                        @if (Auth::user()->compagnie->isVehicules())
                                            {{ $contrat->contractable->immatriculation }}
                                        @else
                                            {{ $contrat->contractable->nom }}
                                        @endif
                                    </a>
                                </dd>
                            @endisset
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Période</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $contrat->du->format('d/m/Y h:i:s') . ' - ' . $contrat->au->format('d/m/Y h:i:s') }}
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Caution</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"></dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Actions</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <ul role="list" class="border border-gray-200 divide-y divide-gray-200 rounded-md">
                                    @foreach ($contrat->activities as $activity)
                                        <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                                            <div class="flex items-center w-0">
                                                @if ($activity->description === 'created')
                                                    <svg class="flex-shrink-0 w-5 h-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    <span class="ml-3">Creation</span>
                                                @elseif($activity->description === 'updated')
                                                    <svg class="flex-shrink-0 w-5 h-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                    <span class="ml-3">Modification</span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <span>

                                                    {{ $activity->causer->name }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <span>
                                                    @if ($activity->description === 'updated')
                                                        {{ $activity->created_at->addHours(1)->format('d-M-Y H:i:s') }}
                                                    @else
                                                        {{ $activity->created_at->format('d-M-Y H:i:s') }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div>
                                                <span>
                                                    {{-- {{ $activity->changes }} --}}
                                                </span>

                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </dd>
                        </div>



                    </dl>
                    <ul role="list"
                        class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                        @if ($contrat->checkout !== null)
                            @foreach ($contrat->checkout->images as $imageName)
                                <li class="relative">
                                    {{-- @dd($contrat->checkout) --}}
                                    <div
                                        class="group overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
                                        <img src="https://rentalpro.fra1.digitaloceanspaces.com/contrats/{{ $imageName }}"
                                            alt=""
                                            class="pointer-events-none aspect-[10/7] object-cover group-hover:opacity-75">
                                        <button type="button" class="absolute inset-0 focus:outline-none">
                                            <span class="sr-only">View details for IMG_4985.HEIC</span>
                                        </button>
                                    </div>
                                    <p class="pointer-events-none mt-2 block truncate text-sm font-medium text-gray-900">
                                        IMG_4985.HEIC
                                    </p>
                                    <p class="pointer-events-none block text-sm font-medium text-gray-500">3.9 MB</p>
                                </li>
                            @endforeach
                        @endif

                    </ul>
                    <div class="px-4 sm:px-6 lg:px-8">

                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Name</th>

                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @if ($contrat->checkout)
                                                    @foreach (str_getcsv($contrat->checkout->documents) as $document)
                                                        <tr>
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                                {{ $document }}</td>

                                                        </tr>
                                                    @endforeach
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 sm:px-6 lg:px-8">

                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Name</th>

                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @if ($contrat->checkout)
                                                    @foreach (str_getcsv($contrat->checkout->accessoires) as $accessoire)
                                                        <tr>
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                                {{ $accessoire }}</td>

                                                        </tr>
                                                    @endforeach
                                                @endif
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
    </contrat-show>
@endsection
