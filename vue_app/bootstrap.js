window._ = require('lodash')
window.Vue = require('vue')

import VuetifyToast from 'vuetify-toast-snackbar-ng'

import setup from './interceptors/interceptors.js'
setup()

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Loader from './base_components/base/Loader.vue'
Vue.component('loader', Loader)

import VCustomTitle from './global_components/VCustomTitle.vue'
Vue.component('v-custom-title', VCustomTitle)

import VCustomCard from './global_components/VCustomCard.vue'
Vue.component('v-custom-card', VCustomCard)

import VClienteComponent from './global_components/VClienteComponent.vue'
Vue.component('v-cliente-component', VClienteComponent)

import VCustomMenuCalendar from './global_components/VCustomMenuCalendar.vue'
Vue.component('v-custom-menu-calendar', VCustomMenuCalendar)

const VFileComponent = () => import('./global_components/VFileComponent.vue')
Vue.component('v-file-component', VFileComponent)

import Nl2br from 'vue-nl2br'
Vue.component('nl2br', Nl2br)

window.axios = require('axios')

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
axios.defaults.headers.common.Authorization = `Bearer ${localStorage.getItem('id_token')}`
axios.defaults.withCredentials = true;

Vue.use(VuetifyToast, {
    x: 'right',
    y: 'top',
    color: 'info',
    icon: 'mdi-info',
    timeout: 3000,
    dismissable: true,
    autoHeight: false,
    multiLine: false,
    vertical: false,
    shorts: {
        error: {
            color: 'red'
        },
        sucs: {
            color: 'green'
        },
        warn: {
            color: 'orange'
        }
    },
    property: '$toast'
})