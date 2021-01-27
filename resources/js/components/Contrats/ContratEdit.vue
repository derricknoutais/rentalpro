<script>
export default {
    props: ['clients_prop', 'contrat_prop'],
    data(){
        return {
            clients: this.clients_prop,
            client: this.contrat_prop.client
        }
    },
    methods:{
    },
    created(){
        if(this.contrat_prop){
            this.contrat_prop.client.nom_complet = this.contrat_prop.client.nom + ' ' + this.contrat_prop.client.prenom
        }
        if(this.clients_prop){
            this.clients_prop.map( client => {
                client.nom_complet = client.nom + ' ' + client.prenom
            })
        }
    }
}
</script>
