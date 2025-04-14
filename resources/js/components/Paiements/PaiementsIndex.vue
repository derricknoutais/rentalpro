<script>
export default {
    props: ['paiements_prop'],
    data() {
        return {
            paiements: null
        }
    },
    methods: {

    },
    mounted() {
        this.paiements = this.paiements_prop
    }
}

</script>