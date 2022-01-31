<template>
    <v-card class="pa-3 ma-3">
        <v-toolbar flat color="#1d2735" dark>
            <v-icon class="white--text" style="font-size: 45px">
                mdi-cash-minus
            </v-icon>
            <pre><v-toolbar-title><h2>Lista Gastos</h2></v-toolbar-title></pre>
        </v-toolbar>
        <loader v-if="isloading"></loader>
        <v-tooltip right>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    fab
                    :to="{ path: `/guardar-gasto` }"
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
            <span>Nuevo Gasto</span>
        </v-tooltip>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field prepend-icon="mdi-account-search" v-model="search" label="Codigo / Importe"></v-text-field>
            </v-col>
            <v-col cols="12" md="2">
                <v-text-field prefix="€" readonly disabled v-model="total" label="Total"></v-text-field>
            </v-col>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="gastos"
            :search="search"
            :items-per-page="10"
            item-key="id"
            class="elevation-1">
            <template v-slot:item.created_at="{ item }">
                {{item.created_at.substr(0, 10) }}
            </template>
            <template v-slot:item.action="{ item }">

                 <a v-if="item.archivo!=null" target="_blank"  @click="down(item)">
                    <v-icon medium color="orange" class="mr-2">
                        mdi-cloud-download
                    </v-icon>
                </a>
                <router-link :to="{ path: `/update-gasto/`+item.id }" class="action-buttons">
                    <v-icon
                        small
                        class="mr-2"
                        color="#1d2735"
                        style="font-size: 25px"
                        title="EDITAR"
                        >mdi-pencil-outline</v-icon>
                </router-link>
                <v-icon @click="openModal(item)" small class="mr-2" color="red" style="font-size: 25px;" title="BORRAR">
                    mdi-trash-can
                </v-icon>
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
                        @click="deleteGasto()">Confirmar</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
    import {
        date_mixin
    } from '../mixins/date_mixin'

    import rangoFechas from '../rangoFechas'

    export default {
        mixins: [date_mixin],

        components: {
            rangoFechas
        },

        data() {
            return {
                url:  `api/get-gastos/${localStorage.getItem('user_id')} `,
                search: '',
                gastos: [],
                selectedItem: 0,
                dialog: false,
                headers: [{
                        text: 'Codigo',
                        align: 'left',
                        value: 'codigo',
                    },
                    {
                        text: 'Fecha',
                        value: 'created_at',
                        filterable: false,
                    },
                    {
                        text: 'Importe',
                        value: 'importe',
                    },
                    {
                        text: 'Tipo',
                        value: 'tiposgasto.nombre',
                    },
                    {
                        text: 'Acciones',
                        value: 'action',
                        sortable: false,
                    },
                ],
            }
        },
        mounted() {
            // this.$emit('hacer_busqueda')
            this.getGastos()

        },
        methods: {

            getGastos(){
                axios.get(this.url).then(res => {
                    this.gastos = res.data.gastos.data
                    //console.log(this.gastos);

                }, res => {
                    this.$toast.error('Error consultando Gasto')
                })
            },

            setGastos(data) {
                if (data.length > 0) {
                    this.gastos = data
                    return
                }
                this.gastos = []
                this.$toast.sucs('No se encontraron registros')
            },

            deleteGasto() {
                this.dialog = false
                axios.get(`api/delete-gasto/${this.gastos[this.selectedItem].id}`).then(res => {
                    this.gastos.splice(this.selectedItem, 1);
                    this.$toast.sucs('Gasto eliminado con exito')
                }, err => {
                    this.$toast.error('Error Eliminando gasto')
                })
            },

            down(item){
                let downloadPath ='/storage/documentos/userId_' +localStorage.user_id + '/factura_recibidas/' + item.archivo
                this.downloadFiles(downloadPath,item.archivo)
            },
            downloadFiles(url, filename) {
                fetch(url).then(function(t) {
                    return t.blob().then((b)=>{
                            var a = document.createElement("a");
                            a.href = URL.createObjectURL(b);
                            a.setAttribute("download", filename);
                            a.click();
                        }
                    );
                });
            },
            openModal(item){
                this.selectedItem = this.gastos.indexOf(item);
                this.dialog = true;
            },
        },
        computed: {
            isloading: function() {
                return this.$store.getters.getloading
            },
            total: function() {

                let total = this.gastos.reduce((acc, gasto) => {
                    return acc + gasto.importe
                }, 0)

                return parseFloat(total).toFixed(2)
            },
            userId(){
                return localStorage.getItem('user_id')
            }
        }
    }
</script>
