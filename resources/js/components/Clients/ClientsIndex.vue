<script>
export default {
    data(){
        return {
            client: {
                nom: '',
                prenom: '',
                numero_telephone: '',
                numero_permis: '',
                mail: '',
                ville: '',
                addresse: '',
                cashier_id: ''
            }
        }
    },
    methods:{
        relocateTo(location){
            window.location = location
        },
        enregistreClientDansCashier(){

            axios.post('https://thecashier.ga/api/client/nouveau', this.client).then(response => {
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