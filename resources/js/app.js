require('./bootstrap');

import Vue from 'vue'
import store from './store';
//Main pages
import router from './components/router.js'
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
Vue.use(Vuetify);
Vue.component('AppDashboard', require('./views/dashboard.vue').default);
const app = new Vue({
    store,
    el: '#app',
    router,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
});
