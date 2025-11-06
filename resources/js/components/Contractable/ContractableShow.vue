<template>
    <div>
        <div v-if="contractable" class="space-y-10">
            <!-- Header -->
            <section
                class="mt-10 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900/40">
                <div
                    class="flex flex-col gap-6 border-b border-gray-100 pb-6 md:flex-row md:items-center md:justify-between dark:border-white/5">
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <img :src="photoUrl" alt="Photo du contractable"
                                class="h-20 w-20 rounded-full object-cover ring-4 ring-indigo-50 dark:ring-indigo-500/20">
                            <span class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></span>
                        </div>
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-300">
                                {{ typeLabel }}
                            </p>
                            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ displayName }}
                            </h1>
                            <p v-if="secondaryLabel" class="text-sm text-gray-500 dark:text-gray-300">
                                {{ secondaryLabel }}
                            </p>
                            <p v-if="contractable.chassis" class="text-xs text-gray-400">
                                Châssis : {{ contractable.chassis }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a :href="'/contrats/create?contractable_id=' + contractable.id"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400">
                            Faire louer
                        </a>
                        <button type="button" @click="openPanneModal"
                            class="inline-flex items-center justify-center rounded-xl bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-yellow-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500">
                            Signaler une panne
                        </button>
                        <button type="button"
                            class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">
                            Envoyer en maintenance
                        </button>
                    </div>
                </div>
                <dl class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-if="contractable.type"
                        class="rounded-xl border border-gray-100 px-4 py-3 dark:border-white/5">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Type</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">
                            {{ contractable.type }}
                        </dd>
                    </div>
                    <div v-if="contractable.marque"
                        class="rounded-xl border border-gray-100 px-4 py-3 dark:border-white/5">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Marque</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">
                            {{ contractable.marque }}
                        </dd>
                    </div>
                    <div class="rounded-xl border border-gray-100 px-4 py-3 dark:border-white/5">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">État</dt>
                        <dd class="mt-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                            :class="etatBadge.classes">
                            {{ etatBadge.label }}
                        </dd>
                    </div>
                    <div v-if="contractable.prix"
                        class="rounded-xl border border-gray-100 px-4 py-3 dark:border-white/5">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Tarif journalier</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">
                            {{ contractable.prix | currency }}
                        </dd>
                    </div>
                </dl>
            </section>

            <!-- Gallery -->
            <section v-if="hasGallery"
                class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900/40">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Galerie photos</h2>
                        <p class="text-sm text-gray-500">Cliquez sur une image pour l’ouvrir.</p>
                    </div>
                    <span class="text-xs font-medium text-gray-400">{{ galleryImages.length }} photo(s)</span>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    <a v-for="image in galleryImages" :key="image.id || image.url" :href="image.url" target="_blank"
                        class="group relative overflow-hidden rounded-xl border border-gray-100 shadow-sm ring-1 ring-gray-50 hover:ring-indigo-200 dark:border-white/5 dark:hover:ring-indigo-500/30">
                        <img :src="image.url" :alt="'Photo ' + displayName"
                            class="h-40 w-full object-cover transition duration-200 group-hover:scale-105">
                    </a>
                </div>
            </section>

            <!-- Pannes & Contrats -->
            <section class="grid gap-6 lg:grid-cols-3">
                <article
                    class="col-span-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900/40">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Historique des pannes</h2>
                            <p class="text-sm text-gray-500">Suivez les incidents déclarés sur ce contractable.</p>
                        </div>
                        <button type="button" @click="openPanneModal"
                            class="inline-flex items-center rounded-lg border border-indigo-200 px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 dark:border-indigo-500/30 dark:text-indigo-300 dark:hover:bg-indigo-500/5">
                            Ajouter une panne
                        </button>
                    </div>
                    <ul v-if="pannes.length" class="mt-6 divide-y divide-gray-100 dark:divide-white/5">
                        <li v-for="panne in pannes" :key="panne.id" class="flex items-start justify-between gap-4 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ panne.description || 'Sans description' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Signalée le {{ panne.created_at | moment('DD MMM YYYY') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="panneEtatBadge(panne).classes">
                                    {{ panneEtatBadge(panne).label }}
                                </span>
                                <button @click="editPanne(panne)" class="text-indigo-500 hover:text-indigo-700">
                                    <span class="sr-only">Modifier</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487 19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button @click="deletePanne(panne.id)" class="text-red-500 hover:text-red-700">
                                    <span class="sr-only">Supprimer</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M4.772 5.79c.34-.059.68-.114 1.022-.165m12 0a48.667 48.667 0 0 0-7.5 0m12 .562V19.5A2.25 2.25 0 0 1 16.5 21h-9A2.25 2.25 0 0 1 5.25 18.75V5.79" />
                                    </svg>
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div v-else
                        class="mt-6 rounded-2xl border-2 border-dashed border-gray-200 p-8 text-center dark:border-white/10">
                        <p class="text-sm text-gray-500">Aucune panne signalée pour le moment.</p>
                        <button type="button" @click="openPanneModal"
                            class="mt-3 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                            Ajouter une première panne
                        </button>
                    </div>
                </article>

                <article
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900/40">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Contrats récents</h2>
                    <p class="text-sm text-gray-500">Les trois derniers contrats liés à ce contractable.</p>
                    <ul v-if="contrats.length" class="mt-6 divide-y divide-gray-100 dark:divide-white/5">
                        <li v-for="contrat in contrats" :key="contrat.id"
                            class="flex items-start justify-between gap-4 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    <a :href="'/contrat/' + contrat.id" class="hover:underline">
                                        {{ contrat.numéro || contrat.numero }}
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ contrat.du | moment('DD MMM YY') }} — {{ contrat.au | moment('DD MMM YY') }}
                                </p>
                            </div>
                            <div v-if="contrat.client" class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ contrat.client.nom }}
                                </p>
                                <p class="text-xs text-gray-500">{{ contrat.client.phone1 }}</p>
                            </div>
                        </li>
                    </ul>
                    <div v-else
                        class="mt-6 rounded-xl border border-dashed border-gray-200 p-6 text-center dark:border-white/10">
                        <p class="text-sm text-gray-500">Aucun contrat trouvé.</p>
                        <a :href="'/contrats/create?contractable_id=' + (contractable ? contractable.id : '')"
                            class="mt-3 inline-flex items-center rounded-lg border border-indigo-200 px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 dark:border-indigo-500/30 dark:text-indigo-300 dark:hover:bg-indigo-500/5">
                            Créer un contrat
                        </a>
                    </div>
                </article>
            </section>

            <!-- Documents & Accessoires -->
            <section class="grid gap-6 lg:grid-cols-2">
                <article
                    class="lg:h-96 overflow-scroll rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900/40">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Documents rattachés</h2>
                            <p class="text-sm text-gray-500">Déclarez les documents obligatoires et leurs dates
                                d’expiration.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-400">{{ documents.length }} document(s)</span>
                            <button type="button" @click="openDocumentModal"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                                Rattacher un document
                            </button>
                        </div>
                    </div>
                    <ul v-if="documents.length" class="mt-6 space-y-4">
                        <li v-for="document in documents" :key="document.id"
                            class="rounded-xl border border-gray-100 p-4 shadow-sm dark:border-white/5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ document.type || document.nom }}
                                    </p>
                                    <p v-if="document.pivot && document.pivot.date_expiration"
                                        class="text-xs text-gray-500">
                                        Expire le {{ document.pivot.date_expiration | moment('DD MMM YYYY') }}
                                    </p>
                                    <p v-else class="text-xs text-gray-400">Date d’expiration non renseignée</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                        :class="documentStatusBadge(document).classes">
                                        {{ documentStatusBadge(document).label }}
                                    </span>
                                    <button type="button" @click="openDocumentModal(document)"
                                        class="rounded-lg border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50 dark:border-white/10 dark:text-gray-300">
                                        Modifier
                                    </button>
                                    <button type="button" @click="deleteDocument(document)"
                                        class="rounded-lg border border-red-200 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div v-else
                        class="mt-6 rounded-2xl border-2 border-dashed border-gray-200 p-8 text-center dark:border-white/10">
                        <p class="text-sm text-gray-500">Aucun document n’est rattaché.</p>
                        <button type="button" @click="openDocumentModal"
                            class="mt-4 inline-flex items-center rounded-lg border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 dark:border-indigo-500/30 dark:text-indigo-300 dark:hover:bg-indigo-500/5">
                            Ajouter un document
                        </button>
                    </div>
                </article>

                <article
                    class="lg:h-96 overflow-scroll rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900/40">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Accessoires disponibles</h2>
                            <p class="text-sm text-gray-500">Suivez les accessoires fournis et leurs quantités.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-400">{{ accessoires.length }}
                                accessoire(s)</span>
                            <button type="button" @click="openAccessoireModal"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                                Ajouter un accessoire
                            </button>
                        </div>
                    </div>
                    <ul v-if="accessoires.length" class="mt-6 space-y-4">
                        <li v-for="accessoire in accessoires" :key="accessoire.id"
                            class="rounded-xl border border-gray-100 p-4 shadow-sm dark:border-white/5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ accessoire.type }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        Quantité : {{ accessoireQuantity(accessoire) }}
                                    </span>
                                    <button type="button" @click="openAccessoireModal(accessoire)"
                                        class="rounded-lg border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50 dark:border-white/10 dark:text-gray-300">
                                        Modifier
                                    </button>
                                    <button type="button" @click="deleteAccessoire(accessoire)"
                                        class="rounded-lg border border-red-200 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div v-else
                        class="mt-6 rounded-2xl border-2 border-dashed border-gray-200 p-8 text-center dark:border-white/10">
                        <p class="text-sm text-gray-500">Aucun accessoire enregistré.</p>
                        <button type="button" @click="openAccessoireModal"
                            class="mt-4 inline-flex items-center rounded-lg border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 dark:border-indigo-500/30 dark:text-indigo-300 dark:hover:bg-indigo-500/5">
                            Ajouter un accessoire
                        </button>
                    </div>
                </article>
            </section>
        </div>
        <div v-else
            class="mt-20 rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center dark:border-white/10">
            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                Contractable introuvable
            </p>
            <p class="mt-2 text-sm text-gray-500">
                Vérifiez l’identifiant ou retournez à la liste des contractables.
            </p>
            <a href="/contractables"
                class="mt-6 inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                Revenir à la liste
            </a>
        </div>

        <!-- Modal -->
        <div v-if="showPanneModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="closePanneModal"></div>
            <div class="relative z-10 w-11/12 max-w-xl rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Signaler une panne</h3>
                        <p class="text-sm text-gray-500">Décrivez brièvement le problème rencontré.</p>
                    </div>
                    <button @click="closePanneModal" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Fermer</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form class="mt-6 space-y-4" @submit.prevent="submitFormulairePannes">
                    <div>
                        <label for="panne-description" class="text-sm font-medium text-gray-700">Description</label>
                        <textarea id="panne-description" v-model="form_panne.description" rows="3"
                            class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closePanneModal"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Document modal -->
        <div v-if="showDocumentModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="closeDocumentModal"></div>
            <div class="relative z-10 w-11/12 max-w-xl rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ form_document.id ? 'Modifier le document' : 'Rattacher un document' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            Sélectionnez le document et définissez la date d’expiration.
                        </p>
                    </div>
                    <button @click="closeDocumentModal" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Fermer</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form class="mt-6 space-y-4" @submit.prevent="submitDocument">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Document</label>
                        <select v-model="form_document.document_id" :disabled="!!form_document.id"
                            class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <option value="" disabled>Sélectionner un document</option>
                            <option v-for="document in documentOptions" :key="document.id" :value="document.id">
                                {{ document.type || document.nom }}
                            </option>
                        </select>
                        <p v-if="!form_document.id && !documentOptions.length" class="mt-2 text-xs text-gray-500">
                            Tous les documents disponibles sont déjà rattachés.
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Date d’expiration</label>
                        <input type="date" v-model="form_document.date_expiration"
                            class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeDocumentModal"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 disabled:opacity-50"
                            :disabled="!form_document.document_id && !form_document.id">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Accessoire modal -->
        <div v-if="showAccessoireModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="closeAccessoireModal"></div>
            <div class="relative z-10 w-11/12 max-w-xl rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ form_accessoire.id ? 'Modifier un accessoire' : 'Ajouter un accessoire' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            Indiquez l’accessoire fourni et la quantité remise.
                        </p>
                    </div>
                    <button @click="closeAccessoireModal" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Fermer</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form class="mt-6 space-y-4" @submit.prevent="submitAccessoire">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Accessoire</label>
                        <select v-model="form_accessoire.accessoire_id" :disabled="!!form_accessoire.id"
                            class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <option value="" disabled>Sélectionner un accessoire</option>
                            <option v-for="accessoire in accessoireOptions" :key="accessoire.id" :value="accessoire.id">
                                {{ accessoire.nom || accessoire.name }}
                            </option>
                        </select>
                        <p v-if="!form_accessoire.id && !accessoireOptions.length" class="mt-2 text-xs text-gray-500">
                            Tous les accessoires disponibles sont déjà rattachés.
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Quantité</label>
                        <input type="number" min="1" v-model.number="form_accessoire.quantite"
                            class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeAccessoireModal"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 disabled:opacity-50"
                            :disabled="(!form_accessoire.id && !form_accessoire.accessoire_id) || !form_accessoire.quantite">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        contractable_prop: {
            type: Object,
            default: null,
        },
        pannes_prop: {
            type: Array,
            default: () => [],
        },
        contrats_prop: {
            type: [Array, Object],
            default: () => [],
        },
        documents_prop: {
            type: Array,
            default: () => [],
        },
        accessoires_prop: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            contractable: null,
            pannes: [],
            contrats: [],
            documents: [],
            accessoires: [],
            documentsAvailable: [],
            accessoiresAvailable: [],
            showPanneModal: false,
            showDocumentModal: false,
            showAccessoireModal: false,
            form_panne: {
                id: null,
                description: '',
                contractable_id: null,
            },
            form_document: {
                id: null,
                document_id: '',
                date_expiration: '',
            },
            form_accessoire: {
                id: null,
                accessoire_id: '',
                quantite: 1,
            },
        };
    },
    computed: {
        displayName() {
            if (!this.contractable) {
                return 'Contractable';
            }
            return this.contractable.immatriculation || this.contractable.nom || this.contractable.name || 'Contractable';
        },
        secondaryLabel() {
            if (!this.contractable) {
                return '';
            }
            const parts = [this.contractable.marque, this.contractable.type].filter(Boolean);
            return parts.join(' • ');
        },
        photoUrl() {
            if (this.contractable && this.contractable.photo_url) {
                return this.contractable.photo_url;
            }
            return '/img/car.png';
        },
        typeLabel() {
            if (!this.contractable) {
                return 'Contractable';
            }
            return this.contractable.type ? this.contractable.type : 'Contractable';
        },
        hasGallery() {
            return this.galleryImages.length > 0;
        },
        galleryImages() {
            if (this.contractable && Array.isArray(this.contractable.images)) {
                return this.contractable.images;
            }
            return [];
        },
        etatBadge() {
            const etat = this.contractable && this.contractable.etat ? this.contractable.etat : 'non défini';
            const state = etat.toLowerCase();
            const variants = {
                disponible: {
                    label: 'Disponible',
                    classes: 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                },
                loué: {
                    label: 'Loué',
                    classes: 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
                },
                maintenance: {
                    label: 'En maintenance',
                    classes: 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                },
                vendu: {
                    label: 'Vendu',
                    classes: 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300',
                },
            };
            if (variants[state]) {
                return variants[state];
            }
            return {
                label: etat || 'Non défini',
                classes: 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300',
            };
        },
        documentOptions() {
            if (!this.documentsAvailable.length) {
                return [];
            }
            if (this.form_document.id) {
                return this.documentsAvailable;
            }
            const attachedIds = this.documents.map(document => document.id);
            return this.documentsAvailable.filter(document => !attachedIds.includes(document.id));
        },
        accessoireOptions() {
            if (!this.accessoiresAvailable.length) {
                return [];
            }
            if (this.form_accessoire.id) {
                return this.accessoiresAvailable;
            }
            const attachedIds = this.accessoires.map(accessoire => accessoire.id);
            return this.accessoiresAvailable.filter(accessoire => !attachedIds.includes(accessoire.id));
        },
    },
    watch: {
        contractable_prop: {
            immediate: true,
            handler(value) {
                this.contractable = value || null;
                this.pannes = value && value.pannes ? this.cloneArray(value.pannes) : this.cloneArray(this.pannes_prop);
                this.documents = value && value.documents ? this.cloneArray(value.documents) : [];
                this.accessoires = value && value.accessoires ? this.cloneArray(value.accessoires) : [];
                this.form_panne.contractable_id = value && value.id ? value.id : null;
            },
        },
        contrats_prop: {
            immediate: true,
            handler(value) {
                this.contrats = this.normalizeContrats(value);
            },
        },
        pannes_prop(value) {
            if (!this.pannes.length && value && value.length) {
                this.pannes = this.cloneArray(value);
            }
        },
        documents_prop: {
            immediate: true,
            handler(value) {
                this.documentsAvailable = this.cloneArray(value);
            },
        },
        accessoires_prop: {
            immediate: true,
            handler(value) {
                this.accessoiresAvailable = this.cloneArray(value);
            },
        },
    },
    methods: {
        cloneArray(source) {
            return Array.isArray(source) ? source.map(item => ({ ...item })) : [];
        },
        normalizeContrats(contrats) {
            if (!contrats) {
                return [];
            }
            if (Array.isArray(contrats)) {
                return contrats;
            }
            if (Array.isArray(contrats.data)) {
                return contrats.data;
            }
            if (Array.isArray(contrats.items)) {
                return contrats.items;
            }
            return [];
        },
        openPanneModal() {
            this.showPanneModal = true;
        },
        closePanneModal() {
            this.showPanneModal = false;
            this.resetPanneForm();
        },
        resetPanneForm() {
            this.form_panne = {
                id: null,
                description: '',
                contractable_id: this.contractable && this.contractable.id ? this.contractable.id : null,
            };
        },
        editPanne(panne) {
            this.form_panne = {
                id: panne.id,
                description: panne.description || '',
                contractable_id: this.contractable && this.contractable.id ? this.contractable.id : null,
            };
            this.showPanneModal = true;
        },
        deletePanne(id) {
            this.$swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action est définitive.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                axios.delete('/api/panne/' + id).then(() => {
                    this.pannes = this.pannes.filter(panne => panne.id !== id);
                    this.syncContractablePannes();
                    this.$swal.fire('Supprimée', 'La panne a été supprimée.', 'success');
                });
            });
        },
        submitFormulairePannes() {
            if (!this.form_panne.description.trim()) {
                return;
            }
            const payload = { ...this.form_panne };
            if (payload.id) {
                axios.put('/api/panne/' + payload.id, payload).then(({ data }) => {
                    const index = this.pannes.findIndex(panne => panne.id === data.id);
                    if (index !== -1) {
                        this.$set(this.pannes, index, data);
                    }
                    this.syncContractablePannes();
                    this.closePanneModal();
                });
                return;
            }
            axios.post('/api/pannes', payload).then(({ data }) => {
                this.pannes.unshift(data);
                this.syncContractablePannes();
                this.closePanneModal();
            });
        },
        syncContractablePannes() {
            if (!this.contractable) {
                return;
            }
            this.$set(this.contractable, 'pannes', this.pannes);
        },
        openDocumentModal(document = null) {
            if (!this.contractable) {
                return;
            }
            if (document) {
                this.form_document = {
                    id: document.id,
                    document_id: document.id,
                    date_expiration: this.formatDateForInput(document.pivot && document.pivot.date_expiration ? document.pivot.date_expiration : ''),
                };
            } else {
                this.resetDocumentForm();
            }
            this.showDocumentModal = true;
        },
        closeDocumentModal() {
            this.showDocumentModal = false;
            this.resetDocumentForm();
        },
        resetDocumentForm() {
            this.form_document = {
                id: null,
                document_id: '',
                date_expiration: '',
            };
        },
        submitDocument() {
            if (!this.contractable) {
                return;
            }
            const targetDocumentId = this.form_document.document_id || this.form_document.id;
            if (!targetDocumentId) {
                return;
            }
            const payload = {
                document_id: targetDocumentId,
                date_expiration: this.form_document.date_expiration || null,
            };
            const url = this.form_document.id
                ? `/api/contractables/${this.contractable.id}/documents/${this.form_document.id}`
                : `/api/contractables/${this.contractable.id}/documents`;
            const request = this.form_document.id ? axios.put(url, payload) : axios.post(url, payload);

            request.then(({ data }) => {
                this.upsertDocument(data);
                this.closeDocumentModal();
            });
        },
        deleteDocument(document) {
            if (!this.contractable || !document) {
                return;
            }
            this.$swal.fire({
                title: 'Supprimer ce document ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                axios.delete(`/api/contractables/${this.contractable.id}/documents/${document.id}`).then(() => {
                    this.documents = this.documents.filter(item => item.id !== document.id);
                });
            });
        },
        upsertDocument(document) {
            if (!document) {
                return;
            }
            const index = this.documents.findIndex(item => item.id === document.id);
            if (index === -1) {
                this.documents.push(document);
                return;
            }
            this.$set(this.documents, index, document);
        },
        openAccessoireModal(accessoire = null) {
            if (!this.contractable) {
                return;
            }
            if (accessoire) {
                this.form_accessoire = {
                    id: accessoire.id,
                    accessoire_id: accessoire.id,
                    quantite: Number(this.accessoireQuantity(accessoire)) || 1,
                };
            } else {
                this.resetAccessoireForm();
            }
            this.showAccessoireModal = true;
        },
        closeAccessoireModal() {
            this.showAccessoireModal = false;
            this.resetAccessoireForm();
        },
        resetAccessoireForm() {
            this.form_accessoire = {
                id: null,
                accessoire_id: '',
                quantite: 1,
            };
        },
        submitAccessoire() {
            if (!this.contractable || !this.form_accessoire.quantite) {
                return;
            }
            const targetAccessoireId = this.form_accessoire.accessoire_id || this.form_accessoire.id;
            if (!targetAccessoireId) {
                return;
            }
            const payload = {
                accessoire_id: targetAccessoireId,
                quantite: this.form_accessoire.quantite,
            };
            const url = this.form_accessoire.id
                ? `/api/contractables/${this.contractable.id}/accessoires/${this.form_accessoire.id}`
                : `/api/contractables/${this.contractable.id}/accessoires`;
            const request = this.form_accessoire.id ? axios.put(url, payload) : axios.post(url, payload);

            request.then(({ data }) => {
                this.upsertAccessoire(data);
                this.closeAccessoireModal();
            });
        },
        deleteAccessoire(accessoire) {
            if (!this.contractable || !accessoire) {
                return;
            }
            this.$swal.fire({
                title: 'Supprimer cet accessoire ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                axios.delete(`/api/contractables/${this.contractable.id}/accessoires/${accessoire.id}`).then(() => {
                    this.accessoires = this.accessoires.filter(item => item.id !== accessoire.id);
                });
            });
        },
        upsertAccessoire(accessoire) {
            if (!accessoire) {
                return;
            }
            const index = this.accessoires.findIndex(item => item.id === accessoire.id);
            if (index === -1) {
                this.accessoires.push(accessoire);
                return;
            }
            this.$set(this.accessoires, index, accessoire);
        },
        panneEtatBadge(panne) {
            const etat = panne && panne.etat ? panne.etat : 'non défini';
            const state = etat.toLowerCase();
            const variants = {
                'non-résolue': {
                    label: 'Non résolue',
                    classes: 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                },
                'non resolue': {
                    label: 'Non résolue',
                    classes: 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                },
                'en-maintenance': {
                    label: 'En maintenance',
                    classes: 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                },
                résolue: {
                    label: 'Résolue',
                    classes: 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                },
            };
            if (variants[state]) {
                return variants[state];
            }
            return {
                label: etat || 'Non défini',
                classes: 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300',
            };
        },
        accessoireQuantity(accessoire) {
            if (!accessoire || !accessoire.pivot) {
                return '—';
            }
            if (typeof accessoire.pivot.quantité !== 'undefined') {
                return accessoire.pivot.quantité;
            }
            if (typeof accessoire.pivot.quantite !== 'undefined') {
                return accessoire.pivot.quantite;
            }
            return '—';
        },
        documentStatusBadge(document) {
            const pivot = document && document.pivot ? document.pivot : null;
            const expiration = pivot && pivot.date_expiration ? pivot.date_expiration : null;
            if (!expiration) {
                return {
                    label: 'Date manquante',
                    classes: 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300',
                };
            }
            const isValid = new Date(expiration) >= new Date();
            return isValid
                ? {
                    label: 'Valide',
                    classes: 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                }
                : {
                    label: 'Expiré',
                    classes: 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                };
        },
        formatDateForInput(value) {
            if (!value) {
                return '';
            }
            if (this.$moment) {
                return this.$moment(value).format('YYYY-MM-DD');
            }
            try {
                return new Date(value).toISOString().slice(0, 10);
            } catch (error) {
                return '';
            }
        },
    },
};
</script>
