
<script>
export default {
props: ["contrats", "chambres_prop", "clients_prop"],
data() {
	return {
        fcEvents: [],
        clients: this.clients_prop,
		today: new Date().toISOString().substr(0, 10),
        weekDays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        chambres: this.chambres_prop,
        chambreADetailler: this.chambres_prop[0],
        formulaire: {
            du: null,
            au : null,
            nb_jours: 2
        },
        afficheFormulaireLocationRapide: true,
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
        },
        display: {
            nouveau_client : false
        }

	};

},
computed: {
	week(){
        todayDay = this.today.getDay()

    },
    'nb_jours' : function(){
        if(this.formulaire.du && this.formulaire.au){
            this.formulaire.nb_jours = ( (new Date(this.formulaire.au) - new Date(this.formulaire.du)) / (3600 * 1000 * 24))
            return ( (new Date(this.formulaire.au) - new Date(this.formulaire.du)) / (3600 * 1000 * 24))
        }
    }
},
methods: {
    // Retourne la couleur a afficher selon l'état de la chambre
    couleurEtat(chambre){
        switch (chambre.etat) {
            case 'Disponible':
                return 'tw-bg-green-500';
                break;
            case 'Loué':
                return 'tw-bg-red-500';
                break;
            case 'Maintenance':
                return 'tw-bg-blue-500';
                break;
            default:
                break;
        }
    },
    // Affiche les détails de la chambre cliquée
    afficheDetailsChambre(chambre){
        this.chambreADetailler = chambre
        this.$forceUpdate()
    },

    // Affiche le formulaire de Location Rapide
    faireLouer(){
        this.afficheFormulaireLocationRapide = true;
        this.$forceUpdate()
    },
    // Cache Formulaire
    cacheFormulaireDétails(){
        this.afficheFormulaireLocationRapide = false;
        this.$forceUpdate()
    },
    displayNewCustomerForm(){
        this.display.nouveau_client = ! this.display.nouveau_client
        this.client = {}
    },

    enregistreClientDansCashier(){
        // Affiche le Spinner
        // this.isLoading = true;
        // this.$forceUpdate();

        // Créee un nouveau client
        if(! this.client.id){
            axios.post('https://cashier.azimuts.ga/api/client/nouveau', this.client).then(response => {
                console.log(response.data);
                this.client.cashier_id = response.data.id
                document.getElementById('cashier_id').value = response.data.id
                this.$forceUpdate()
                this.$alertify.success("Le Client a été enregistré dans Cashier")
                document.getElementById('clientForm').submit();
            }).catch(error => {
                this.$alertify.error("Un Problème est survenu lors de l'enregistrement du client. Veuillez vérifiez votre connexion Internet")
            });
        } else {
            console.log('Hellooooo')
            document.getElementById('clientForm').submit();
        }
    }
},
mounted() {
    if(this.clients_prop){
        this.clients_prop.map( client => {
            client.nom_complet = client.nom + ' ' + client.prenom
        })
    }
}
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
