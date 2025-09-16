@extends('layouts.app')


@section('content')
    <contractable-show :voiture_prop="{{ $voiture }}">
        <div class="container mx-auto">
            <div class="mt-10 md:flex md:items-center md:justify-between md:space-x-5">
                <div class="flex items-start space-x-5">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img class="w-16 h-16 rounded-full" :src="voiture.photo_url" alt="">
                            <span class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="pt-1.5">
                        <h1 class="text-2xl font-bold text-gray-900">@{{ voiture.immatriculation }}</h1>
                        <p class="text-sm font-medium text-gray-500">@{{ voiture.marque }} @{{ voiture.type }}</p>
                    </div>
                </div>
                <div
                    class="flex flex-col-reverse mt-6 space-y-4 space-y-reverse justify-stretch sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">

                    <button type="button"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                        Faire Louer
                    </button>
                    <button type="button"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                        Envoyer en Maintenance
                    </button>
                </div>
            </div>

            <div class="mt-10">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Détails Voiture
                    </h3>
                    <p class="max-w-2xl mt-1 text-sm text-gray-500">
                        Personal details and application.
                    </p>
                </div>
                <div class="mt-5 border-t border-gray-200">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Full name
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                Margot Foster
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Application for
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                Backend Developer
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Email address
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                margotfoster@example.com
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Salary expectation
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                $120,000
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                About
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                Fugiat ipsum ipsum deserunt culpa aute sint do nostrud anim incididunt cillum culpa
                                consequat.
                                Excepteur qui ipsum aliquip consequat sint. Sit id mollit nulla mollit nostrud in ea officia
                                proident. Irure nostrud pariatur mollit ad adipisicing reprehenderit deserunt qui eu.
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="sticky top-0 text-sm font-medium text-gray-500">
                                Contrats
                            </dt>
                            <dd class="max-h-screen mt-10 overflow-y-auto text-sm text-gray-900 sm:mt-10 sm:col-span-2">

                                <ul role="list"
                                    class="divide-y divide-gray-100 overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                                    @foreach ($voiture->contrats->reverse() as $contrat)
                                        <li
                                            class="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 sm:px-6">
                                            <div class="flex gap-x-4">
                                                <img class="h-12 w-12 flex-none rounded-full bg-gray-50"
                                                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                    alt="">
                                                <div class="min-w-0 flex-auto">
                                                    <p class="text-sm font-semibold leading-6 text-gray-900">
                                                        <a href="#">
                                                            <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                                            Leslie Alexander
                                                        </a>
                                                    </p>
                                                    <p class="mt-1 flex text-xs leading-5 text-gray-500">
                                                        <a href="mailto:leslie.alexander@example.com"
                                                            class="relative truncate hover:underline">leslie.alexander@example.com</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-x-4">
                                                <div class="hidden sm:flex sm:flex-col sm:items-end">
                                                    <p class="text-sm leading-6 text-gray-900">Co-Founder / CEO</p>
                                                    <p class="mt-1 text-xs leading-5 text-gray-500">Last seen <time
                                                            datetime="2023-01-23T13:23Z">3h ago</time></p>
                                                </div>
                                                <svg class="h-5 w-5 flex-none text-gray-400" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </contractable-show>
@endsection
