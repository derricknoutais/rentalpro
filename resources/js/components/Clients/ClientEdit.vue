
<script>

export default {
    props: ['prop_client'],
    data() {
        return {
            client: {
                nom: '',
                prenom: '',
                numero_telephone: '',
                numero_telephone2: '',
                numero_telephone3: '',
                numero_permis: '',
                mail: '',
                ville: '',
                addresse: '',
                cashier_id: '',
                image_id: ''
            }
        }
    },
    methods: {
        attributeImage(id) {
            console.log('Hello ' + id)
            this.client.image_id = id
            this.$forceUpdate()
        },
        updateClient() {
            axios.post('https://cashier.azimuts.ga/api/client/' + this.prop_client.cashier_id + '/update', this.client).then(response => {
                console.log(response.data);
                document.getElementById('clientUpdateForm').submit();
            }).catch(error => {
                console.log(error);
            });
        },
        deleteImage() {
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
                    axios.delete('/image/' + this.prop_client.image_id + '/client/' + this.prop_client.id)
                        .then(response => {
                            this.$swal(
                                'Supprimé!',
                                'Client supprimé',
                                'success'
                            )
                            window.location = '/clients/'
                        })

                }
            })

        }
    },
    mounted() {
        this.client.nom = this.prop_client.nom;
        this.client.prenom = this.prop_client.prenom
        this.client.numero_telephone = this.prop_client.phone1
        this.client.numero_telephone2 = this.prop_client.phone2
        this.client.numero_telephone3 = this.prop_client.phone3
        this.client.mail = this.prop_client.mail
        this.client.ville = this.prop_client.ville
        this.client.addresse = this.prop_client.addresse
        this.client.cashier_id = this.prop_client.cashier_id
        this.client.image_id = ''

    }
}
</script>
