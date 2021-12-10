import Vue from 'vue'
import App from './App'
import BootstrapVue from 'bootstrap-vue'
import router from './router'
import store from './store'
import en from './locales/en.json'
import es from './locales/es.json'
import VueI18n from 'vue-i18n'
import {defaultLocale, localeOptions, firebaseConfig} from './constants/config'
import Notifications from './components/Common/Notification'
import Breadcrumb from './components/Common/Breadcrumb'
import RefreshButton from './components/Common/RefreshButton'
import Colxx from './components/Common/Colxx'
import vuePerfectScrollbar from 'vue-perfect-scrollbar'
import contentmenu from 'v-contextmenu'
import VueLineClamp from 'vue-line-clamp'
import VCalendar from 'v-calendar'
import VueScrollTo from 'vue-scrollto'
import VueLodash from 'lodash';
import VueCurrencyFilter from 'vue-currency-filter'

Vue.use(VueLodash);
import {env} from "./env";
import {http} from "appmakers-vue/services/http-service";
import './assets/css/sass/common.scss';

// import Vuelidate from 'vuelidate'
// Vue.use(Vuelidate);

Vue.use(BootstrapVue);
Vue.use(VueI18n);

const messages = {en: en, es: es};
const locale = (localStorage.getItem('currentLanguage') && localeOptions.filter(x => x.id === localStorage.getItem('currentLanguage')).length > 0) ? localStorage.getItem('currentLanguage') : defaultLocale;
const i18n = new VueI18n({
    locale: locale,
    fallbackLocale: 'en',
    messages
});

Vue.use(Notifications);
Vue.component('piaf-breadcrumb', Breadcrumb);
Vue.component('b-refresh-button', RefreshButton);
Vue.component('b-colxx', Colxx);
Vue.component('vue-perfect-scrollbar', vuePerfectScrollbar);
Vue.use(require('vue-shortkey'));
Vue.use(contentmenu);
Vue.use(VueLineClamp, {
    importCss: true
});
Vue.use(VCalendar, {
    firstDayOfWeek: 2, // ...other defaults,
    formats: {
        title: 'MMM YY',
        weekdays: 'WW',
        navMonths: 'MMMM',
        input: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'],
        dayPopover: 'L'
    },
    datePickerShowDayPopover: false,
    popoverExpanded: true,
    popoverDirection: 'bottom'
});
Vue.use(require('vue-moment'));
Vue.use(VueScrollTo);

http.interceptors.request.use(function (config) {
    config.url = env.apiUrl + config.url.replace(/^\//, '');
    return config;
}, function (error) {
    return Promise.reject(error);
});

Vue.config.productionTip = false;

Vue.prototype.$env = env;

Vue.use(VueCurrencyFilter, {
    symbol: 'PKR',
    thousandsSeparator: ',',
    fractionCount: 0,
    fractionSeparator: '.',
    symbolPosition: 'front',
    symbolSpacing: true,
    avoidEmptyDecimals: undefined,
});


export default new Vue({
    i18n,
    router,
    store,
    render: h => h(App)
}).$mount('#app')
