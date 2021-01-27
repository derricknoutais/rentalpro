
<script>

export default {
    props: ['prop_client'],
    data(){
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
                cashier_id: ''
            }
        }
    },
    methods:{
        updateClient(){
            axios.post( 'https://cashier.azimuts.ga/api/client/' + this.prop_client.cashier_id + '/update', this.client ).then(response => {
                console.log(response.data);
                document.getElementById('clientUpdateForm').submit();
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted(){
        this.client.nom = this.prop_client.nom;
        this.client.prenom = this.prop_client.prenom
        this.client.numero_telephone = this.prop_client.phone1
        this.client.numero_telephone2 = this.prop_client.phone2
        this.client.numero_telephone3 = this.prop_client.phone3
        this.client.mail = this.prop_client.mail
        this.client.ville = this.prop_client.ville
        this.client.addresse = this.prop_client.addresse
        this.cashier_id = this.prop_client.cashier_id

    }
}
</script>
