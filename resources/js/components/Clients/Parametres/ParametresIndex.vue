<script>
export default {
    props: {
        documents_prop: Array,
        accessoires_prop: Array,
        techniciens_prop: Array,
        contractables_prop: Array
    },
    data() {
        return {
            documents: null,
            accessoires: null,
            techniciens: null,
            contractables: null,
            modalData: null,
        }
    },
    methods: {
        passData(document) {
            this.modalData = document;
        },
        deleteResource(link, type) {
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
                                type + ' Supprimé',
                                'success'
                            )
                            window.location.reload()
                        })

                }
            })
        }
    },
    mounted() {
        this.documents = this.documents_prop;
        this.accessoires = this.accessoires_prop;
        this.techniciens = this.techniciens_prop;
        this.contractables = this.contractables_prop;
    }
}
</script>