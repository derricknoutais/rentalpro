@extends('layouts.printA4')


@section('sheet1')
    <header class="flex justify-center">
        <img src="/img/logosta.png" alt="" class="w-1/2">
    </header>
    <main class="mt-5">
        <p class="text-right" >{{ $maintenance->created_at->format('d F Y') }}</p>
        <p class="text-2xl underline text-center">FICHE MAINTENANCE </p>
        <div class="flex justify-between mt-5">
            <p>Immatriculation : {{ $maintenance->voiture->immatriculation }}</p>
            <p>Technicien : {{ $maintenance->technicien->nom }}</p>
        </div>
        <div>
            <h2 class="text-xl mt-5 underline">Pannes</h2>
            <ul>
                @foreach ($maintenance->pannes as $panne)
                    <li class="list">{{ $panne->description }}</li>
                @endforeach
            </ul>
        </div>
        <p>Besoins Materiels :</p>

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">Materiels</h1>
                    <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Quantite
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Designation
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Prix Unitaire
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Prix Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($sale['data'][0]['line_items'] as $item)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $item['quantity'] }}</td>
                                            <td class=" px-3 py-4 text-sm text-gray-500">{{ $prods[$loop->index ] }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $item['price'] }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $item['price_total'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>
@endsection
