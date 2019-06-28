<script>
export default {
    data(){
        return {
            yo: 'jkdhj',
            isLoading: false
        }
    },
    methods:{
        relocateTo(id){
            window.location = '/voiture/' + id
        },
        submitForm(){
            this.isLoading = true
            document.getElementById("voitureForm").submit();
        }
    },
    mounted(){
    }
}
</script>