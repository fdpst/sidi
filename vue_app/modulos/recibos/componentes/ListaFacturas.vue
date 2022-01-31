<template>
    <div class="background my-container">

        <v-custom-title text="Lista Facturas"></v-custom-title>

        <v-btn rounded depressed color="blue" class="mt-5 white--text" :to="{ path: `/guardar-recibo?tipo=factura` }">Nuevo</v-btn>

        <loader v-if="isloading"></loader>

        <v-row>
            <v-col cols="12" md="4">
                <v-text-field prepend-icon="mdi-account-search" v-model="search" label="busqueda"></v-text-field>
            </v-col>
        </v-row>

        <v-data-table :headers="headers" :items="facturas" :search="search" disable-pagination hide-default-footer item-key="id" class="elevation-1">

            <template v-slot:item.archivo="{ item }">

                <a v-if="item.nombre_factura != null" target="_blank" :href="item.factura_path">
                    <v-icon medium color="blue" class="mr-2">
                        mdi-file-pdf-outline
                    </v-icon>
                </a>

            </template>

            <template v-slot:item.action="{ item }">

                <router-link :to="{ path: `/guardar-recibo?id=${item.id}&tipo=factura` }" class="action-buttons">
                    <v-icon small class="mr-2" color="blue">
                        mdi-pencil
                    </v-icon>
                </router-link>

                <!--<v-icon @click="deleteFactura(item)" small class="mr-2" color="red">
                    mdi-trash-can
                </v-icon>-->

            </template>

        </v-data-table>

    </div>

</template>

<script>
    export default {
        data() {
            return {
                search: '',
                facturas: [],
                headers: [{
                        text: 'Nro.Factura',
                        align: 'left',
                        value: 'nro_factura',
                    }, {
                        text: 'Fecha',
                        value: 'fecha',
                    },
                    {
                        text: 'Cliente',
                        value: 'cliente'
                    },
                    {
                        text: 'total',
                        value: 'total'
                    },
                    {
                        text: 'PDF',
                        value: 'archivo'
                    },
                    {
                        text: 'Acciones',
                        value: 'action',
                        sortable: false
                    },
                ],
            }
        },
        created() {
            this.getFacturas()
        },
        methods: {
            getFacturas() {
                axios.get(`api/get-facturas/${localStorage.getItem('user_id')}`).then(res => {
                    this.facturas = res.data
                }, err => {
                    this.$toast.error('Error consultando facturas')
                })
            },
            deleteFactura(item) {
                axios.get(`api/delete-factura/${item.id}`).then(res => {
                    this.facturas.splice(this.facturas.indexOf(item), 1)
                    this.$toast.sucs('Factura eliminada')
                }, err => {
                    this.$toast.error('Error eliminando factura')
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