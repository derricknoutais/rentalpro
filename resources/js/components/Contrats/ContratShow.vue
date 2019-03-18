<script>
export default {
    data(){
        return {

        }
    },
    methods:{
        envoyerACashier(){
            console.log('Hellooooo')
            var data;
            this.data = data = {
                'objet': 'Location ' + this.contrat.voiture.marque + ' ' + this.contrat.voiture.type + ' ' + this.contrat.voiture.immatriculation,
                'échéance' : this.contrat.check_in,
                'quantité' : this.contrat.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : this.contrat.prix_journalier
            };

            axios.post('http://facture.test/api/facture', this.data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id       
                // if( cashier_id !== null ){
                //     axios.post('/contrat/' + this.contrat_enregistré.id + '/update-cashier-id', {cashier_id: this.cashier_id}).then( response => {
                //         console.log(response.data);
                //     }).catch(error => {
                //         console.log(error);
                //     });
                // }   
            }).catch(error => {
                console.log(error);
            });
        },
    },
    mounted(){

    }
}
</script>