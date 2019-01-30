import Vue from 'vue';
import Vuex from 'vuex';
import userstorage from './modules/userstorage';
import createPersistedState from "vuex-persistedstate";



Vue.use(Vuex);

export default new Vuex.Store({
  modules:{
      userstorage

  },
  plugins: [createPersistedState()] // この行でvuexに「vuex-persistedstate」を追加
});
