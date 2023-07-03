
<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';


setOptions({
    server: {
        process: {

            url: '../../image',
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        }
    }
})

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

export default {
    components: {
        FilePond
    },
    props: ["contrats", "chambres_prop", "clients_prop", "contractables_prop", "offres_prop", "compagnie_prop"],
    data() {
        return {
            contractables: this.contractables_prop,
            offres: this.offres_prop,

            types_paiements: ['Espèce', 'Airtel Money', 'Chèque'],
            type_paiement_selectionné: null,
            type_caution_selectionné: null,
            contractable: {
                id: null
            },
            fcEvents: [],
            clients: this.clients_prop,
            today: new Date().toISOString().substr(0, 10),
            weekDays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            chambres: this.chambres_prop,
            chambreADetailler: this.chambres_prop[0],
            formulaire: {
                du: null,
                au: null,
                nb_jours: 2,
                prix_journalier: null,
                paiement: null,
                demi_journee: null,
                chauffeur: null,
                offre: null,
            },
            afficheFormulaireLocationRapide: true,
            client: {
                id: '',
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
            },
            display: {
                nouveau_client: false,
                halfDay: false,
                driver: false
            },
            compagnie: this.compagnie_prop
        };

    },
    computed: {
        week() {
            todayDay = this.today.getDay()

        },
        'nb_jours': function () {
            if (this.compagnie.type === 'véhicules' && this.formulaire.du && this.formulaire.au) {
                this.formulaire.nb_jours = ((new Date(this.formulaire.au) - new Date(this.formulaire.du)) / (3600 * 1000 * 24))
                return ((new Date(this.formulaire.au) - new Date(this.formulaire.du)) / (3600 * 1000 * 24))
            }
        },
        'nb_jours_hotel': function () {
            // if(this.formulaire.offre && this.formulaire.offre.nom === 'Detente'){
            //     var now = new Date();
            //     now.setMinutes( now.getMinutes() + 10 );
            //     this.formulaire.du = now.toISOString().split('.')[0];
            //     this.formulaire.au = new Date(now.setHours( now.getHours() + 4  )).toISOString().split('.')[0];
            //     this.formulaire.nb_jours = 1;
            //     this.formulaire.prix_journalier = this.formulaire.offre.montant;
            // }
        },
        'total': function () {
            if (this.compagnie.type === 'véhicules' && this.formulaire.du && this.formulaire.au && this.formulaire.prix_journalier) {
                return +(this.formulaire.nb_jours * this.formulaire.prix_journalier) + (+this.formulaire.demi_journee) + (+this.formulaire.chauffeur)
            } else if (this.compagnie.type === 'hôtel' && this.formulaire.nb_jours && this.formulaire.prix_journalier) {
                return +(this.formulaire.prix_journalier * this.formulaire.nb_jours)
            }
        },
        solde() {
            if (this.total && this.formulaire.paiement) {
                return this.total - this.formulaire.paiement
            }
        }
    },
    watch: {
        'formulaire.prix_journalier': function () {
            if (this.display.halfDay)
                this.formulaire.demi_journee = this.formulaire.prix_journalier / 2
        },
        'formulaire.offre': function () {
            switch (this.formulaire.offre.nom) {
                case 'Detente':
                    this.appliquerOffreDetente();
                    break;
                case 'Nuitee VIP':
                case 'Nuitee Classique':
                    this.appliquerOffreNuitee();
                    break;
                case 'H24 VIP':
                case 'H24 Classique':
                    this.appliquerOffreH24()
                default:
                    break;
            }

        }

    },
    methods: {
        filepondInitialized() {
            console.log(this.$refs.createPond)
        },
        handleProcessedFile(error, file) {
            if (error) {
                console.log(error)
                return;
            }
            console.log(file.serverId)
            this.client.image_id = file.serverId
        },
        appliquerOffreDetente() {
            var now = new Date();
            now.setMinutes(now.getMinutes() + 10);
            now.setHours(now.getHours() + 1);
            this.formulaire.du = now.toISOString().split('.')[0];
            this.formulaire.au = new Date(now.setHours(now.getHours() + 4)).toISOString().split('.')[0];
            this.formulaire.nb_jours = 1;
            this.formulaire.prix_journalier = this.formulaire.offre.montant;
        },
        appliquerOffreNuitee() {
            var now = new Date();
            now.setHours(23)
            now.setMinutes(0);
            now.setSeconds(0);
            this.formulaire.du = now.toISOString().split('.')[0];
            now.setDate(now.getDate() + 1)
            now.setHours(9)
            now.setMinutes(0);
            now.setSeconds(0);
            this.formulaire.au = now.toISOString().split('.')[0];
            this.formulaire.nb_jours = 1;
            this.formulaire.prix_journalier = this.formulaire.offre.montant
        },
        appliquerOffreH24() {
            var now = new Date();
            now.setHours(14)
            now.setMinutes(0);
            now.setSeconds(0);
            this.formulaire.du = now.toISOString().split('.')[0];
            now.setDate(now.getDate() + 1)
            now.setHours(13)
            now.setMinutes(59);
            now.setSeconds(59);
            this.formulaire.au = now.toISOString().split('.')[0];
            this.formulaire.nb_jours = 1;
            this.formulaire.prix_journalier = this.formulaire.offre.montant
        },



        // Retourne la couleur a afficher selon l'état de la chambre
        couleurEtat(chambre) {
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
        afficheDetailsChambre(chambre) {
            this.chambreADetailler = chambre
            this.$forceUpdate()
        },
        // Affiche le formulaire de Location Rapide
        faireLouer() {
            this.afficheFormulaireLocationRapide = true;
            this.$forceUpdate()
        },
        // Cache Formulaire
        cacheFormulaireDétails() {
            this.afficheFormulaireLocationRapide = false;
            this.$forceUpdate()
        },
        toggleNewCustomerForm() {
            this.display.nouveau_client = !this.display.nouveau_client
            this.client = {}
        },
        toggleHalfDayForm() {
            this.display.halfDay = !this.display.halfDay
        },
        toggleDriverForm() {
            this.display.driver = !this.display.driver
        },
        enregistreClientDansCashier() {
            // Affiche le Spinner
            // this.isLoading = true;
            // this.$forceUpdate();

            // Créee un nouveau client
            if (!this.client.id) {
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
        },
        rendreVehiculeDisponible() {
            axios.post('/contractable/' + this.contractable.id + '/rendre-disponible')
                .then(response => {
                    console.log(response)
                    this.contractable.etat = 'disponible'
                    document.getElementById('rendreVehiculeDisponible').style.display = 'none';
                    document.getElementsByClassName('modal-backdrop')[0].classList.remove('show')
                    console.log(document.getElementById('rendreVehiculeDisponible'))
                    this.$forceUpdate()
                    this.$alertify.success("Le Client a été enregistré dans Cashier")
                })
        }
    },
    mounted() {
        if (this.clients) {
            this.clients.map(client => {
                client.nom_complet = client.nom + ' ' + client.prenom
            })
        }
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
