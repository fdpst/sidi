<template>
    <v-card class="pa-3 ma-3">
        <v-toolbar flat color="#1d2735" dark>
            <v-icon class="white--text" style="font-size: 45px">
                mdi-bullhorn
            </v-icon>
            <pre><v-toolbar-title><h2>Listado Promociones</h2></v-toolbar-title></pre>
        </v-toolbar>
        <loader v-if="isloading"></loader>
        <v-tooltip right>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    v-if="user.role == 1"
                    fab
                    :to="{ path: `/guardar-promocion` }"
                    :loading="isloading"
                    :disabled="isloading"
                    color="orange darken-1"
                    class="mt-2"
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-icon class="white--text">mdi-plus-box</v-icon>
                </v-btn>
            </template>
            <span>Nueva Promocion</span>
        </v-tooltip>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field prepend-icon="mdi-account-search" v-model="search" label="busqueda"></v-text-field>
            </v-col>
        </v-row>
        <v-data-table :headers="headers" :search="search" :items-per-page="10" :items="promociones" item-key="id" class="elevation-1">
            <template v-slot:item.active="{ item }">
                <v-chip v-if="user.role == 1" @click="changeActive(item)" class="ma-2 white--text" :color="item.active ? 'green' : 'red'">
                    {{ item.active ? 'activo' : 'inactivo' }}
                </v-chip>
                <v-chip v-if="user.role == 2" class="ma-2 white--text" :color="item.active ? 'green' : 'red'">
                    {{ item.active ? 'activo' : 'inactivo' }}
                </v-chip>
            </template>
            <template v-slot:item.action="{ item }">
                <router-link v-if="user.role == 1 || user.role == 2" :to="{ path: `/guardar-promocion?id=${item.id}` }" class="action-buttons">
                   <v-icon
                        small
                        class="mr-2"
                        color="#1d2735"
                        style="font-size: 25px"
                        title="EDITAR"
                        >mdi-pencil-outline</v-icon
                    >
                </router-link>
                <v-icon v-if="user.role == 1" @click="openModal(item)" small class="mr-2" color="red" style="font-size: 25px;" title="BORRAR">
                    mdi-trash-can
                </v-icon>
            </template>
            <template v-slot:item.file_name="{ item }">
                <img :src="item.path" style="width: 150px; height: 40px" class="mt-1"/>
            </template>
            <template v-slot:item.url="{ item }">
                <!--<span v-if="item.url != 'undefined'"> <a :href="'https://www.'+item.url" target="_blank">{{item.url}}</a></span>-->
                <span v-if="item.url != 'undefined'"> <a :href="item.url" target="_blank">{{item.url}}</a></span>
                <span v-else>No Asignada</span>
            </template>
        </v-data-table>
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5 aviso" style="justify-content: center; background:#1d2735; color:white">
                    Aviso
                </v-card-title>
                <v-card-text style="text-align:center">
                    <h2>¿Estás seguro que deseas eliminar?</h2>
                </v-card-text>
                <v-card-actions class="pt-3">
                    <v-spacer></v-spacer>
                    <v-btn
                        color="error"
                        large
                        @click="dialog = false;selectedItem={}">Cancelar</v-btn>
                    <v-btn
                        color="success"
                        large
                        @click="deletepromocion()">Confirmar</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                search: '',
                promociones: [],
                selectedItem: 0,
                dialog: false,
                headers:
                [
                    {text: 'Imagen', value: 'file_name'},
                    {text: 'Nombre', value: 'nombre'},
                    {text: 'Url', value: 'url'},
                    {text: 'Activo',align: 'center',value: 'active'},
                    {text: 'Acciones',value: 'action',sortable: false},
                ],
            }
        },
        mounted() {
            this.getAllpromociones()
        },
        methods: {
            getAllpromociones() {
                axios.get('api/get-all-promociones').then(res => {
                    this.promociones = res.data
                }, err => {
                    this.$toast.error('Error consultando datos')
                })
            },
            deletepromocion() {
                this.dialog = false
                axios.get(`api/delete-promocion/${this.promociones[this.selectedItem].id}`).then(res => {
                    this.promociones.splice(this.selectedItem, 1)
                    this.$toast.sucs('promocion eliminada con exito')
                }, res => {
                    this.$toast.error('Error eliminando promocion')
                })
            },
            changeActive(item) {
                axios.get(`api/change-promocion-active/${item.id}`).then(res => {
                    item.active = !item.active
                    this.$toast.sucs('promocion actualizada')
                }, err => {
                    this.$toast.error('Error consultando datos')
                })
            },
            openModal(item){
                this.selectedItem = this.promociones.indexOf(item);
                this.dialog = true;
            },
        },
        computed: {
            isloading: function() {
                return this.$store.getters.getloading
            },
            user: function() {
                return this.$store.getters.getuser
            }
        }
    }
</script>
