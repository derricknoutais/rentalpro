@extends('layouts.app')


@section('content')
    <maintenance-index inline-template>
        <template>
            <div class="p-6 space-y-6">

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Maintenance</h2>
                        <p class="text-sm text-gray-500">Sélectionnez les pannes à envoyer chez le technicien et suivez
                            les coûts associés.</p>
                    </div>
                    <button @click="openAddModal()"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        <span class="hidden sm:inline">Nouvelle maintenance</span>
                        <span class="sm:hidden">+ Ajouter</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    <select v-model="filters.contractable_id"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">Contractable</option>
                        <option v-for="c in contractables" :key="c.id" :value="c.id">
                            @{{ c.immatriculation || c.nom }}
                        </option>
                    </select>
                    <select v-model="filters.technicien_id"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">Technicien</option>
                        <option v-for="t in techniciens" :key="t.id" :value="t.id">
                            @{{ t.nom }}</option>
                    </select>
                    <select v-model="filters.statut"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">Statut</option>
                        <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                            @{{ status.label }}
                        </option>
                    </select>
                    <input type="date" v-model="filters.start"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                    <input type="date" v-model="filters.end"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-900/5">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Contractable</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Technicien</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Pannes</th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Note</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Total</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <tr v-for="maintenance in filteredMaintenances" :key="maintenance.id"
                                class="hover:bg-gray-50 cursor-pointer" @click="goToMaintenance(maintenance.id)">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-semibold text-gray-900">
                                        @{{ maintenance.contractable ? (maintenance.contractable.immatriculation || maintenance.contractable.nom) : '-' }}
                                    </div>
                                    <p class="text-sm text-gray-500">@{{ formatDate(maintenance.created_at) }}</p>
                                    <p class="text-xs text-gray-500">@{{ maintenance.titre || 'Sans titre' }}</p>
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1"
                                        :class="statusBadge(maintenance.statut).classes">
                                        @{{ statusBadge(maintenance.statut).label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-top text-sm text-gray-700">
                                    @{{ maintenance.technicien ? maintenance.technicien.nom : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <p class="font-medium">
                                        @{{ maintenance.pannes ? maintenance.pannes.length : 0 }} panne(s)
                                    </p>
                                    <ul class="mt-1 space-y-1 text-xs text-gray-500 max-h-24 overflow-y-auto pr-1">
                                        <li v-for="p in maintenance.pannes" :key="p.id" class="flex gap-2">
                                            <span class="text-gray-400">•</span>
                                            <span>@{{ panneLabel(p) }}</span>
                                        </li>
                                        <li v-if="!maintenance.pannes || !maintenance.pannes.length" class="text-gray-400">
                                            Aucune panne</li>
                                    </ul>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">@{{ excerpt(maintenance.note) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @{{ formatMoney(maintenance_total(maintenance)) }}
                                </td>

                                <td class="px-4 py-3 text-right text-sm">
                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <button @click.stop="openEditModal(maintenance)"
                                            class="text-indigo-600 hover:text-indigo-800">Modifier</button>
                                        <button v-if="maintenance.statut !== 'terminé'"
                                            @click.stop="openCompleteModal(maintenance)"
                                            class="text-green-600 hover:text-green-800">Terminer</button>
                                        <button @click.stop="confirmDelete(maintenance.id)"
                                            class="text-red-600 hover:text-red-800">Supprimer</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredMaintenances.length === 0">
                                <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                    Aucune maintenance pour ces filtres.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div v-if="showModal"
                    class="fixed inset-0 z-40 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-10">
                    <div class="fixed inset-0 bg-black/50" @click="closeModal"></div>
                    <div
                        class="relative z-50 w-full max-w-3xl rounded-2xl bg-white shadow-xl ring-1 ring-black/5 dark:bg-slate-900">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    @{{ isEditing ? 'Compléter la maintenance' : 'Créer une maintenance' }}
                                </h3>
                                <p class="text-xs text-gray-500" v-if="!isEditing">
                                    Choisissez un contractable, sélectionnez les pannes à régler puis envoyez-les au
                                    technicien.
                                </p>
                                <p class="text-xs text-gray-500" v-else>
                                    Ajoutez les coûts réels et une note sur l’intervention.
                                </p>
                            </div>
                            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                                <span class="sr-only">Fermer</span>
                                ✕
                            </button>
                        </div>

                        <div class="space-y-6 px-6 py-6">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre (optionnel)</label>
                                    <input type="text" v-model="form.titre"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        placeholder="Ex. Révision freins" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Technicien</label>
                                    <select v-model="form.technicien_id"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">-- Choisir --</option>
                                        <option v-for="t in techniciens" :key="t.id" :value="t.id">
                                            @{{ t.nom }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="!isEditing" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contractable</label>
                                    <select v-model="form.contractable_id" @change="handleContractableChange"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">-- Choisir --</option>
                                        <option v-for="c in contractables" :key="c.id" :value="c.id">
                                            @{{ c.immatriculation || c.nom }}
                                        </option>
                                    </select>
                                </div>

                                <div class="rounded-xl border border-dashed border-gray-200 p-4"
                                    v-if="form.contractable_id">
                                    <div class="flex items-center justify-between text-sm font-medium text-gray-700">
                                        <span>Pannes disponibles</span>
                                        <span class="text-gray-500">
                                            @{{ selectedPanneIds.length }} sélectionnée(s)
                                        </span>
                                    </div>
                                    <div v-if="loadingPannes" class="mt-3 text-sm text-gray-500">
                                        Chargement des pannes...
                                    </div>
                                    <div v-else>
                                        <div v-if="availablePannes.length"
                                            class="mt-3 space-y-2 max-h-56 overflow-y-auto">
                                            <label v-for="panne in availablePannes" :key="panne.id"
                                                class="flex gap-3 rounded-lg border border-gray-100 p-3 text-sm text-gray-700 hover:bg-gray-50">
                                                <input type="checkbox" :value="panne.id" v-model="selectedPanneIds"
                                                    class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <div>
                                                    <p class="font-medium text-gray-900">@{{ panneLabel(panne) }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        Signalée le @{{ formatDate(panne.created_at) }}
                                                    </p>
                                                </div>
                                            </label>
                                        </div>
                                        <p v-else class="mt-3 text-sm text-gray-500">
                                            Aucune panne en attente pour ce contractable.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <p class="text-sm font-medium text-gray-700">Pannes prises en charge</p>
                                <ul class="mt-2 space-y-1 text-sm text-gray-600 max-h-40 overflow-y-auto pr-1">
                                    <li v-for="p in currentMaintenancePannes" :key="p.id" class="flex gap-2">
                                        <span class="text-gray-400">•</span>
                                        <span>@{{ panneLabel(p) }}</span>
                                    </li>
                                    <li v-if="!currentMaintenancePannes.length" class="text-gray-400">
                                        Aucune panne associée
                                    </li>
                                </ul>
                            </div>

                            <div v-if="isEditing" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Main d'oeuvre</label>
                                    <input type="number" v-model.number="form.cout" min="0"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        placeholder="Ex : 50000" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût des pièces</label>
                                    <input type="number" v-model.number="form.cout_pieces" min="0"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        placeholder="Ex : 15000" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                    <select v-model="form.statut"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option v-for="status in statusOptions" :key="status.value"
                                            :value="status.value"
                                            :disabled="status.value === 'terminé' && form.statut !== 'terminé'">
                                            @{{ status.label }}
                                        </option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Clôturez via le bouton « Terminer » pour passer
                                        en
                                        statut terminé.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                                <textarea v-model="form.note" rows="3"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                    placeholder="Ajoutez un détail sur l’intervention"></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-2">
                                <button @click="closeModal"
                                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    Annuler
                                </button>
                                <button @click="submitForm" :disabled="saving"
                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 disabled:opacity-60">
                                    @{{ saving ? 'Enregistrement...' : (isEditing ? 'Mettre à jour' : 'Créer') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Terminer -->
                <div v-if="showCompleteModal"
                    class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-10">
                    <div class="fixed inset-0 bg-black/60" @click="closeCompleteModal"></div>
                    <div class="relative z-50 w-full max-w-2xl rounded-2xl bg-white shadow-xl ring-1 ring-black/5">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Terminer la maintenance</h3>
                                <p class="text-xs text-gray-500">Cochez les pannes totalement résolues. Les autres
                                    reviendront en « non-résolue ».</p>
                            </div>
                            <button @click="closeCompleteModal" class="text-gray-400 hover:text-gray-600">✕</button>
                        </div>
                        <div class="space-y-4 px-6 py-6">
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600">
                                <p class="font-medium text-gray-900">
                                    @{{ completionTarget && completionTarget.contractable ? (completionTarget.contractable.immatriculation || completionTarget.contractable.nom) : 'Contractable' }}
                                </p>
                                <p>Technicien : @{{ completionTarget && completionTarget.technicien ? completionTarget.technicien.nom : '-' }}</p>
                            </div>
                            <div class="max-h-72 space-y-3 overflow-y-auto pr-1">
                                <label v-for="p in currentMaintenancePannes" :key="p.id"
                                    class="flex gap-3 rounded-lg border border-gray-100 p-3 text-sm text-gray-700 hover:bg-gray-50">
                                    <input type="checkbox" :value="p.id" v-model="completionSelection"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="font-medium text-gray-900">@{{ panneLabel(p) }}</p>
                                        <p class="text-xs text-gray-500">Signalée le @{{ formatDate(p.created_at) }}</p>
                                    </div>
                                </label>
                                <p v-if="!currentMaintenancePannes.length" class="text-sm text-gray-500">
                                    Aucune panne associée à cette maintenance.</p>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button @click="closeCompleteModal"
                                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    Annuler
                                </button>
                                <button @click="submitCompletion" :disabled="completing || !completionSelection.length"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500 disabled:opacity-60">
                                    @{{ completing ? 'Validation…' : 'Terminer' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>


    </maintenance-index>
@endsection
