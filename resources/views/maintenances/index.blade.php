@extends('layouts.app')


@section('content')
    <maintenance-index inline-template>
        <template>
            <div class="p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Maintenance</h2>
                    <button @click="openAddModal()"
                        class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-500">
                        + Ajouter une maintenance
                    </button>
                </div>

                <!-- Filtres -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                    <select v-model="filters.voiture_id" class="border rounded p-2 w-full">
                        <option value="">-- Voiture --</option>
                        <option v-for="v in voitures" :key="v.id" :value="v.id">@{{ v.immatriculation }}
                        </option>
                    </select>

                    <select v-model="filters.technicien_id" class="border rounded p-2 w-full">
                        <option value="">-- Technicien --</option>
                        <option v-for="t in techniciens" :key="t.id" :value="t.id">
                            @{{ t.nom }}</option>
                    </select>

                    <input type="date" v-model="filters.start" class="border rounded p-2 w-full" />
                    <input type="date" v-model="filters.end" class="border rounded p-2 w-full" />
                </div>

                <!-- Tableau des maintenances -->
                <div class="overflow-hidden bg-white shadow ring-1 ring-gray-900/5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Voiture</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Technicien</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Titre</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Coût</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Coût Pièces</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="m in filteredMaintenances" :key="m.id" class="hover:bg-gray-50">
                                <td class="px-4 py-2">@{{ m.contractable ? m.contractable.immatriculation : '-' }}</td>
                                <td class="px-4 py-2">@{{ m.technicien ? m.technicien.nom : '-' }}</td>
                                <td class="px-4 py-2">@{{ m.titre || '-' }}</td>
                                <td class="px-4 py-2">@{{ formatMoney(m['coût']) }}</td>
                                <td class="px-4 py-2">@{{ formatMoney(m['coût_pièces']) }}</td>
                                <td class="px-4 py-2">@{{ formatDate(m.created_at) }}</td>
                                <td class="px-4 py-2 flex gap-2">
                                    <button @click="openEditModal(m)" class="text-indigo-600 text-sm">Modifier</button>
                                    <button @click="confirmDelete(m.id)" class="text-red-600 text-sm">Supprimer</button>
                                </td>
                            </tr>

                            <tr v-if="filteredMaintenances.length === 0">
                                <td class="px-4 py-6 text-center text-gray-500" colspan="7">Aucune maintenance trouvée.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Ajout / Edition -->
                <div v-if="showModal" class="fixed inset-0 z-40 flex items-start justify-center px-4 pt-10">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>

                    <!-- Modal -->
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl z-50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">
                                @{{ isEditing ? 'Modifier la maintenance' : 'Ajouter une maintenance' }}
                            </h3>
                            <button @click="closeModal" class="text-gray-500 hover:text-gray-700">✕</button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Contractable Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contractable</label>
                                    <select v-model="form.contractable_id" class="border rounded p-2 w-full">
                                        <option value="">-- Choisir --</option>
                                        <option v-for="c in contractables" :key="c.id" :value="c.id">
                                            @{{ c.immatriculation }}
                                        </option>
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">Type déterminé automatiquement.</p>
                                </div>


                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                    <input type="text" v-model="form.titre" class="border rounded p-2 w-full"
                                        placeholder="Ex: Révision freins" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Technicien</label>
                                    <select v-model="form.technicien_id" class="border rounded p-2 w-full">
                                        <option value="">-- Aucun --</option>
                                        <option v-for="t in techniciens" :key="t.id" :value="t.id">
                                            @{{ t.nom }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût</label>
                                    <input type="number" v-model.number="form.cout" class="border rounded p-2 w-full"
                                        placeholder="Ex: 50000" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût pièces</label>
                                    <input type="number" v-model.number="form.cout_pieces"
                                        class="border rounded p-2 w-full" placeholder="Ex: 15000" />
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button @click="closeModal" class="px-4 py-2 bg-gray-200 rounded">Annuler</button>
                                <button @click="submitForm" :disabled="saving"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                                    @{{ saving ? 'Enregistrement...' : (isEditing ? 'Sauvegarder' : 'Créer') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </template>


    </maintenance-index>
@endsection
