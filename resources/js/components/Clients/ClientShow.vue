<script>

export default {
    props: {
        client_prop: Object
    },
    data() {
        return {
            client: null
        }
    },
    methods: {
        deleteClient() {
            this.$swal({
                title: 'Êtes-vous sûr?',
                text: "Cette action est irréversible",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, Supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/clients/' + this.client.id)
                        .then(response => {
                            this.$swal(
                                'Supprimé!',
                                'Client supprimé',
                                'success'
                            )
                            window.location.href = '/clients'
                        })

                }
            })
        }
    },
    mounted() {
        if (this.client_prop) {
            this.client = this.client_prop
        }
    }
}
</script>