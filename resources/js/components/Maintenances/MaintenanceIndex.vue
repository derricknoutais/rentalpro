<script>
export default {
    name: 'MaintenanceIndex',
    data() {
        return {
            maintenances: [],
            techniciens: [],
            contractables: [],
            availablePannes: [],
            currentMaintenancePannes: [],
            selectedPanneIds: [],
            loadingPannes: false,
            statusOptions: [
                { value: 'en cours', label: 'En cours' },
                { value: 'en pause', label: 'En pause' },
                { value: 'terminé', label: 'Terminé' },
            ],
            filters: {
                contractable_id: null,
                technicien_id: null,
                statut: null,
                start: '',
                end: ''
            },
            showModal: false,
            showCompleteModal: false,
            isEditing: false,
            saving: false,
            completing: false,
            completionTarget: null,
            completionSelection: [],
            form: {
                id: null,
                contractable_id: null,
                titre: '',
                technicien_id: null,
                cout: null,
                cout_pieces: null,
                note: '',
                statut: 'en cours'
            }
        };
    },
    computed: {
        maintenance_total() {
            return (maintenance) => {
                const cout = maintenance['coût'] ? Number(maintenance['coût']) : 0;
                const cout_pieces = maintenance['coût_pièces'] ? Number(maintenance['coût_pièces']) : 0;
                return cout + cout_pieces;
            };
        },
        filteredMaintenances() {
            let list = this.maintenances.slice();

            if (this.filters.contractable_id) {
                const targetId = Number(this.filters.contractable_id);
                list = list.filter(m => m.contractable && Number(m.contractable.id) === targetId);
            }
            if (this.filters.technicien_id) {
                const techId = Number(this.filters.technicien_id);
                list = list.filter(m => m.technicien && Number(m.technicien.id) === techId);
            }
            if (this.filters.statut) {
                list = list.filter(m => (m.statut || 'en cours') === this.filters.statut);
            }
            if (this.filters.start) {
                const start = new Date(this.filters.start);
                list = list.filter(m => new Date(m.created_at) >= start);
            }
            if (this.filters.end) {
                const end = new Date(this.filters.end);
                end.setHours(23, 59, 59, 999);
                list = list.filter(m => new Date(m.created_at) <= end);
            }

            return list.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        },
        contractableOptions() {
            return (this.contractables || []).map(contractable => ({
                value: contractable.id,
                label: contractable.immatriculation || contractable.nom || `Contractable #${contractable.id}`
            }));
        },
        technicienOptions() {
            return (this.techniciens || []).map(technicien => ({
                value: technicien.id,
                label: technicien.nom || `Technicien #${technicien.id}`
            }));
        },
        filterContractableOption: {
            get() {
                if (!this.filters.contractable_id) {
                    return null;
                }
                return this.contractableOptions.find(option => option.value === this.filters.contractable_id) || null;
            },
            set(option) {
                this.filters.contractable_id = option ? option.value : null;
            },
        },
        filterTechnicienOption: {
            get() {
                if (!this.filters.technicien_id) {
                    return null;
                }
                return this.technicienOptions.find(option => option.value === this.filters.technicien_id) || null;
            },
            set(option) {
                this.filters.technicien_id = option ? option.value : null;
            },
        },
        filterStatusOption: {
            get() {
                if (!this.filters.statut) {
                    return null;
                }
                return this.statusOptions.find(option => option.value === this.filters.statut) || null;
            },
            set(option) {
                this.filters.statut = option ? option.value : null;
            },
        },
        formContractableOption: {
            get() {
                if (!this.form.contractable_id) {
                    return null;
                }
                return this.contractableOptions.find(option => option.value === this.form.contractable_id) || null;
            },
            set(option) {
                const previous = this.form.contractable_id;
                this.form.contractable_id = option ? option.value : null;
                if (previous !== this.form.contractable_id) {
                    this.handleContractableChange();
                }
            },
        },
        formTechnicienOption: {
            get() {
                if (!this.form.technicien_id) {
                    return null;
                }
                return this.technicienOptions.find(option => option.value === this.form.technicien_id) || null;
            },
            set(option) {
                this.form.technicien_id = option ? option.value : null;
            },
        },
        formStatusOption: {
            get() {
                if (!this.form.statut) {
                    return null;
                }
                return this.statusOptions.find(option => option.value === this.form.statut) || null;
            },
            set(option) {
                this.form.statut = option ? option.value : 'en cours';
            },
        }
    },
    mounted() {
        this.fetchAll();
    },
    methods: {
        statusDisabled(option) {
            if (!option) {
                return false;
            }
            return option.value === 'terminé' && this.form.statut !== 'terminé';
        },
        fetchAll() {
            this.fetchMaintenances();
            this.fetchTechniciens();
            this.fetchContractables();
        },
        fetchMaintenances() {
            axios.get('/api/maintenances')
                .then(res => {
                    this.maintenances = res.data;
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur en chargeant les maintenances');
                });
        },
        fetchTechniciens() {
            axios.get('/api/techniciens')
                .then(res => {
                    this.techniciens = res.data;
                })
                .catch(err => {
                    console.error(err);
                });
        },
        fetchContractables() {
            axios.get('/api/contractables')
                .then(res => {
                    this.contractables = res.data;
                })
                .catch(err => {
                    console.error(err);
                });
        },
        openAddModal() {
            this.resetForm();
            this.isEditing = false;
            this.availablePannes = [];
            this.currentMaintenancePannes = [];
            this.selectedPanneIds = [];
            this.showModal = true;
        },
        openEditModal(maintenance) {
            this.isEditing = true;
            this.showModal = true;
            this.availablePannes = [];
            this.selectedPanneIds = [];
            this.currentMaintenancePannes = maintenance.pannes || [];

            this.form = {
                id: maintenance.id,
                contractable_id: maintenance.contractable_id || (maintenance.contractable ? maintenance.contractable.id : null),
                titre: maintenance.titre || '',
                technicien_id: maintenance.technicien_id || null,
                cout: maintenance['coût'] ?? null,
                cout_pieces: maintenance['coût_pièces'] ?? null,
                note: maintenance.note || '',
                statut: maintenance.statut || 'en cours'
            };
        },
        closeModal() {
            this.showModal = false;
            this.resetForm();
            this.availablePannes = [];
            this.currentMaintenancePannes = [];
            this.selectedPanneIds = [];
        },
        resetForm() {
            this.form = {
                id: null,
                contractable_id: null,
                titre: '',
                technicien_id: null,
                cout: null,
                cout_pieces: null,
                note: '',
                statut: 'en cours'
            };
            this.saving = false;
        },
        handleContractableChange() {
            if (!this.form.contractable_id) {
                this.availablePannes = [];
                this.selectedPanneIds = [];
                return;
            }
            this.loadContractablePannes(this.form.contractable_id);
        },
        loadContractablePannes(contractableId) {
            this.loadingPannes = true;
            this.availablePannes = [];
            this.selectedPanneIds = [];
            axios.get(`/api/contractables/${contractableId}/pannes`)
                .then(res => {
                    this.availablePannes = res.data;
                })
                .catch(err => {
                    console.error(err);
                    alert('Impossible de charger les pannes pour ce contractable.');
                })
                .finally(() => {
                    this.loadingPannes = false;
                });
        },
        submitForm() {
            if (this.isEditing) {
                this.updateMaintenance();
                return;
            }

            if (!this.form.contractable_id) {
                alert('Veuillez choisir un contractable.');
                return;
            }
            if (!this.form.technicien_id) {
                alert('Veuillez sélectionner un technicien.');
                return;
            }
            if (!this.selectedPanneIds.length) {
                alert('Veuillez sélectionner au moins une panne à traiter.');
                return;
            }

            this.saving = true;
            const payload = {
                contractable_id: this.form.contractable_id,
                technicien_id: this.form.technicien_id,
                titre: this.form.titre || null,
                note: this.form.note || null,
                panne_ids: this.selectedPanneIds
            };

            axios.post('/api/maintenances', payload)
                .then(() => {
                    this.saving = false;
                    this.closeModal();
                    this.fetchMaintenances();
                })
                .catch(err => {
                    console.error(err);
                    this.saving = false;
                    alert('Erreur lors de la création de la maintenance.');
                });
        },
        updateMaintenance() {
            if (!this.form.id) {
                return;
            }

            this.saving = true;
            const payload = {
                titre: this.form.titre || null,
                technicien_id: this.form.technicien_id || null,
                note: this.form.note || null,
            };

            payload['coût'] = this.form.cout !== null && this.form.cout !== '' ? Number(this.form.cout) : null;
            payload['coût_pièces'] = this.form.cout_pieces !== null && this.form.cout_pieces !== '' ? Number(this.form.cout_pieces) : null;

            if (this.form.statut && this.form.statut !== 'terminé') {
                payload['statut'] = this.form.statut;
            }

            axios.put(`/api/maintenances/${this.form.id}`, payload)
                .then(() => {
                    this.saving = false;
                    this.closeModal();
                    this.fetchMaintenances();
                })
                .catch(err => {
                    console.error(err);
                    this.saving = false;
                    alert('Erreur lors de la mise à jour.');
                });
        },
        confirmDelete(id) {
            if (!confirm('Supprimer cette maintenance ?')) return;
            this.deleteMaintenance(id);
        },
        deleteMaintenance(id) {
            axios.delete(`/api/maintenances/${id}`)
                .then(() => {
                    this.fetchMaintenances();
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur lors de la suppression.');
                });
        },
        formatDate(val) {
            if (!val) return '-';
            const d = new Date(val);
            return d.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },
        formatMoney(v) {
            if (v === null || v === undefined || v === '') return '-';
            return Number(v).toLocaleString('fr-FR') + ' FCFA';
        },
        excerpt(text, limit = 80) {
            if (!text) return '-';
            if (text.length <= limit) {
                return text;
            }
            return text.substring(0, limit) + '…';
        },
        panneLabel(panne) {
            if (!panne) {
                return '-';
            }
            if (panne.description) {
                return panne.description;
            }
            return 'Panne #' + panne.id;
        },
        maintenance_total(maintenance) {
            const main = Number(maintenance['coût'] || 0);
            const pieces = Number(maintenance['coût_pièces'] || 0);
            const total = main + pieces;
            return total ? total : null;
        },
        statusBadge(statut) {
            const status = statut || 'en cours';
            const map = {
                'en cours': {
                    label: 'En cours',
                    classes: 'bg-blue-50 text-blue-700 ring-blue-500/20',
                },
                'en pause': {
                    label: 'En pause',
                    classes: 'bg-yellow-50 text-yellow-700 ring-yellow-500/20',
                },
                'terminé': {
                    label: 'Terminé',
                    classes: 'bg-green-50 text-green-700 ring-green-500/20',
                },
            };
            return map[status] || map['en cours'];
        },
        openCompleteModal(maintenance) {
            this.completionTarget = maintenance;
            this.currentMaintenancePannes = maintenance.pannes || [];
            this.completionSelection = this.currentMaintenancePannes.map(p => p.id);
            this.showCompleteModal = true;
        },
        closeCompleteModal() {
            this.showCompleteModal = false;
            this.completionTarget = null;
            this.completionSelection = [];
            this.currentMaintenancePannes = [];
            this.completing = false;
        },
        submitCompletion() {
            if (!this.completionTarget) {
                return;
            }
            if (!this.completionSelection.length) {
                alert('Veuillez sélectionner au moins une panne résolue.');
                return;
            }

            this.completing = true;
            axios.post(`/api/maintenances/${this.completionTarget.id}/complete`, {
                pannes_resolues: this.completionSelection,
            })
                .then(() => {
                    this.completing = false;
                    this.closeCompleteModal();
                    this.fetchMaintenances();
                })
                .catch(err => {
                    console.error(err);
                    this.completing = false;
                    alert('Impossible de terminer cette maintenance.');
                });
        },
        goToMaintenance(id) {
            window.location = `/maintenance/${id}`;
        }
    }
};
</script>
