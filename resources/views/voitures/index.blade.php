@extends('layouts.app')


@section('content')

    <div class="container mx-auto">
        <div class="relative pb-5 mt-10 border-b border-gray-200 sm:pb-0">
            <div class="md:flex md:items-center md:justify-between">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Liste Véhicules
                </h2>
                <div class="flex mt-3 md:mt-0 md:absolute md:top-3 md:right-0">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Share
                    </button>
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create
                    </button>
                </div>
            </div>

            <livewire:car-list >
        </div>
    </div>
@endsection
