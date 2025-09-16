<script>
export default {
    props: [
        'contractable_prop',
        'pannes_prop',
        'contrats_prop',
        'documents_prop',
        'accessoires_prop'
    ],
    data() {
        return {
            display_formulaire_panne: false,
            pannes: [],
            form: {
                description: '',
                contractable_id: null,
            },

            contractable: this.contractable_prop,
            contrats: this.contrats_prop,


        }
    },
    methods: {
        toggleFormulairePanne() {
            this.display_formulaire_panne = !this.display_formulaire_panne
        },
        submitFormulairePannes() {
            axios.post('/api/panne', this.form).then((response) => {
                console.log(response.data)
                this.pannes.push(response.data)
                this.$forceUpdate()
            });
        },
        supprimerPanne(id) {

            this.$swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/panne/' + id).then((response) => {
                        console.log(response.data)
                        this.pannes = this.pannes.filter(panne => panne.id !== id)
                        this.$forceUpdate()
                        this.$swal.fire(
                            'Supprimé!',
                            'La panne a été supprimée.',
                            'success'
                        )
                    });
                }
            })


        },
        associerDocument(document) {
            axios.post('/api/associer-document', {
                document_id: document.id,
                voiture_id: this.contractable.id,
                date_expiration: document.pivot ? document.pivot.date_expiration : null
            }).then((response) => {
                console.log(response.data)
                this.documents.push(response.data)
                this.$forceUpdate()
            });
        },
    },
    created() {
        this.documents = this.documents_prop || [];
        this.accessoires = this.accessoires_prop || [];
        this.documents.map(document => {
            return document.pivot = null
        });
        this.accessoires.map(accessoire => {
            return accessoire.pivot = null
        });
    },
    mounted() {
        this.form.contractable_id = this.contractable_prop.id

        if (this.contractable_prop) {
            this.contractable = this.contractable_prop
            this.pannes = this.contractable_prop.pannes
            this.form.contractable_id = this.contractable.id
        } else {
            this.contractable = null
            this.form.contractable_id = null
            this.pannes = []
                ;
        }

        this.$forceUpdate()
    }
}
</script>
