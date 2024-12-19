@extends('layouts.app')

@section('content')
    <contrat-show inline-template environment="{{ app()->environment() }}">
        <div class="flex flex-col mt-6">

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-2xl font-medium leading-6 text-gray-900">Contrat {{ $contrat->numéro }}</h3>
                    @if ($contrat->activities && ($causer = $contrat->activities->where('description', 'created')->first()->causer))
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">Créé par {{ $causer->name }} le
                            {{ $contrat->created_at->format('d-m-Y') }}</p>
                    @endif
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
                                {{ $contrat->du->format('d/m/Y h:i:s') . ' - ' . $contrat->au->format('d/m/Y h:i:s') }}</dd>
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
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Images</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @foreach ($contrat->checkout as $imageName)
                                    <div class="sm:flex">
                                        <div class="mb-4 shrink-0 sm:mb-0 sm:mr-4">
                                            <img
                                                src="https://rentalpro.fra1.digitaloceanspaces.com/contrats/{{ $imageName }}">
                                        </div>
                                    </div>
                                @endforeach
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </contrat-show>
@endsection
