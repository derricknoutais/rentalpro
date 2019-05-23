<script>
export default {
    props: ['environment'],
    data(){
        return {
            isLoading: false
        }
    },
    methods:{
        envoieACashier(payload){
            
            console.log(payload)
            var data;
            var link = 'https://thecashier.ga/api/facture';
            data = {
                'objet': 'Location ' + payload.voiture.marque + ' ' + payload.voiture.type + ' ' + payload.voiture.immatriculation,
                'échéance' : payload.check_in,
                'quantité' : payload.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : payload.prix_journalier,
                'client': payload.client.cashier_id 
            };
            if(this.environment === 'local'){
                link = 'http://thecashier.test/api/facture'
            }
                

            axios.post(link, data).then(response => {
                console.log(link)
                var cashier_id = response.data.id
                this.cashier_id = cashier_id       
                if( cashier_id !== null ){
                    axios.post('/contrat/' + payload.id + '/update-cashier-id', { cashier_id: this.cashier_id }).then( response => {
                        // window.location.href = '/contrat/' + payload.id
                        location.reload()
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