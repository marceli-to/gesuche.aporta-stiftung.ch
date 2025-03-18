require('@/bootstrap');

// Vue
import Vue from 'vue';
window.Vue = Vue;

// Axios, Vue-Axios
import VueAxios from 'vue-axios';
import axios from 'axios';
window.axios = require('axios');
Vue.use(VueAxios, axios);

// Axios Interceptors
require('vue-axios-interceptors');

// Filters
require('@/mixins/Filters');

// Vue-Axios defaults
Vue.axios.defaults.withCredentials = true;

// Vue-Notifications
import Notifications from 'vue-notification';
Vue.use(Notifications);

// Vue-Router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

import number from '@coders-tm/vue-number-format';
Vue.use(number, { precision: 4 });

// Routes
import formRoutes from '@/views/pages/form/config/routes';

const router = new VueRouter(
  { 
    mode: 'history', 
    routes: [
      ...formRoutes,
    ]
  }
);

// Form component
import FormComponent from '@/Form.vue';

// Mount App
if (document.getElementById("app-form")) {
  const app = new Vue({
    mixins: [],
    components: { 
      FormComponent
    },
    router
  }).$mount('#app-form');
}
