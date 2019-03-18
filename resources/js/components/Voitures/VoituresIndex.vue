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
        }
    },
    mounted(){
        $('#ajoutVoiture').modal('show')
    }
}
</script>