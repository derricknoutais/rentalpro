<script>
const blankForm = () => ({
    nom: '',
    prenom: '',
    numero_telephone: '',
    numero_telephone2: '',
    numero_telephone3: '',
    numero_permis: '',
    mail: '',
    ville: '',
    addresse: '',
    image_id: null,
});

export default {
    props: {
        initialClients: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            clients: Array.isArray(this.initialClients) ? [...this.initialClients] : [],
            searchQuery: '',
            showCreateModal: false,
            saving: false,
            errors: {},
            form: blankForm(),
            refreshing: false,
            sortField: 'nom',
            sortDirection: 'asc',
        };
    },
    computed: {
        filteredClients() {
            const filtered = this.searchQuery && this.searchQuery.trim().length >= 2
                ? this.clients.filter((client) => {
                    const query = this.searchQuery.toLowerCase();
                    const haystack = [
                        client.nom,
                        client.prenom,
                        client.phone1,
                        client.phone2,
                        client.mail,
                        client.numero_permis,
                    ]
                        .filter(Boolean)
                        .join(' ')
                        .toLowerCase();
                    return haystack.includes(query);
                })
                : [...this.clients];

            return this.sortClients(filtered);
        },
        totalClients() {
            return this.clients.length;
        },
    },
    methods: {
        sortClients(list) {
            const field = this.sortField;
            const direction = this.sortDirection === 'asc' ? 1 : -1;
            const numericFields = ['nombre_locations', 'chiffre_affaire', 'paiements_percus', 'solde'];

            return [...list].sort((a, b) => {
                let aVal = a[field];
                let bVal = b[field];

                if (numericFields.includes(field)) {
                    aVal = Number(aVal) || 0;
                    bVal = Number(bVal) || 0;
                } else {
                    aVal = (aVal || '').toString().toLowerCase();
                    bVal = (bVal || '').toString().toLowerCase();
                }

                if (aVal === bVal) {
                    return 0;
                }
                return (aVal > bVal ? 1 : -1) * direction;
            });
        },
        setSort(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                return;
            }
            this.sortField = field;
            this.sortDirection = 'asc';
        },
        sortIcon(field) {
            if (this.sortField !== field) {
                return '';
            }
            return this.sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
        },
        relocateTo(location) {
            window.location = location;
        },
        clientFullName(client) {
            const fullName = [client.nom, client.prenom].filter(Boolean).join(' ').trim();
            return fullName || `Client #${client.id}`;
        },
        formatCurrency(value) {
            if (!value) {
                return '—';
            }
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'XAF',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(Number(value));
        },
        openCreateModal() {
            this.form = blankForm();
            this.errors = {};
            this.showCreateModal = true;
        },
        closeCreateModal() {
            this.showCreateModal = false;
            this.errors = {};
        },
        createClient() {
            this.saving = true;
            this.errors = {};
            axios
                .post('/api/clients', this.form)
                .then(({ data }) => {
                    this.clients.unshift(data);
                    this.closeCreateModal();
                    this.form = blankForm();
                    if (this.$swal) {
                        this.$swal('Client créé', 'Le client a été ajouté avec succès.', 'success');
                    }
                })
                .catch((error) => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else if (this.$swal) {
                        this.$swal('Erreur', "Impossible de créer le client.", 'error');
                    }
                })
                .finally(() => {
                    this.saving = false;
                });
        },
        fetchClients() {
            this.refreshing = true;
            axios
                .get('/api/clients')
                .then(({ data }) => {
                    this.clients = data;
                })
                .catch(() => {
                    if (this.$swal) {
                        this.$swal('Erreur', 'Impossible de rafraîchir la liste des clients.', 'error');
                    }
                })
                .finally(() => {
                    this.refreshing = false;
                });
        },
        confirmDeleteClient(client) {
            if (!client) {
                return;
            }
            this.$swal({
                title: 'Supprimer ce client ?',
                text: 'Le client sera archivé en douceur.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                this.deleteClient(client);
            });
        },
        deleteClient(client) {
            axios.delete(`/clients/${client.id}`)
                .then(() => {
                    this.clients = this.clients.filter(c => c.id !== client.id);
                    if (this.$swal) {
                        this.$swal('Client supprimé', 'Le client a été archivé.', 'success');
                    }
                })
                .catch(() => {
                    if (this.$swal) {
                        this.$swal('Erreur', 'Impossible de supprimer ce client.', 'error');
                    }
                });
        },
        setCreateClientImage(id) {
            this.form.image_id = id;
        },
        removeCreateClientImage() {
            this.form.image_id = null;
        },
    },
};
</script>
