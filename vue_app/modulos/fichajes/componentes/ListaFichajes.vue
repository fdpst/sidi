<template>
    <v-card class="pa-3 ma-3">
        <v-toolbar flat color="#1d2735" dark>
            <v-icon class="white--text" style="font-size: 45px;">mdi-card-account-details-outline</v-icon><pre><v-toolbar-title><h2 style="margin-left:20px;"> Lista Fichajes</h2></v-toolbar-title></pre>
        </v-toolbar>
        <loader v-if="isloading"></loader>
        <v-row v-if="rol == 1">
            <v-col cols="12" md="4">
                <v-text-field prepend-icon="mdi-account-search" v-model="search" label="Buscar"></v-text-field>
            </v-col>
        </v-row>
        <v-row style="margin-bottom:15px; margin-top:15px;">
            <v-col cols="12" md="3" v-if="rol == 1">
                <v-select dense outlined v-model="filtros.usuario" :items="usuarios" item-text="nombre" item-value="id" label="Usuarios">
                </v-select>
            </v-col>
            <v-col cols="12" md="3">
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="filtros.fecha_inicio" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="filtros.fecha_inicio" label="Fecha Inicio"
                            append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                        </v-text-field>
                    </template>
                    <v-date-picker color="#1d2735" first-day-of-week="1" v-model="filtros.fecha_inicio" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="red" @click="menu = false"><strong>Cancelar</strong></v-btn>
                        <v-btn text color="success" @click="$refs.menu.save(filtros.fecha_inicio)"><strong>OK</strong></v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="3">
                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="filtros.fecha_fin" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="filtros.fecha_fin" label="Fecha Fin"
                            append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                        </v-text-field>
                    </template>
                    <v-date-picker color="#1d2735" first-day-of-week="1" v-model="filtros.fecha_fin" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="red" @click="menu2 = false"><strong>Cancelar</strong></v-btn>
                        <v-btn text color="success" @click="$refs.menu2.save(filtros.fecha_fin)"><strong>OK</strong></v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="3">
                <v-tooltip right>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click= "filtrar" :loading="isloading" :disabled="isloading" color="orange darken-1" class="mt-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-filter</v-icon>
                        </v-btn>
                    </template>
                    <span>Filtrar</span>
                </v-tooltip>
            </v-col>
        </v-row>       
        <v-data-table dense :headers="headers" :items="fichajes" :search="search" item-key="id" class="elevation-1" :sort-by="['nombre']" :sort-desc="[false]">
        </v-data-table>
    </v-card>
</template>
<script>
    export default {
        data() {
            return {
                menu:false,
                menu2:false,
                usuarios:[],
                filtros:{
                    fecha_inicio: null,
                    fecha_fin: null,
                    usuario: null
                },
                search: '',  
                headers: [
                    // {text: 'Id',value: 'id',sortable: false},
                    {text: 'Usuario', value: 'nombre',sortable: false},
                    {text: 'Fecha',value: 'fecha_fichaje',sortable: true},
                    {text: 'Hora',value: 'hora_fichaje',sortable: false},
                ],
                fichajes: [],
                rol:''
            }
        },
        created() {
            this.rol = localStorage.getItem('role');
            this.getUsuarios();
            var date = new Date();
            var fecha_inicio = new Date(date.getFullYear(), date.getMonth(), 1);
            var fecha_fin = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            this.filtros.fecha_inicio = fecha_inicio.toISOString().slice(0, 10);
            this.filtros.fecha_fin = fecha_fin.toISOString().slice(0, 10);
            this.getFichajesByUser()
        },
        methods: {
            getFichajesByUser(){
                axios.post(`api/get-fichajes-by-user/${localStorage.getItem('user_id')}`, this.filtros).then(res => {                  
                    this.fichajes = res.data;
                    this.$toast.success('Fichajes actualizados correctamente')
                }, err => {
                    this.$toast.error('Error consultando Fichajes')
                })
            },
            getFichajes() {
                axios.post(`api/get-fichajes`, this.filtros).then(res => {                  
                    this.fichajes = res.data;
                    this.$toast.success('Fichajes actualizados correctamente')
                }, err => {
                    this.$toast.error('Error consultando Fichajes')
                })
            },            
            filtrar(){
                if (this.rol != 1){
                    this.filtros.usuario = localStorage.getItem('user_id')
                }
                axios.post(`api/get-fichajes`, this.filtros).then(res => {                  
                    this.fichajes = res.data;
                    this.$toast.success('Fichajes actualizados correctamente')
                }, err => {
                    this.$toast.error('Error consultando Fichajes')
                })
            },
            getUsuarios() {
                axios.post(`api/get-usuarios-empleados`, this.filtros).then(res => {                  
                    this.usuarios = res.data.users.data;
                }, err => {
                    this.$toast.error('Error consultando Fichajes')
                })
            }      
        },
        computed: {
            isloading: function() {
                return this.$store.getters.getloading
            },
        }
    }
</script>