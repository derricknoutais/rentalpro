@extends('layouts.app')


@section('content')
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Reservations</h2>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="/reservations/create" class="ml-3 inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Ajouter</a>
        </div>
    </div>

    <div class='px-4 sm:px-6 lg:px-8'>
        <div class='mt-8 flow-root'>
            <div class='-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8'>
                <div class='inline-block min-w-full py-2 align-middle'>
                    <table class='min-w-full divide-y divide-gray-300'>
                        <thead>
                            <tr>
                                <th scope='col'
                                    class='py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8'>
                                    Nom</th>
                                <th scope='col' class='px-3 py-3.5 text-left text-sm font-semibold text-gray-900'>Voiture</th>
                                <th scope='col' class='px-3 py-3.5 text-left text-sm font-semibold text-gray-900'>Du</th>
                                <th scope='col' class='px-3 py-3.5 text-left text-sm font-semibold text-gray-900'>Au</th>
                                <th scope='col' class='px-3 py-3.5 text-left text-sm font-semibold text-gray-900'>Role</th>
                                <th scope='col' class='relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8'>
                                    <span class='sr-only'>Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class='divide-y divide-gray-200 bg-white'>
                            @foreach ($reservations as $resa)
                                <tr>
                                    <td class='whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8'>{{ $resa->client->nom . ' ' . $resa->client->prenom }}</td>
                                    <td class='whitespace-nowrap px-3 py-4 text-sm text-gray-500'>{{ $resa->contractable->nom() }}</td>
                                    <td class='whitespace-nowrap px-3 py-4 text-sm text-gray-500'>{{ $resa->du->format('d-m-Y h:i') }}</td>
                                    <td class='whitespace-nowrap px-3 py-4 text-sm text-gray-500'>{{ $resa->au->format('d-m-Y h:i') }}</td>
                                    <td class='relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8'>
                                        <a href='#' class='text-blue-600 hover:text-blue-900'>Edit<span class='sr-only'>,
                                                Lindsay Walton</span></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
