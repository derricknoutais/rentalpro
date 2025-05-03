@extends('layouts.app')

@section('content')
    <main>
        <!-- Settings forms -->
        <div class="divide-y divide-gray-800/5">
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base/7 font-semibold text-gray-800">Informations Personnelles</h2>
                </div>

                <form class="md:col-span-2">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
                        {{-- <div class="col-span-full flex items-center gap-x-8">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="" class="size-24 flex-none rounded-lg bg-gray-800 object-cover">
                            <div>
                                <button type="button"
                                    class="rounded-md bg-gray-800/10 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-800/20">Change
                                    avatar</button>
                                <p class="mt-2 text-xs/5 text-gray-400">JPG, GIF or PNG. 1MB max.</p>
                            </div>
                        </div> --}}

                        <div class="sm:col-span-full">
                            <label for="name" class="block text-sm/6 font-medium text-gray-800">Nom</label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" autocomplete="given-name"
                                    value="{{ Auth::user()->name }}"
                                    class="block w-full rounded-md bg-gray-800/5 px-3 py-1.5 text-base text-gray-800 outline outline-1 -outline-offset-1 outline-gray-800/10 placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="email" class="block text-sm/6 font-medium text-gray-800">Email
                                address</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email"
                                    value="{{ Auth::user()->email }}"
                                    class="block w-full rounded-md bg-gray-800/5 px-3 py-1.5 text-base text-gray-800 outline outline-1 -outline-offset-1 outline-gray-800/10 placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                            </div>
                        </div>
                    </div>



                    <div class="mt-8 flex">
                        <button type="submit"
                            class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
                    </div>
                </form>
            </div>

            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base/7 font-semibold text-gray-800">Informations Personnelles</h2>
                </div>
                <div class="md:col-span-2">

                    @if (Auth::user()->settings && json_decode(Auth::user()->settings)->signature)
                        <div class="col-span-full mt-2">
                            <div class="flex items-center">

                                <p>Votre signature actuelle</p>

                            </div>
                            <img src="https://rentalpro.fra1.digitaloceanspaces.com/{{ json_decode(Auth::user()->settings)->signature }}"
                                alt=""
                                class="pointer-events-none aspect-[10/7] object-cover group-hover:opacity-75">

                        </div>
                        <form action="/user/{{ Auth::user()->id }}/delete-signature" method="POST" class="  mt-5">
                            @method('DELETE')
                            {{-- <input type="hidden" name="signature" value="{{ Auth::user()->settings }}"> --}}
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre signature ?');"
                                class="ml-4 rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-red-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Supprimer</button>
                        </form>
                    @else
                        <div class="col-span-full mt-2">
                            <p>Vous n'avez pas encore de signature</p>
                        </div>
                        <div class="col-span-full mt-2 sm:w-full" style="width: 300px;">
                            <p>Signez ici</p>
                            <my-signature-pad :user_id="{{ Auth::user()->id }}"></my-signature-pad>
                        </div>
                    @endif
                </div>

            </div>
            <div class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-base/7 font-semibold text-gray-800">Change password</h2>
                    <p class="mt-1 text-sm/6 text-gray-400">Update your password associated with your account.</p>
                </div>

                <form class="md:col-span-2">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
                        <div class="col-span-full">
                            <label for="current-password" class="block text-sm/6 font-medium text-gray-800">Current
                                password</label>
                            <div class="mt-2">
                                <input id="current-password" name="current_password" type="password"
                                    autocomplete="current-password"
                                    class="block w-full rounded-md bg-gray-800/5 px-3 py-1.5 text-base text-gray-800 outline outline-1 -outline-offset-1 outline-gray-800/10 placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="new-password" class="block text-sm/6 font-medium text-gray-800">New
                                password</label>
                            <div class="mt-2">
                                <input id="new-password" name="new_password" type="password" autocomplete="new-password"
                                    class="block w-full rounded-md bg-gray-800/5 px-3 py-1.5 text-base text-gray-800 outline outline-1 -outline-offset-1 outline-gray-800/10 placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="confirm-password" class="block text-sm/6 font-medium text-gray-800">Confirm
                                password</label>
                            <div class="mt-2">
                                <input id="confirm-password" name="confirm_password" type="password"
                                    autocomplete="new-password"
                                    class="block w-full rounded-md bg-gray-800/5 px-3 py-1.5 text-base text-gray-800 outline outline-1 -outline-offset-1 outline-gray-800/10 placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex">
                        <button type="submit"
                            class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
