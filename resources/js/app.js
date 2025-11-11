require('./bootstrap');
require('./alpine');
require('./apex');
window.Vue = require('vue').default;





window.writtenNumber = require('written-number');
writtenNumber.defaults.lang = 'fr';

import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter, {
    symbol : 'F CFA',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'back',
    symbolSpacing: true
});

import fullCalendar from 'vue-fullcalendar'
Vue.component('full-calendar', fullCalendar)

const moment = require('moment')
require('moment/locale/fr')

Vue.use(require('vue-moment'), {
    moment
})

import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect)

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);


import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';

import VueSignaturePad from 'vue-signature-pad';
Vue.use(VueSignaturePad);

import html2pdf from 'html2pdf.js';


const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import Chart from './components/Chart.vue'
import LineChart from './components/LineChart.js'
Vue.component('chart', Chart)

Vue.component('pdf-download-button', {
    props: {
        target: {
            type: String,
            required: true
        },
        filename: {
            type: String,
            default: 'document'
        }
    },
    data() {
        return {
            loading: false
        };
    },
    methods: {
        async generate() {
            if (this.loading) {
                return;
            }

            const element = document.querySelector(this.target);
            if (!element) {
                console.warn('[pdf-download-button] cible introuvable :', this.target);
                return;
            }

            this.loading = true;
            const safeName = (this.filename || 'document').trim();
            const filename = safeName.toLowerCase().endsWith('.pdf') ? safeName : `${safeName}.pdf`;
            const options = {
                margin: [0, 0, 0, 0],
                filename,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['css'], avoid: ['.avoid-page-break'] }
            };

            try {
                await html2pdf().set(options).from(element).save();
            } catch (error) {
                console.error('Erreur génération PDF', error);
                if (this.$swal) {
                    this.$swal.fire('Erreur', "Impossible de générer le PDF.", 'error');
                }
            } finally {
                this.loading = false;
            }
        }
    },
    template: `
        <button type="button"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-70"
            :disabled="loading"
            @click="generate">
            <span v-if="loading">Génération…</span>
            <span v-else>Télécharger le PDF</span>
        </button>
    `
});

// Vue.component('linechart', LineChart)

const app = new Vue({
    el: '#app',
    name : "App",
    components : {
    },
    data: {
        test: false,
        print_options : false,
        notifications: [],
        fcEvents: {
          title : 'Sunny Out of Office',
          start : '2019-08-25',
          end : '2020-07-27'
        },
        isOpen : {
            mobileMenu : false

        },
        sidebarCollapsed: false

    },
    methods: {
        toggleMobileMenu(){
            this.isOpen.mobileMenu = ! this.isOpen.mobileMenu
        },
        toggleSidebar(){
            this.sidebarCollapsed = !this.sidebarCollapsed;
        },
        toggle(data){
            this[data] = ! this[data];
            this.$forceUpdate();
        },
        relocateTo(location){
            window.location = location
        },
        envoieACashier(payload){
            console.log(payload)
            var data;
            data = {
                'objet': 'Location ' + payload.voiture.marque + ' ' + payload.voiture.type + ' ' + payload.voiture.immatriculation,
                'échéance' : payload.du,
                'quantité' : payload.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : payload.prix_journalier,
                'client': payload.client.cashier_id
            };

            axios.post('https://thecashier.ga/api/facture', data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id
                if( cashier_id !== null ){
                    axios.post('/contrat/' + payload.id + '/update-cashier-id', {cashier_id: this.cashier_id}).then( response => {
                        window.location.reload()
                    }).catch(error => {
                        console.log(error);
                    });
                }
            }).catch(error => {
                console.log(error);
            });
        },
        toggleTest(){
            this.test != this.test
        }
    },
    mounted() {
        this.test = false
    },
    created(){
        window.Echo.channel('contrats').listen('ContratCree', e => {
            this.$alertify.success('Contrat créé');
            console.log(e)
            this.notifications.push({
                message: 'Nouveau Contrat Créé ' + e.contrat.numéro,
                lien: '/contrat/' + e.contrat.id
            })
        })
    }
});
