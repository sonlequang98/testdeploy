require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import { routes } from './routes';
import StoreData from './store';
import MainApp from './components/MainApp.vue';
import { initialize } from './helpers/general';
import Axios from 'axios';
import Clipboard from "v-clipboard";
import Vue2Editor from "vue2-editor";

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Clipboard)
Vue.use(Vue2Editor);

Axios.defaults.baseURL = '/api';

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp,
        Vue2Editor
    }
});