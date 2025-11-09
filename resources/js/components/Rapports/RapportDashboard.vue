<template>
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col 2xl:flex-row 2xl:items-end 2xl:justify-between gap-4 mb-6">
            <div class="flex-1">
                <p class="text-xs uppercase tracking-wide text-gray-500">Plage sélectionnée</p>
                <h2 class="text-xl font-semibold text-gray-800">{{ rangeLabel }}</h2>
                <p class="text-sm text-gray-500" v-if="stats">
                    Du {{ $moment(stats.range.start).format('LL') }} au
                    {{ $moment(stats.range.end).format('LL') }}
                </p>
            </div>
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="flex gap-2">
                    <select v-model="selectedPreset" @change="applyPreset" class="form-select w-full sm:w-48">
                        <option disabled value="">Sélectionner une période</option>
                        <option v-for="(label, key) in presets" :key="key" :value="key">
                            {{ label }}
                        </option>
                    </select>
                    <select v-model="selectedCar" @change="applyCarFilter" class="form-select w-full sm:w-56">
                        <option value="">Toutes les voitures</option>
                        <option v-for="voiture in voitures" :key="voiture.id" :value="voiture.id">
                            {{ voiture.label }}
                        </option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" v-model="customStart" class="form-input" />
                    <span class="text-gray-500">→</span>
                    <input type="date" v-model="customEnd" class="form-input" />
                    <button class="btn btn-primary" @click="applyCustomRange">Filtrer</button>
                </div>
            </div>
        </div>

        <div v-if="loading" class="py-16 text-center text-gray-500">
            Chargement des indicateurs...
        </div>

        <div v-else-if="stats">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                <metric-card title="Chiffre d’affaires" icon="💰" :value="formatCurrency(stats.metrics.total_sales)" />
                <metric-card title="Nombre de réservations" icon="📅" :value="stats.metrics.reservations_count" />
                <metric-card title="Taux de conversion" icon="🎯" :value="formatPercent(stats.metrics.conversion_rate)" />
                <metric-card title="Taux d’occupation" icon="🚗" :value="formatPercent(stats.metrics.rental_rate)" />
                <metric-card title="ADR (moy. / jour)" icon="📈" :value="formatCurrency(stats.metrics.average_daily_rate)" />
                <metric-card title="Durée moyenne (jours)" icon="🕓" :value="stats.metrics.average_reservation_length" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="p-4 bg-slate-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">Revenus vs Coûts</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Revenus</span>
                            <span class="font-semibold text-emerald-600">{{ formatCurrency(stats.revenue_vs_costs.revenue) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Coûts de maintenance</span>
                            <span class="font-semibold text-rose-600">{{ formatCurrency(stats.revenue_vs_costs.maintenance_costs) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">ROI</span>
                            <span class="font-semibold text-indigo-600">
                                {{ stats.revenue_vs_costs.roi !== null ? formatPercent(stats.revenue_vs_costs.roi) : '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">Répartition des réservations</h3>
                    <ul class="space-y-2">
                        <li v-for="(count, statut) in stats.status_breakdown" :key="statut" class="flex items-center justify-between">
                            <span class="capitalize text-gray-600">{{ renderStatus(statut) }}</span>
                            <span class="font-semibold text-gray-900">{{ count }}</span>
                        </li>
                        <li v-if="Object.keys(stats.status_breakdown).length === 0" class="text-sm text-gray-500">
                            Aucune donnée pour cette période.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Chronologie des ventes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2">Date</th>
                                <th class="py-2">Ventes</th>
                                <th class="py-2">Réservations</th>
                                <th class="py-2">Jours loués</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in stats.timeline" :key="row.date" class="border-b last:border-b-0">
                                <td class="py-2">{{ $moment(row.date).format('ll') }}</td>
                                <td class="py-2 font-semibold">{{ formatCurrency(row.total_sales) }}</td>
                                <td class="py-2">{{ row.total_reservations }}</td>
                                <td class="py-2">{{ row.total_days }}</td>
                            </tr>
                            <tr v-if="stats.timeline.length === 0">
                                <td colspan="4" class="py-4 text-center text-gray-500">Pas de données pour afficher la chronologie.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else class="py-10 text-center text-gray-500">
            Impossible de charger les statistiques. Veuillez réessayer.
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name: 'RapportDashboard',
    props: {
        statsUrl: { type: String, required: true },
        presets: { type: Object, required: true },
        voitures: { type: Array, default: () => [] },
        defaultPreset: { type: String, default: 'this_month' },
    },
    data() {
        return {
            stats: null,
            loading: true,
            error: null,
            selectedPreset: this.defaultPreset,
            customStart: '',
            customEnd: '',
            selectedCar: '',
        }
    },
    computed: {
        rangeLabel() {
            return this.stats ? this.stats.range.label : '—'
        },
    },
    components: {
        MetricCard: {
            props: ['title', 'value', 'icon'],
            template: `
                <div class="p-4 rounded-lg border border-slate-100 bg-gradient-to-br from-white to-slate-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">{{ title }}</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ value }}</p>
                        </div>
                        <div class="text-3xl">
                            <span>{{ icon }}</span>
                        </div>
                    </div>
                </div>
            `,
        },
    },
    mounted() {
        this.fetchStats(this.buildParams())
    },
    methods: {
        async fetchStats(params = {}) {
            try {
                this.loading = true
                const query = { ...params }
                if (this.selectedCar) {
                    query.voiture_id = this.selectedCar
                }
                const { data } = await axios.get(this.statsUrl, { params: query })
                this.stats = {
                    ...data,
                    timeline: data.timeline || [],
                    status_breakdown: data.status_breakdown || {},
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Impossible de charger les statistiques.'
                this.$swal && this.$swal(this.error)
            } finally {
                this.loading = false
            }
        },
        applyPreset() {
            this.customStart = ''
            this.customEnd = ''
            this.fetchStats(this.buildParams())
        },
        applyCarFilter() {
            this.fetchStats(this.buildParams())
        },
        applyCustomRange() {
            if (!this.customStart || !this.customEnd) {
                this.$swal && this.$swal('Veuillez sélectionner une date de début et de fin.')
                return
            }
            this.selectedPreset = ''
            this.fetchStats(this.buildParams())
        },
        renderStatus(key) {
            const labels = {
                en_attente: 'En attente',
                confirmee: 'Confirmée',
                en_cours: 'En cours',
                annulee: 'Annulée',
                convertie: 'Convertie',
            }
            return labels[key] || key
        },
        formatCurrency(value) {
            if (value === null || value === undefined) return '—'
            const formatter = new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'XAF',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            })
            return formatter.format(value)
        },
        formatPercent(value) {
            if (value === null || value === undefined) return '—'
            return `${value}%`
        },
        buildParams() {
            const params = {}
            if (this.selectedPreset) {
                params.preset = this.selectedPreset
            } else if (this.customStart && this.customEnd) {
                params.start_date = this.customStart
                params.end_date = this.customEnd
            }
            return params
        },
    },
}
</script>

<style scoped>
.form-select,
.form-input {
    border: 1px solid #cbd5f5;
    border-radius: 6px;
    font-size: 0.875rem;
    padding: 0.45rem 0.75rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-select:focus,
.form-input:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.15);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.btn-primary {
    background-color: #059669;
    color: #fff;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background-color: #047857;
}
</style>
