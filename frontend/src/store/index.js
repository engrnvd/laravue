import Vue from 'vue'
import Vuex from 'vuex'
import menu from './modules/menu'
import auth from './modules/auth'
import generic from "./modules/generic";
import bkgProgress from "./modules/bkg-progress";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    getters: {},
    mutations: {},
    actions: {},
    modules: {
        bkgProgress,
        generic,
        menu,
        auth,
    }
});
