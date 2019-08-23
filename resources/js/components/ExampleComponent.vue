
<script>
    export default {
        data(){
            return {
                fcEvents: [
                {
                  title : 'FZ-885-AA',
                  start : '2019-08-25',
                  end : '2019-08-27',
                  cssClass: 'bg-danger text-white'
                },
                {
                  title : 'EZ-442-AA',
                  start : '2019-08-28',
                  end : '2019-08-30',
                  cssClass: 'bg-success text-white'
                },
                {
                  title : 'EZ-443-AA',
                  start : '2019-08-23',
                  end : '2019-08-30',
                  cssClass: 'bg-warning'
                }
                ]
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
