<template>
    <div class="flex flex-col">
        <div class="flex justify-end w-full pr-4">
            <span class="isolate inline-flex rounded-md shadow-sm">
                <button
                    @click="requestChartSummary('annee', 'chiffre_affaires')"
                    class="relative inline-flex items-center rounded-l-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">Annuel</button>
                <button type="button"
                    @click="requestChartSummary('mois', 'chiffre_affaires')"
                    class="relative -ml-px inline-flex items-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">Mensuel</button>
                <button type="button"
                    @click="requestChartSummary('jour', 'chiffre_affaires')"
                    class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">Journalier</button>
            </span>
        </div>
        <canvas id="myChart"></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js/auto'

export default {
    data(){
        return {
            chart : null,
            type : 'line',
            labels : ['Rouge', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            label : 'Votes',
            data : [12, 19, 10, 15, 12, 13],
            summary_request : {
                type : 'annee',
                data_requested : 'chiffre_affaires'
            }
        }
    },
    mounted() {
        const ctx = document.getElementById('myChart');
        this.chart = new Chart(ctx, {
            type: this.type,
            data: {
                labels: this.labels,
                datasets: [{
                    label: this.label,
                    data: this.data,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        axios.get('/test?type=annee').then(response => {
            console.log(response.data)
            this.chart.data.labels = response.data.labels;
            this.chart.data.datasets.forEach((dataset) => {
                dataset.data = response.data.data
            })
            this.chart.update()
        })
        this.chart.update()
    },
    methods : {
        switchData(){
            console.log('Hello')
            this.labels = ['Pain', 'Biere', 'Jaune', 'Green', 'Purple', 'Orange'];
            this.data = [24, 19, 30, 45, 12, 13]
            this.chart.update()
            this.$forceUpdate()
        },
        requestChartSummary(type, data_requested){
            this.summary_request.type = type;
            this.summary_request.data_requested = data_requested;
            this.$forceUpdate()
            axios.get('/test?type=' + type + '&data_requested=' + data_requested).then(response => {
                console.log(response.data)
                this.chart.data.labels = response.data.labels;
                this.chart.data.datasets.forEach((dataset) => {
                    dataset.data = response.data.data
                })
                this.chart.update()
            })
        }
    }
}
</script>
