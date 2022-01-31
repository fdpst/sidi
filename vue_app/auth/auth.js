import router from '../router/router.js'
import store from '../store/store.js'
import Vue from 'vue'

export default {
    user: {
        authenticated: false,
    },

    signin: function(user) {

        axios.post('api/login', user).then(response => {
            this.setLocalStorage(response.data)
            this.dispatchUser(response.data.user)
            this.user.authenticated = true
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token
            window.location.href = '/'
            //  router.push('/')
        }).catch(error => {
            console.log(error)
            //this.$toast.error('Acceso denegado')
        })
    },

    dispatchUser: function(data) {
        store.dispatch('setAuth', true)
        store.dispatch('setUser', data)
    },

    setLocalStorage: function(data) {
        localStorage.setItem('id_token', data.token)
        localStorage.setItem('user_name', data.user.name)
        localStorage.setItem('user_email', data.user.email)
        localStorage.setItem('role', data.user.role)
        localStorage.setItem('user_id', data.user.id)
    },

    logout: function() {
        axios.post('api/logout').then(response => {
            localStorage.removeItem('id_token')
            localStorage.removeItem('user_name')
            localStorage.removeItem('user_email')
            localStorage.removeItem('role')
            localStorage.removeItem('user_id')
            store.dispatch('setAuth', false)
            store.dispatch('setUser', this.setDefault())
            router.push('/login')
        }).catch(error => {
            console.log(error)
        })
    },

    setDefault: function() {
        return {
            name: '...',
        }
    },

    authenticated: function() {
        return this.check()
    },

    check: function() {
        return (localStorage.getItem('id_token') !== null) ? true : false
    }
}