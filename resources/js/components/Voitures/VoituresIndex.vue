<script>
export default {
    data(){
        return {
            yo: 'jkdhj'
        }
    },
    methods:{
        relocateTo(id){
            window.location = '/voiture/' + id
        },
        submitForm(){
            document.getElementById("voitureForm").submit();
        }
    },
    mounted(){
    }
}
</script>