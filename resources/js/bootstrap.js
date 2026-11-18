// Globals used by vue-axios-interceptors and the Filters mixin, which
// reference window.Vue / window.axios. Import this file first.
import Vue from 'vue';
import axios from 'axios';

window.Vue = Vue;
window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
