<script>
export default {
    props: {
        documents_prop: Array,
        accessoires_prop: Array,
        techniciens_prop: Array,
        contractables_prop: Array
    },
    data() {
        return {
            documents: null,
            accessoires: null,
            techniciens: null,
            contractables: null
        }
    },
    methods: {

    },
    mounted() {
        this.documents = this.documents_prop;
        this.accessoires = this.accessoires_prop;
        this.techniciens = this.techniciens_prop;
        this.contractables = this.contractables_prop;
    }
}
</script>