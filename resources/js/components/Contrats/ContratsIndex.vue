<script>
import CreeContrats from './CreeContrats.vue';

export default {
    props: ['contrats_prop', 'env', 'voitures_prop', 'clients_prop'],
    data() {
        return {
            contrats: null,
            editPaiement: true,
            print_options: false,
            modal: '',
            modalData: {
                'paiement': null,
                'contrat': null
            },
            filters: {
                chambre: {
                    nom: ''
                },
                voiture: {
                    immatriculation: ''
                },
                client: {
                    nom_complet: ''
                },
                etat: false
            },
            ajouterPaiement: {
                payer_avec_caution: false,
                montant: 0,
                note: ''
            },
            search: {
                etat: 'En Cours'
            }
        }

    },
    methods: {
        displayModal(modal, data) {
            this.modalData.contrat = data


            this.modal = modal
            this.$forceUpdate()


        },
        relocateTo(location) {
            window.location = location
        },
        envoieACashier(payload) {
            console.log(payload)
            var data;
            data = {
                'objet': 'Location ' + payload.voiture.marque + ' ' + payload.voiture.type + ' ' + payload.voiture.immatriculation,
                'échéance': payload.du,
                'quantité': payload.nombre_jours,
                'description': 'Jours',
                'prix_unitaire': payload.prix_journalier,
                'client': payload.client.cashier_id
            };
            var link = 'https://cashier.azimuts.ga/api/facture'
            if (this.env === 'local') {
                link = 'http://thecashier.test/api/facture'
            }
            axios.post(link, data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id
                if (cashier_id !== null) {
                    axios.post('/contrat/' + payload.id + '/update-cashier-id', { cashier_id: this.cashier_id }).then(response => {
                        window.location.reload()
                    }).catch(error => {
                        console.log(error);
                    });
                }
            }).catch(error => {
                console.log(error);
            });
        },
        annulerContrat(contrat) {
            this.$swal({
                title: 'Êtes-vous sûr?',
                text: 'Cette action est irréversible',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, Supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/contrats/' + contrat.id).then(response => {
                        this.$swal(
                            'Supprimé!',
                            'Paiement supprimé',
                            'success'
                        )
                        location.reload()
                    }).catch(error => {
                        console.log(error);
                    });

                }
            })


        },
        showModal(modal) {
            this.modal = modal
        },
        closeModal() {
            this.modal = ''
        },
        toggle(data) {
            this[data] = !this[data]
        },
        total(contrat) {
            return contrat.nombre_jours * contrat.prix_journalier + contrat.demi_journee + contrat.montant_chauffeur
        },
        payé(contrat) {
            if (contrat.paiements && contrat.paiements.length > 0) {
                return contrat.paiements.reduce((total, paiement) => {
                    return total + paiement.montant
                }, 0)
            }
            return 0;
        },
        solde(contrat) {
            return this.total(contrat) - this.payé(contrat)
        },
        passDataToModal(type, data) {
            this.modalData[type] = data
            this.$forceUpdate()
            console.log(this.modalData)
            this.showModal('prolongation-modal')
        },
        deletePayment(paiement) {
            this.$swal({
                title: 'Êtes-vous sûr?',
                text: 'Cette action est irréversible',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, Supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/paiement/' + paiement.id)
                        .then(response => {
                            this.$swal(
                                'Supprimé!',
                                'Paiement supprimé',
                                'success'
                            )
                            window.location.reload()
                        })

                }
            })
        },
        deleteData(link) {
            this.$swal({
                title: 'Êtes-vous sûr?',
                text: 'Cette action est irréversible',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, Supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(link)
                        .then(response => {
                            this.$swal(
                                'Supprimé!',
                                'Paiement supprimé',
                                'success'
                            )
                            window.location.reload()
                        })

                }
            })
        }
    },
    computed: {

    },
    watch: {
        'search.etat': function (newVal, oldVal) {

        }
    },
    mounted() {
        this.contrats = this.contrats_prop.data
    }

}
</script>
