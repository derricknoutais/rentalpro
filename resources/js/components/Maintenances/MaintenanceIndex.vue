<script>
export default {
    name: 'MaintenanceIndex',
    data() {
        return {
            maintenances: [],
            voitures: [],
            techniciens: [],
            contractables: [], // {id, display, type}
            filters: {
                voiture_id: '',
                technicien_id: '',
                start: '',
                end: ''
            },
            showModal: false,
            isEditing: false,
            saving: false,
            form: {
                id: null,
                contractable_id: '',

                titre: '',
                technicien_id: null,
                voiture_id: null,
                cout: null, // local field mapped to 'coût'
                cout_pieces: null // mapped to 'coût_pièces'
            }
        };
    },
    computed: {
        filteredMaintenances() {
            let list = this.maintenances.slice();

            if (this.filters.voiture_id) {
                list = list.filter(m => m.voiture && m.voiture.id === Number(this.filters.voiture_id));
            }
            if (this.filters.technicien_id) {
                list = list.filter(m => m.technicien && m.technicien.id === Number(this.filters.technicien_id));
            }
            if (this.filters.start) {
                const start = new Date(this.filters.start);
                list = list.filter(m => new Date(m.created_at) >= start);
            }
            if (this.filters.end) {
                const end = new Date(this.filters.end);
                // include the whole day
                end.setHours(23, 59, 59, 999);
                list = list.filter(m => new Date(m.created_at) <= end);
            }

            // newest first
            return list.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
    },
    mounted() {
        this.fetchAll();
    },
    methods: {
        fetchAll() {
            // Parallel fetching
            this.fetchMaintenances();
            this.fetchVoitures();
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

        fetchVoitures() {
            axios.get('/api/voitures')
                .then(res => {
                    this.voitures = res.data;
                })
                .catch(err => {
                    console.error(err);
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
            // contractables should contain type info, ex: { id, display: 'Contrat X', type: 'App\\Models\\Contrat' }
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
            this.showModal = true;
        },

        openEditModal(m) {
            this.isEditing = true;
            this.showModal = true;
            // populate form
            this.form.id = m.id;
            this.form.contractable_id = m.contractable_id;
            this.form.contractable_type = m.contractable_type || '';
            this.form.titre = m.titre;
            this.form.technicien_id = m.technicien_id || null;
            this.form.voiture_id = m.voiture_id || null;
            this.form.cout = m['coût'] || null;
            this.form.cout_pieces = m['coût_pièces'] || null;
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
        },

        resetForm() {
            this.form = {
                id: null,
                contractable_id: '',
                contractable_type: '',
                titre: '',
                technicien_id: null,
                voiture_id: null,
                cout: null,
                cout_pieces: null
            };
            this.saving = false;
        },


        submitForm() {
            // minimal validation: voiture required (you asked voiture obligatoire)
            if (!this.form.contractable_id) {
                alert('Veuillez sélectionner un contractable.');
                return;
            }
            this.saving = true;

            // build payload and map cout fields to the DB column names containing accents
            const payload = {
                contractable_id: this.form.contractable_id || null,
                contractable_type: this.form.contractable_type || null,
                titre: this.form.titre || null,
                technicien_id: this.form.technicien_id || null,
                voiture_id: this.form.voiture_id || null
            };

            // send accented keys explicitly
            payload['coût'] = this.form.cout !== null ? Number(this.form.cout) : null;
            payload['coût_pièces'] = this.form.cout_pieces !== null ? Number(this.form.cout_pieces) : null;

            if (this.isEditing && this.form.id) {
                axios.put(`/api/maintenances/${this.form.id}`, payload)
                    .then(res => {
                        this.saving = false;
                        this.showModal = false;
                        this.fetchMaintenances(); // refresh list
                    })
                    .catch(err => {
                        console.error(err);
                        this.saving = false;
                        alert('Erreur lors de la mise à jour.');
                    });
            } else {
                axios.post('/api/maintenances', payload)
                    .then(res => {
                        this.saving = false;
                        this.showModal = false;
                        this.fetchMaintenances();
                    })
                    .catch(err => {
                        console.error(err);
                        this.saving = false;
                        alert('Erreur lors de la création.');
                    });
            }
        },

        confirmDelete(id) {
            if (!confirm('Supprimer cette maintenance ?')) return;
            this.deleteMaintenance(id);
        },

        deleteMaintenance(id) {
            axios.delete(`/api/maintenances/${id}`)
                .then(() => {
                    // remove locally for snappier UI or refetch
                    this.maintenances = this.maintenances.filter(m => m.id !== id);
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
            if (v === null || v === undefined) return '-';
            return Number(v).toLocaleString('fr-FR') + ' FCFA';
        }
    }
};
</script>

<style scoped>
/* petits ajustements si besoin */
</style>