<script>

export default {
    props: ['contrat_prop'],
    data() {
        return {
            contrat: null,
            photos: {
                avant: null,
                gauche: null,
                droite: null,
                arriere: null,
            },
            accessoires: [],
            documents: []
        }
    },
    methods: {

        photoAvant(id) {
            this.photos.avant = id
        },
        photoArriere(id) {
            this.photos.arriere = id
        },
        photoGauche(id) {
            this.photos.gauche = id
        },
        photoDroite(id) {
            this.photos.droite = id
        }
    },
    created() {
        this.contrat = this.contrat_prop;
    }
}

</script>