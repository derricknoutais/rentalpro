@extends('layouts.app')


@section('content')

    <div class="min-h-full container-fluid">
        <div class="flex flex-col">
            <!-- This example requires Tailwind CSS v2.0+ -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Titre
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Voiture
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Technicien
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Main d'Oeuvre
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Coût Pièces
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($maintenances as $maintenance)
                                        <tr
                                            @if ($loop->odd)
                                                class="bg-white"
                                            @else
                                                class="bg-gray-100"
                                            @endif
                                        >
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                {{ $maintenance->created_at->format('d-M-Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                {{ $maintenance->titre }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                @isset ( $maintenance->voiture )
                                                    {{ $maintenance->voiture->immatriculation }}
                                                @endisset
                                            </td>


                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                @if($maintenance->technicien)

                                                    {{ $maintenance->technicien->nom }}

                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $maintenance->coût }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                {{ $maintenance->coût_pièces }}
                                            </td>
                                            <td>
                                                <a href="/maintenance/{{ $maintenance->id }}/edit" class="bg-blue-300 px-3 py-1 rounded-md mr-3">
                                                    Modifier
                                                </a>
                                                @if (! $maintenance->gescash_transaction_id)
                                                    <a class="text-white bg-blue-500 px-3 py-1 rounded-md" href="/maintenances/{{ $maintenance->id }}/envoyer-gescash">Envoyer à Gescash</a>
                                                @else
                                                    <span class="text-white bg-green-500 rounded-2xl">Envoyé à Gescash</span>
                                                @endif
                                                <form action="/maintenances/{{ $maintenance->id }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="bg-red-300 px-3 py-1 rounded-md mr-3" type="submit" >
                                                        Supprimer
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky flex justify-end px-40 bottom-16 container-fluid">
            <a href="/maintenances/create">
                <i class="text-green-700 cursor-pointer fas fa-plus-circle fa-5x hover:text-green-800"></i>
            </a>
        </div>
    </div>

@endsection
