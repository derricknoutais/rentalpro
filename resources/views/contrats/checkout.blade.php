@extends('layouts.app')

@section('content')
    <contrat-checkout :contrat_prop="{{ $contrat }}" inline-template>

        <form action="/contrats/{{ $contrat->id }}/save-photos" method="POST">
            @csrf
            <vue-signature-pad width="500px" height="500px" ref="signaturePad" />
            <h1>Photos</h1>
            <input type="hidden" name="avant" v-model="photos.avant">
            <input type="hidden" name="arriere" v-model="photos.arriere">
            <input type="hidden" name="droite" v-model="photos.droite">
            <input type="hidden" name="gauche" v-model="photos.gauche">
            <div>
                <label class="block text-sm/6 font-medium text-gray-900">Photo Avant</label>
                <div>

                    <permis-pond @file-processed="photoAvant" label="Sauvegarder Photo Avant">

                    </permis-pond>
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-900">Photo Côté Droit</label>
                <div>

                    <permis-pond @file-processed="photoDroite" label="Sauvegarder Photo Côté Droit">

                    </permis-pond>
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-900">Photo Côté Gauche</label>
                <div>

                    <permis-pond @file-processed="photoGauche" label="Sauvegarder Photo Côté Gauche">

                    </permis-pond>
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-900">Photo Arrière</label>
                <div>

                    <permis-pond @file-processed="photoArriere" label="Sauvegarder Photo Arrière">

                    </permis-pond>
                </div>
            </div>

            <h2 class="text-2xl mt-3">Documents</h2>
            <input type="hidden" name="documents" v-model="documents">
            <input type="hidden" name="accessoires" v-model="accessoires">
            <fieldset class="border-b border-t border-gray-200">
                <legend class="sr-only">Notifications</legend>
                <div class="divide-y divide-gray-200" v-for="document in contrat.contractable.documents">
                    <div class="relative flex gap-3 pb-4 pt-3.5">
                        <div class="min-w-0 flex-1 text-sm/6">
                            <label for="comments" class="font-medium text-gray-900">@{{ document.type }}</label>
                            <p id="comments-description" class="text-gray-500">@{{ document.pivot.date_expiration }}</p>
                        </div>
                        <div class="flex h-6 shrink-0 items-center">
                            <div class="group grid size-8 grid-cols-1">
                                <input :id="document.id" aria-describedby="comments-description" type="checkbox"
                                    :value="document.type + '(' + document.pivot.date_expiration + ')'" v-model="documents">
                                <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25"
                                    viewBox="0 0 14 14" fill="none">
                                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h2 class="text-2xl mt-3">Accessoires</h2>

            <fieldset class="border-b border-t border-gray-200">
                <legend class="sr-only">Notifications</legend>
                <div class="divide-y divide-gray-200" v-for="accessoire in contrat.contractable.accessoires">
                    <div class="relative flex gap-3 pb-4 pt-3.5">
                        <div class="min-w-0 flex-1 text-sm/6">
                            <label for="comments" class="font-medium text-gray-900">@{{ accessoire.type }}</label>
                            <p id="comments-description" class="text-gray-500">@{{ accessoire.pivot.quantité }}</p>
                        </div>
                        <div class="flex h-6 shrink-0 items-center">
                            <div class="group grid size-8 grid-cols-1">
                                <input type="checkbox" :id="accessoire.id"
                                    :value="accessoire.type + '(' + accessoire.pivot.quantité + ')'" v-model="accessoires">
                                <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25"
                                    viewBox="0 0 14 14" fill="none">
                                    <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path class="opacity-0 group-has-[:indeterminate]:opacity-100" d="M3 7H11"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                <button type="submit"
                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">Enregistrer</button>
                <button type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">Cancel</button>
            </div>
        </form>
    </contrat-checkout>
@endsection
