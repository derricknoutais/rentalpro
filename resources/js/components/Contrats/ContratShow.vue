<script>
export default {
    data(){
        return {

        }
    },
    methods:{
        envoieACashier(payload){
            console.log(payload)
            var data;
            data = {
                'objet': 'Location ' + payload.voiture.marque + ' ' + payload.voiture.type + ' ' + payload.voiture.immatriculation,
                'échéance' : payload.check_in,
                'quantité' : payload.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : payload.prix_journalier,
                'client': payload.client.cashier_id 
            };

            axios.post('https://thecashier.ga/api/facture', data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id       
                if( cashier_id !== null ){
                    axios.post('/contrat/' + payload.id + '/update-cashier-id', {cashier_id: this.cashier_id}).then( response => {
                        console.log(response.data);
                    }).catch(error => {
                        console.log(error);
                    });
                }   
            }).catch(error => {
                console.log(error);
            });
        },
    },
    mounted(){

    }
}
</script>