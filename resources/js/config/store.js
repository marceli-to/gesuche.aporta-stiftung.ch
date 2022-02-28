import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user: false,
    filter: {
      hasFilter: false,
      stateId: null,
      amount: null,
    }
  },
  mutations: {
    user(state, user) {
      state.user = user;
    },
    filter(state, filter) {
      state.filter = filter;
    }
  }
});