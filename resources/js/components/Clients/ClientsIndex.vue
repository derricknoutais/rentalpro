<script>
export default {
    data(){
        return {
            client: {
                nom: 'sadsada',
                prenom: 'sadsada',
                numero_telephone: 'sadsada',
                numero_permis: 'sadsada',
                mail: 'sadsada',
                ville: 'Port-Gentil',
                addresse: 'sadsada',
                cashier_id: ''
            }
        }
    },
    methods:{
        relocateTo(location){
            window.location = location
        },
        enregistreClientDansCashier(){

            axios.post('http://facture.test/api/client/nouveau', this.client).then(response => {
                console.log(response.data);
                this.client.cashier_id = response.data.id
                axios.post('/clients/ajout-client', this.client).then(response => {
                    console.log(response.data);
                    
                }).catch(error => {
                    console.log(error);
                });
            }).catch(error => {
                console.log(error);
            });


            

            
        }
    },
    mounted(){

    }
}
</script>