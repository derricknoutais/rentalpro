require('./bootstrap');
require('./alpine');
require('./apex');
window.Vue = require('vue');

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

import VueAlertify from 'vue-alertify';
Vue.use(VueAlertify);

import Vuetable from 'vuetable-2'
Vue.component('vuetable', Vuetable);

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


const app = new Vue({
    el: '#app',
    data: {
        test: false,
        notifications: [],
        fcEvents: {
          title : 'Sunny Out of Office',
          start : '2019-08-25',
          end : '2020-07-27'
        },

    },
    methods: {
        toggleUserOptions(){
            console.log('Hi')
            this.test = ! this.test
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


