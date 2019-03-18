<script>
export default {
    data(){
        return {

        }
    },
    methods:{
        relocateTo(location){
            window.location = location
        }
    },
    mounted(){
        $('#ajoutClient').modal('show')
    }
}
</script>