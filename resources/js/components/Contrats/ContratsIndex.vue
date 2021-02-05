<script>
export default {
    props: ['contrats', 'env', 'voitures_prop', 'clients_prop'],
    data(){
        return {
            filters: {
                voiture: {
                    immatriculation: ''
                },
                client: {
                    nom_complet : ''
                },
                etat: false
            },
            search : {
                etat: 'En Cours'
            }
        }
    },
    methods:{
        relocateTo(location){
            window.location = location
        },
        envoieACashier(payload){
            console.log(payload)
            var data;
            data = {
                'objet': 'Location ' + payload.voiture.marque + ' ' + payload.voiture.type + ' ' + payload.voiture.immatriculation,
                'échéance' : payload.du,
                'quantité' : payload.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : payload.prix_journalier,
                'client': payload.client.cashier_id
            };
            var link = 'https://cashier.azimuts.ga/api/facture'
            if(this.env === 'local'){
                link = 'http://thecashier.test/api/facture'
            }
            axios.post(link, data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id
                if( cashier_id !== null ){
                    axios.post('/contrat/' + payload.id + '/update-cashier-id', {cashier_id: this.cashier_id}).then( response => {
                        window.location.reload()
                    }).catch(error => {
                        console.log(error);
                    });
                }
            }).catch(error => {
                console.log(error);
            });
        },
        annulerContrat(contrat){
            console.log('deleting...')
            axios.delete('/contrats/' + contrat.id ).then(response => {
                console.log(response.data);
                location.reload()
            }).catch(error => {
                console.log(error);
            });
        }
    },
    watch : {
        'search.etat' : function(newVal, oldVal){

        }
    }
}
</script>
