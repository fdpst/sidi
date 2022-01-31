<template>
    <v-card class="pa-3 ma-3">
        <v-toolbar flat color="#1d2735" dark>
            <v-icon class="white--text" style="font-size: 45px;">mdi-file-powerpoint</v-icon>
            <pre><v-toolbar-title><h2>Lista Potenciales</h2></v-toolbar-title></pre>
        </v-toolbar>
        <loader v-if="isloading"></loader>
        <v-tooltip right>
            <template v-slot:activator="{ on, attrs }">
                <v-btn fab :to="{ path: `/guardar-potencial` }" :loading="isloading" :disabled="isloading" color="orange darken-1" class="mt-2" v-bind="attrs" v-on="on">
                    <v-icon class="white--text">mdi-plus-box</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Potencial</span>
        </v-tooltip>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field prepend-icon="mdi-account-search" v-model="search" label="Buscar"></v-text-field>
            </v-col>
        </v-row>
        <v-data-table dense :headers="headers" :items="potenciales" :search="search" :items-per-page="15" item-key="id" class="elevation-1" :sort-by="['usuario_id']" :sort-desc="[true]">
            <template v-slot:item.usuario.nombre="{ item }">
                <span v-if="item.usuario">{{item.usuario.nombre}}</span>
                <span v-else style="color:red !important;"><strong>Cliente Eliminado</strong></span>
            </template>
            <template v-slot:item.action="{ item }">
                <router-link :to="{ path: `/guardar-potencial?id=${item.id}` }" class="action-buttons">
                    <v-icon small class="mr-2" color="#1d2735" style="font-size: 25px;" title="EDITAR">mdi-pencil-outline</v-icon>
                </router-link>
                <v-icon @click="openModal(item)" small class="mr-2" color="red" style="font-size: 25px;" title="BORRAR">mdi-trash-can</v-icon>
                <router-link :to="{ path: `/registrar-presupuesto?id=${item.id}` }">
                    <v-icon small class="mr-2" :color="item.presupuesto == null ? 'orange darken-1' : 'success'" style="font-size: 25px" title="Presupuesto">
                        mdi-card-text-outline
                    </v-icon>
                </router-link>
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
                    <v-btn color="error" large @click="dialog = false;selectedItem={}">Cancelar</v-btn>
                    <v-btn color="success" large @click="deletePotencial()">Confirmar</v-btn>
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
                potenciales: [],
                selectedItem: 0,
                dialog: false,
                headers: [{
                        text: 'Cliente',
                        value: 'usuario.nombre',
                        sortable: false
                    },
                    {
                        text: 'Tipo',
                        value: 'estado.nombre',
                        sortable: false
                    },
                    {
                        text: 'Fecha Alta',
                        value: 'fecha_alta',
                        sortable: false
                    },
                    {
                        text: 'Servicio',
                        value: 'servicio.nombre',
                        sortable: false
                    },
                    {
                        text: 'Pvp (€)',
                        value: 'pvp',
                        sortable: false
                    },
                    {
                        text: 'Acciones',
                        value: 'action',
                        sortable: false
                    }
                ],
            }
        },
        created() {
            this.getAllPotenciales();
        },
        methods: {
            getAllPotenciales() {
                axios.get(`api/get-all-potenciales`).then(res => {
                    this.potenciales = res.data
                }, res => {
                    this.$toast.error('Error consultando potenciales')
                })
            },
            deletePotencial() {
                this.dialog = false
                axios.get(`api/delete-potencial/${this.potenciales[this.selectedItem].id}`).then(res => {
                    this.potenciales.splice(this.selectedItem, 1)
                    this.$toast.sucs('Potencial eliminado')
                }, err => {
                    this.$toast.error('Error eliminando Potencial')
                })
            },
            openModal(item) {
                this.selectedItem = this.potenciales.indexOf(item);
                this.dialog = true;
            },
        },
        computed: {
            isloading: function() {
                return this.$store.getters.getloading
            },
        }
    }
</script>