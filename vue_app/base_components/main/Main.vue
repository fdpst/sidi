<template>
    <v-app>
        <top v-if="$route.path !== '/login'" v-on:toggleDrawer="toggleDrawer"></top>
        <lateral v-if="$route.path !== '/login'" :drawer="drawer" v-on:emitInnputDrawer="getInnputDrawer"></lateral>

        <v-main color="purple darken-3">
            <v-container fluid grid-list-xs :class="{ 'pa-0' : $route.path == '/login' }">
                <router-view :key="$route.path"></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
    import Top from '../navs/Top.vue'
    import Lateral from '../navs/Lateral.vue'
    export default {
        components: {
            Top,
            Lateral
        },
        watch: {
            drawer: {
                immediate: true,
                handler: function(n) {

                }
            },
        },
        data() {
            return {
                drawer: true
            }
        },
        methods: {
            isActive() {
                return false //this.$route.path != '/login'
            },
            toggleDrawer() {
                this.drawer = !this.drawer
            },
            getInnputDrawer(e) {
                this.drawer = e
            }
        },
    }
</script>

<style media="screen">
    .my-container {
        padding: 1rem;
        background-color: #ffffff;
        border-radius: 7px;
    }

    .v-content__wrap {
        background-color: #f3f6fd !important;
    }

    a {
        text-decoration: none;
    }

    .theme--light.v-application {
        background: rgb(225, 225, 225) !important;
    }

    .theme--light.v-app-bar.v-toolbar.v-sheet {
        background-color: #ffffff !important;
    }

    .theme--light.v-data-table .v-data-table-header th.sortable .v-data-table-header__icon {
        display: none;
    }
</style>