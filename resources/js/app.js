
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import Vuetify from 'vuetify'

Vue.use(Vuetify)

import VueSimplemde from 'vue-simplemde'
import 'simplemde/dist/simplemde.min.css'

Vue.use(VueSimplemde)

import md from "marked"
window.md =md

const axios = require('axios');//何故かここwindow.axiosだとエラーになるんだけど・・？

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('AppHome', require('./components/AppHome.vue').default);
Vue.component('AppLog', require('./components/AppLog.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import router from './Router/router.js'
import {sync} from 'vuex-router-sync'
import store from './Store/store.js'
import User from './Helpers/User'
import Token from './Helpers/Token'
import Exception from './Helpers/Exception'


Vue.prototype.$eventBus = new Vue();
import ParticleEffectButton from 'vue-particle-effect-button'

Vue.use(ParticleEffectButton)


window.User = User
window.Token = Token
window.Exception = Exception

sync(store,router);
require('../sass/grobal.scss')

const app = new Vue({
    el: '#app',
    router,
    store
});
