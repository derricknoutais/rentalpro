<template>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tableau des réservations</h1>
                <p class="text-sm text-gray-500">Visualisez les véhicules réservés, leurs statuts et convertissez une
                    demande en contrat en un clic.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="flex gap-2 flex-wrap sm:flex-nowrap">
                    <div class="w-full sm:w-56">
                        <multiselect v-model="filterContractableOption" :options="contractableSelectOptions"
                            label="label" track-by="value" placeholder="Tous les contractables" :allow-empty="true"
                            :show-labels="false" class="w-full" />
                    </div>
                    <div class="w-full sm:w-48">
                        <multiselect v-model="filterStatusOption" :options="statusSelectOptions" label="label"
                            track-by="value" placeholder="Tous les statuts" :allow-empty="true" :show-labels="false"
                            :searchable="false" class="w-full" />
                    </div>
                </div>
                <button type="button" @click="openForm()"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                    Nouvelle réservation
                </button>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-gray-200">
            <full-calendar ref="calendar" lang="fr" :first-day="1" :events="calendarEvents"
                @changeMonth="handleMonthChange" @eventClick="handleEventClick" @dayClick="handleDayClick" />
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section v-if="selectedReservation" ref="reservationDetails"
                class="rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200 lg:col-span-2">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Réservation
                            #{{ selectedReservation.id }}</p>
                        <h2 class="text-lg font-semibold text-gray-900">{{ selectedReservation.title }}</h2>
                        <p class="text-sm text-gray-500">{{ formatDateRange(selectedReservation.start,
                            selectedReservation.end) }}</p>
                    </div>
                    <span :class="['inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1',
                        statusBadge(selectedReservation.statut)]">
                        {{ selectedReservation.status_label }}
                    </span>
                </div>

                <dl class="mt-6 grid gap-4 text-sm text-gray-700 sm:grid-cols-2">
                    <div>
                        <dt class="font-medium text-gray-500">Contractable</dt>
                        <dd class="mt-1">{{ formatContractable(selectedReservation.contractable) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Client</dt>
                        <dd class="mt-1">
                            {{ selectedReservation.client ? selectedReservation.client.nom + ' ' +
                                (selectedReservation.client.prenom || '') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Durée</dt>
                        <dd class="mt-1">{{ selectedReservation.nombre_jours || 1 }} jour(s)</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Montant chauffeur</dt>
                        <dd class="mt-1">{{ formatMoney(selectedReservation.montant_chauffeur) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Demi-journée</dt>
                        <dd class="mt-1">{{ formatMoney(selectedReservation.demi_journee) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Caution</dt>
                        <dd class="mt-1">{{ formatMoney(selectedReservation.caution) }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500">Note</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ selectedReservation.note || '—' }}</dd>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="button" @click="openForm(selectedReservation)"
                        class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Modifier
                    </button>
                    <button type="button" v-if="selectedReservation.statut !== 'annulee'"
                        @click="changeStatus('annulee')"
                        class="inline-flex items-center rounded-lg border border-rose-100 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                        Annuler
                    </button>
                    <button type="button" @click="changeStatus('confirmee')"
                        class="inline-flex items-center rounded-lg border border-emerald-100 px-3 py-2 text-sm font-semibold text-emerald-600 hover:bg-emerald-50">
                        Confirmer
                    </button>
                    <button type="button" v-if="selectedReservation.statut !== 'convertie'" @click="convertToContract"
                        class="inline-flex items-center rounded-lg border border-indigo-100 px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
                        Créer un contrat
                    </button>
                    <button type="button" @click="deleteReservation"
                        class="inline-flex items-center rounded-lg border border-red-100 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                        Supprimer
                    </button>
                </div>
            </section>
        </div>

        <!-- Form modal -->
        <div v-if="showForm"
            class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 px-4 py-10">
            <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ form.id ? 'Modifier la réservation' : 'Nouvelle réservation' }}
                        </h3>
                        <p class="text-xs text-gray-500">Renseignez les informations principales puis enregistrez.</p>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-600" @click="closeForm">✕</button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="flex items-center justify-between text-sm font-medium text-gray-600">
                                <span>Client</span>
                                <button type="button"
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-500"
                                    @click="openClientModal">
                                    Nouveau client
                                </button>
                            </div>
                            <div class="mt-1">
                                <multiselect v-model="formClientOption" :options="clientSelectOptions" label="label"
                                    track-by="value" placeholder="Sélectionner un client" :allow-empty="true"
                                    :show-labels="false" class="w-full" />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Contractable</label>
                            <div class="mt-1">
                                <multiselect v-model="formContractableOption" :options="contractableSelectOptions"
                                    label="label" track-by="value" placeholder="Choisir…" :allow-empty="false"
                                    :show-labels="false" class="w-full" />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Début</label>
                            <input type="datetime-local" v-model="form.du"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Fin</label>
                            <input type="datetime-local" v-model="form.au"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Montant journalier</label>
                            <input type="number" min="0" step="1" v-model.number="form.montant_journalier"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="Ex: 65000">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 flex items-center justify-between">
                                Total estimé
                                <span v-if="formDurationDays" class="text-xs font-normal text-gray-400">
                                    {{ formDurationDays }} jour(s)
                                </span>
                            </label>
                            <input type="text" :value="formEstimatedTotalDisplay" readonly
                                class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Statut</label>
                            <div class="mt-1">
                                <multiselect v-model="formStatusOption" :options="statusSelectOptions" label="label"
                                    track-by="value" placeholder="Choisir un statut" :allow-empty="false"
                                    :show-labels="false" :searchable="false" class="w-full" />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Caution</label>
                            <input type="number" step="0.01" v-model.number="form.caution"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Montant chauffeur</label>
                            <input type="number" step="0.01" v-model.number="form.montant_chauffeur"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Demi-journée</label>
                            <input type="number" step="0.01" v-model.number="form.demi_journee"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Note</label>
                        <textarea v-model="form.note" rows="3"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                    <button type="button"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        @click="closeForm">Annuler</button>
                    <button type="button"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                        :disabled="saving" @click="submitForm">
                        {{ saving ? 'Enregistrement…' : (form.id ? 'Mettre à jour' : 'Créer') }}
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showClientModal" class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 px-4 py-10">
            <div class="relative w-full max-w-xl rounded-2xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Créer un client</h3>
                        <p class="text-xs text-gray-500">Ajoutez un client sans quitter cette page.</p>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-600" @click="closeClientModal">✕</button>
                </div>
                <form class="px-6 py-6 space-y-4" @submit.prevent="submitNewClient">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nom *</label>
                            <input type="text" v-model="newClient.nom"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <p v-if="clientErrors.nom" class="mt-1 text-xs text-rose-600">@{{ clientErrors.nom[0] }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Prénom</label>
                            <input type="text" v-model="newClient.prenom"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Téléphone *</label>
                            <input type="text" v-model="newClient.numero_telephone"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <p v-if="clientErrors.numero_telephone" class="mt-1 text-xs text-rose-600">
                                @{{ clientErrors.numero_telephone[0] }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Téléphone 2</label>
                            <input type="text" v-model="newClient.numero_telephone2"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <input type="email" v-model="newClient.mail"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Ville</label>
                            <input type="text" v-model="newClient.ville"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Numéro de permis</label>
                            <input type="text" v-model="newClient.numero_permis"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Téléphone 3</label>
                            <input type="text" v-model="newClient.numero_telephone3"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Adresse</label>
                        <textarea v-model="newClient.addresse" rows="3"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Permis (optionnel)</label>
                        <permis-pond folder="permis" @file-processed="setNewClientImage"
                            @file-removed="clearNewClientImage" label="Téléverser le permis"></permis-pond>
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button"
                            class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            @click="closeClientModal">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-60"
                            :disabled="creatingClient">
                            <svg v-if="creatingClient" class="mr-2 h-4 w-4 animate-spin"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        clients: {
            type: Array,
            default: () => []
        },
        contractables: {
            type: Array,
            default: () => []
        },
        statuses: {
            type: Object,
            default: () => ({})
        },
        compagnie: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            calendarEvents: [],
            clientItems: Array.isArray(this.clients) ? [...this.clients] : [],
            filters: {
                contractable_id: null,
                statut: null
            },
            calendarRange: {
                start: null,
                end: null
            },
            selectedReservation: null,
            showForm: false,
            form: this.blankForm(),
            saving: false,
            loading: false,
            rawReservations: [],
            showClientModal: false,
            creatingClient: false,
            clientErrors: {},
            newClient: {
                nom: '',
                prenom: '',
                numero_telephone: '',
                numero_telephone2: '',
                numero_telephone3: '',
                mail: '',
                ville: '',
                addresse: '',
                numero_permis: '',
                image_id: null,
            },
        };
    },
    mounted() {
        this.fetchReservations();
    },
    computed: {
        formDurationDays() {
            if (!this.form.du || !this.form.au) {
                return 0;
            }
            const start = new Date(this.form.du);
            const end = new Date(this.form.au);
            if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
                return 0;
            }
            const diffMs = end.getTime() - start.getTime();
            if (diffMs <= 0) {
                return 0;
            }
            const days = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
            return Math.max(days, 1);
        },
        formEstimatedTotal() {
            const daily = parseFloat(this.form.montant_journalier);
            if (!daily || Number.isNaN(daily)) {
                return 0;
            }
            const days = this.formDurationDays;
            if (!days) {
                return 0;
            }
            return daily * days;
        },
        formEstimatedTotalDisplay() {
            if (!this.formEstimatedTotal) {
                return '—';
            }
            return Number(this.formEstimatedTotal).toLocaleString('fr-FR') + ' FCFA';
        },
        contractableSelectOptions() {
            return (this.contractables || []).map(contractable => ({
                value: contractable.id,
                label: this.formatContractable(contractable),
            }));
        },
        clientSelectOptions() {
            return (this.clientItems || []).map(client => ({
                value: client.id,
                label: [client.nom, client.prenom].filter(Boolean).join(' ').trim() || `Client #${client.id}`,
            }));
        },
        statusSelectOptions() {
            return Object.entries(this.statuses || {}).map(([value, label]) => ({
                value,
                label,
            }));
        },
        filterContractableOption: {
            get() {
                if (!this.filters.contractable_id) {
                    return null;
                }
                return this.contractableSelectOptions.find(option => option.value === this.filters.contractable_id) || null;
            },
            set(option) {
                this.filters.contractable_id = option ? option.value : null;
            },
        },
        filterStatusOption: {
            get() {
                if (!this.filters.statut) {
                    return null;
                }
                return this.statusSelectOptions.find(option => option.value === this.filters.statut) || null;
            },
            set(option) {
                this.filters.statut = option ? option.value : null;
            },
        },
        formClientOption: {
            get() {
                if (!this.form.client_id) {
                    return null;
                }
                return this.clientSelectOptions.find(option => option.value === this.form.client_id) || null;
            },
            set(option) {
                this.form.client_id = option ? option.value : null;
            },
        },
        formContractableOption: {
            get() {
                if (!this.form.contractable_id) {
                    return null;
                }
                return this.contractableSelectOptions.find(option => option.value === this.form.contractable_id) || null;
            },
            set(option) {
                this.form.contractable_id = option ? option.value : null;
            },
        },
        formStatusOption: {
            get() {
                if (!this.form.statut) {
                    return null;
                }
                return this.statusSelectOptions.find(option => option.value === this.form.statut) || null;
            },
            set(option) {
                this.form.statut = option ? option.value : (Object.keys(this.statuses)[0] || 'en_attente');
            },
        }
    },
    watch: {
        clients: {
            immediate: true,
            handler(value) {
                this.clientItems = Array.isArray(value) ? [...value] : [];
            }
        },
        'filters.contractable_id'() {
            this.fetchReservations();
        },
        'filters.statut'() {
            this.fetchReservations();
        }
    },
    methods: {
        blankForm() {
            return {
                id: null,
                client_id: null,
                contractable_id: null,
                du: '',
                au: '',
                montant_journalier: '',
                caution: '',
                demi_journee: '',
                montant_chauffeur: '',
                note: '',
                statut: Object.keys(this.statuses)[0] || 'en_attente'
            };
        },
        formatContractable(contractable) {
            if (!contractable) {
                return '—';
            }
            return contractable.immatriculation || contractable.nom || `#${contractable.id}`;
        },
        formatMoney(value) {
            if (!value) {
                return '—';
            }
            return Number(value).toLocaleString('fr-FR') + ' FCFA';
        },
        formatDateRange(start, end) {
            if (!start || !end) {
                return '—';
            }
            const options = { weekday: 'short', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' };
            return `${new Date(start).toLocaleString('fr-FR', options)} → ${new Date(end).toLocaleString('fr-FR', options)}`;
        },
        statusBadge(status) {
            return {
                'status-pill-pending': status === 'en_attente',
                'status-pill-confirmed': status === 'confirmee',
                'status-pill-progress': status === 'en_cours',
                'status-pill-cancelled': status === 'annulee',
                'status-pill-converted': status === 'convertie'
            };
        },
        statusSwatch(status) {
            return {
                'bg-indigo-300': status === 'en_attente',
                'bg-emerald-400': status === 'confirmee',
                'bg-blue-400': status === 'en_cours',
                'bg-rose-500': status === 'annulee',
                'bg-purple-500': status === 'convertie'
            }[status] || 'bg-slate-400';
        },
        handleMonthChange(start, end) {
            this.calendarRange = {
                start: start ? new Date(start).toISOString().slice(0, 10) : null,
                end: end ? new Date(end).toISOString().slice(0, 10) : null,
            };
            this.fetchReservations();
        },
        handleEventClick(event) {
            const payload = event.original || event;
            this.selectedReservation = payload;
            this.scrollToDetails();
            // this.openForm(payload);
        },
        handleDayClick(day) {
            const iso = new Date(day).toISOString().slice(0, 16);
            this.openForm({
                du: iso,
                au: iso
            });
        },
        fetchReservations() {
            this.loading = true;
            const params = {
                start: this.calendarRange.start,
                end: this.calendarRange.end,
                contractable_id: this.filters.contractable_id || undefined,
                status: this.filters.statut || undefined,
            };
            axios.get('/api/reservations', { params })
                .then(({ data }) => {
                    this.rawReservations = data;
                    this.calendarEvents = data.map(item => ({
                        id: item.id,
                        title: item.title,
                        start: item.start,
                        end: item.end,
                        cssClass: item.cssClass,
                        original: item,
                    }));
                    if (this.selectedReservation) {
                        const refreshed = data.find(res => res.id === this.selectedReservation.id);
                        this.selectedReservation = refreshed || null;
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        openForm(reservation = null) {
            if (reservation) {
                const payload = reservation.original || reservation;
                this.form = {
                    id: payload.id || null,
                    client_id: payload.client ? payload.client.id : '',
                    contractable_id: payload.contractable ? payload.contractable.id : '',
                    du: payload.start ? this.toLocalInput(payload.start) : '',
                    au: payload.end ? this.toLocalInput(payload.end) : '',
                    montant_journalier: payload.montant_journalier || '',
                    caution: payload.caution || '',
                    demi_journee: payload.demi_journee || '',
                    montant_chauffeur: payload.montant_chauffeur || '',
                    note: payload.note || '',
                    statut: payload.statut || Object.keys(this.statuses)[0]
                };
            } else {
                this.form = this.blankForm();
            }
            this.showForm = true;
        },
        closeForm() {
            this.showForm = false;
            this.form = this.blankForm();
        },
        toLocalInput(value) {
            if (!value) {
                return '';
            }
            const date = new Date(value);
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
            return date.toISOString().slice(0, 16);
        },
        submitForm() {
            this.saving = true;
            const payload = {
                client_id: this.form.client_id || null,
                contractable_id: this.form.contractable_id,
                du: this.form.du,
                au: this.form.au,
                caution: this.form.caution,
                demi_journee: this.form.demi_journee,
                montant_chauffeur: this.form.montant_chauffeur,
                note: this.form.note,
                statut: this.form.statut,
            };
            const request = this.form.id
                ? axios.put(`/api/reservations/${this.form.id}`, payload)
                : axios.post('/api/reservations', payload);

            request.then(() => {
                this.saving = false;
                this.closeForm();
                this.fetchReservations();
            }).catch(() => {
                this.saving = false;
                alert("Impossible d'enregistrer la réservation.");
            });
        },
        changeStatus(status) {
            if (!this.selectedReservation) {
                return;
            }
            axios.post(`/api/reservations/${this.selectedReservation.id}/status`, { statut: status })
                .then(({ data }) => {
                    this.selectedReservation = data;
                    this.fetchReservations();
                })
                .catch(() => alert('Impossible de changer le statut.'));
        },
        deleteReservation() {
            if (!this.selectedReservation) {
                return;
            }
            if (!confirm('Supprimer cette réservation ?')) {
                return;
            }
            axios.delete(`/api/reservations/${this.selectedReservation.id}`)
                .then(() => {
                    this.selectedReservation = null;
                    this.fetchReservations();
                })
                .catch(() => alert('Suppression impossible.'));
        },
        convertToContract() {
            if (!this.selectedReservation) {
                return;
            }
            if (!confirm('Créer un contrat à partir de cette réservation ?')) {
                return;
            }
            axios.post(`/api/reservations/${this.selectedReservation.id}/convert`)
                .then(({ data }) => {
                    if (data.redirect_url) {
                        window.location = data.redirect_url;
                    }
                })
                .catch(() => alert('Conversion impossible.'));
        },
        scrollToDetails() {
            this.$nextTick(() => {
                const target = this.$refs.reservationDetails;
                if (target && typeof target.scrollIntoView === 'function') {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        },
        openClientModal() {
            this.resetNewClient();
            this.clientErrors = {};
            this.showClientModal = true;
        },
        closeClientModal() {
            this.showClientModal = false;
            this.clientErrors = {};
        },
        resetNewClient() {
            this.newClient = {
                nom: '',
                prenom: '',
                numero_telephone: '',
                numero_telephone2: '',
                numero_telephone3: '',
                mail: '',
                ville: '',
                addresse: '',
                numero_permis: '',
            };
        },
        setNewClientImage(id) {
            this.newClient.image_id = id;
        },
        clearNewClientImage() {
            this.newClient.image_id = null;
        },
        submitNewClient() {
            this.creatingClient = true;
            this.clientErrors = {};
            axios.post('/api/clients', this.newClient)
                .then(({ data }) => {
                    this.clientItems.unshift(data);
                    this.form.client_id = data.id;
                    this.closeClientModal();
                    if (this.$swal) {
                        this.$swal('Client créé', 'Le client a été ajouté.', 'success');
                    }
                })
                .catch((error) => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.clientErrors = error.response.data.errors;
                    } else if (this.$swal) {
                        this.$swal('Erreur', "Impossible de créer le client.", 'error');
                    }
                })
                .finally(() => {
                    this.creatingClient = false;
                });
        }
    }
};
</script>

<style scoped>
.status-pill-pending {
    background-color: #e0e7ff;
    color: #1d4ed8;
    border-color: #c7d2fe;
}

.status-pill-confirmed {
    background-color: #d1fae5;
    color: #047857;
    border-color: #a7f3d0;
}

.status-pill-progress {
    background-color: #bfdbfe;
    color: #1d4ed8;
    border-color: #bfdbfe;
}

.status-pill-cancelled {
    background-color: #fee2e2;
    color: #b91c1c;
    border-color: #fecaca;
}

.status-pill-converted {
    background-color: #ede9fe;
    color: #6d28d9;
    border-color: #ddd6fe;
}

.event-item.status-en_attente {
    background-color: #e0e7ff;
    color: #1d4ed8;
}

.event-item.status-confirmee {
    background-color: #d1fae5;
    color: #047857;
}

.event-item.status-en_cours {
    background-color: #dbeafe;
    color: #1d4ed8;
}

.event-item.status-annulee {
    background-color: #fee2e2;
    color: #b91c1c;
}

.event-item.status-convertie {
    background-color: #ede9fe;
    color: #6d28d9;
}
</style>
