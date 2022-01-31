<template>
    <v-container>
        <loader v-if="isloading"></loader>
        <v-card>
            <v-toolbar flat color="#1d2735" dark>
                <v-toolbar-title>Guardar / Editar Ingreso</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-row dense>
                    <v-col cols="12" md="4">
                        <v-autocomplete
                            outlined
                            prepend-icon="mdi-account-search-outline"
                            v-model="ingreso.proyecto_id"
                            :error-messages="
                            errors.errors['ingreso.proyecto_id'] ? errors.errors['ingreso.proyecto_id'][0] : null"
                            :items="proyectos"
                            item-value="id"
                            item-text="nombre"
                            label="Seleccione un proyecto"
                        >
                        </v-autocomplete>
                        <!--<v-text-field class="my-input" filled :error-messages="errors.errors.codigo ? errors.errors.codigo[0] : null" v-model="ingreso.codigo" label="Codigo" required></v-text-field>-->
                    </v-col>

                    <v-col cols="12" md="2">
                        <v-text-field filled :error-messages="errors.errors['ingreso.importe'] ? errors.errors['ingreso.importe'][0] : null" v-model="ingreso.importe" label="Importe" required></v-text-field>
                    </v-col>
                </v-row>

                <v-row dense>
                    <v-col cols="12" md="3">
                        <v-textarea v-model="ingreso.descripcion" label="Descripción"></v-textarea>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <v-row class="mt-3">
            <!-- Botones Navegacion -->
            <v-col cols="12">
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            fab
                            @click="$router.push('/lista-ingresos')"
                            :loading="isloading"
                            :disabled="isloading"
                            color="blue"
                            class="mx-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon class="white--text">mdi-arrow-left-bold-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Volver</span>
                </v-tooltip>
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            fab
                            @click="saveIngreso"
                            :loading="isloading"
                            :disabled="isloading"
                            color="success"
                            class="mx-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon class="white--text">mdi-content-save-all</v-icon>
                        </v-btn>
                    </template>
                    <span>Guardar Ingreso</span>
                </v-tooltip>
            </v-col>
        </v-row>
    </v-container>

</template>

<script>
    export default {
        data() {
            return {
                ingreso: {
                    id: '',
                    codigo: '',
                    importe: '',
                    descripcion: '',
                    user_id: localStorage.getItem('user_id'),
                    proyecto_id: '',
                },
                proyectos: [],
            }
        },

        created() {
            this.getAllProyectos();
            //si editamos un ingreso
            if (this.$route.query.id) {
                this.getIngresoById(this.$route.query.id)
            }
            else {
                this.getRandomCode();
            }
            //si añadimos un ingreso desde el enlace de listado de pendientes de pago
            if(this.$route.query.identi && this.$route.query.tipo){
                this.ingreso.codigo = this.$route.query.tipo.substring(0,3) + this.$route.query.identi
            }
        },

        methods: {
            getIngresoById(ingreso_id) {
                axios.get(`api/get-ingreso-by-id/${ingreso_id}`).then(res => {
                    this.ingreso = res.data
                }, res => {
                    this.$toast.error('Error consultando Ingreso')
                })
            },

            getAllProyectos() {
                axios.get(`api/get-all-proyectos`).then(
                    (res) => {
                        this.proyectos = res.data;
                    },
                    (res) => {
                        this.$toast.error("Error consultando proyectos");
                    }
                );
            },

            saveIngreso() {
                console.log(this.ingreso)
                axios.post('api/save-ingreso', this.ingreso).then(res => {
                    this.$toast.sucs('Ingreso guardado con exito')
                    this.$router.push('/lista-ingresos')
                }, res => {
                    this.$toast.error('Error guardando ingreso')
                })
            },

            getRandomCode () {
                let randomChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                let result = '';
                for ( var i = 0; i < 5; i++ ) {
                    result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
                }
                this.ingreso.codigo = result;
            },
        },
        computed: {
            isloading() {
                return this.$store.getters.getloading
            },

            errors() {
                return this.$store.getters.geterrors
            }
        }
    }
</script>

<style media="screen">
    .my-input input {
        text-transform: uppercase;
    }
</style>
