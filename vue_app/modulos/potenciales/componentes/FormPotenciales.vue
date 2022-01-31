<template>
    <v-container>
        <loader v-if="isloading"></loader>
        <v-card>
            <v-toolbar flat color="#1d2735" dark>
                <v-toolbar-title>Guardar / Editar Potencial</v-toolbar-title>
            </v-toolbar>
            <v-tabs horizontal>
                <v-tab>
                    <v-icon left>mdi-book-variant-multiple</v-icon>Potencial
                </v-tab>
                <v-tab>
                    <v-icon left>mdi-account</v-icon>Cliente
                </v-tab>
                <v-tab>
                    <v-icon left>mdi-folder-multiple-outline</v-icon>Archivo
                </v-tab>

                <v-tab-item class="pa-3 ma-1">
                    <v-card flat>
                        <v-row dense>
                            <v-col cols="12" md="6" class="pt-3 pl-0 pb-0">
                                <v-select dense outlined :error-messages="errors.errors['servicio_id'] ? errors.errors['servicio_id'][0] : null" v-model="potencial.servicio_id" :items="servicios" item-text="nombre" item-value="id"
                                  label="Servicio Contratado">
                                </v-select>
                            </v-col>
                            <v-col cols="12" md="6" class="pl-3 pt-0 pb-0">
                                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="potencial.fecha_alta" transition="scale-transition"
                                  :error-messages="errors.errors['fecha_alta'] ? errors.errors['fecha_alta'][0] : null" offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="potencial.fecha_alta" label="Fecha de Alta Servicio" append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                                        </v-text-field>
                                    </template>
                                    <v-date-picker color="#1d2735" first-day-of-week="1" v-model="potencial.fecha_alta" no-title scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn text color="red" @click="menu2 = false"><strong>Cancelar</strong></v-btn>
                                        <v-btn text color="success" @click="$refs.menu2.save(potencial.fecha_alta)"><strong>OK</strong></v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="12">
                                <!-- Descripcion -->
                                <small><strong>Detalles del Servicio contratado</strong></small>
                                <!-- Editor -->
                                <ckeditor style="cursor:none" :editor="editor" v-model="potencial.detalle_servicio" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="4" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-select dense outlined :error-messages="errors.errors['estado_id'] ? errors.errors['estado_id'][0] : null" v-model="potencial.estado_id" :items="estados" item-text="nombre" item-value="id" label="Estado">
                                    </v-select>
                                </v-col>
                            </v-col>
                            <v-col cols="12" md="4" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-text-field dense outlined :error-messages="errors.errors['pvp'] ? errors.errors['pvp'][0] : null" v-model="potencial.pvp" label="Precio Potencial">
                                    </v-text-field>
                                </v-col>
                            </v-col>
                            <v-col cols="12" md="4" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-text-field dense outlined v-model="potencial.pvp_gasto" label="Gasto Externo"></v-text-field>
                                </v-col>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="12">
                                <!-- Descripcion -->
                                <small><strong>Detalles de Gasto Externo</strong></small>
                                <!-- Editor -->
                                <ckeditor style="cursor:none" :editor="editor" v-model="potencial.detalles_gasto" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-tab-item>

                <v-tab-item class="pa-3 ma-1">
                    <v-card flat>
                        <v-row dense>
                            <v-row>
                                <v-col cols="12" md="6" align="center">
                                    <v-card class="mt-3 mr-4 mb-6" style="max-height: 100px !important; max-width: 250px !important;" elevation="0">
                                        <v-img v-if="potencial.usuario.avatar" :src="potencial.usuario.avatar" contain aspect-ratio="3"></v-img>
                                        <v-img v-if="!potencial.usuario.avatar" src="/default.png" contain aspect-ratio="3"></v-img>
                                        <v-btn class="mt-1" v-if="potencial.usuario.avatar" fab x-small color="error" @click="defaultAvatar()">
                                            <v-icon class="white--text">mdi-close-circle-outline</v-icon>
                                        </v-btn>
                                        <!--<v-img v-if="potencial.usuario.avatar" :src="potencial.usuario.avatar" contain aspect-ratio="3">
                                            <v-file-component v-on:file_changed="fileChanged" style="color: transparent !important;"></v-file-component>
                                        </v-img>
                                        <v-img v-if="!potencial.usuario.avatar" src="/default.png" contain aspect-ratio="3">
                                            <v-file-component v-on:file_changed="fileChanged" style="color: transparent !important;"></v-file-component>
                                        </v-img>-->
                                    </v-card>
                                </v-col>
                                <v-col cols="12" md="5" class="mt-6" style="max-width: 360px !important;" align="center">
                                    <v-file-component v-on:file_changed="fileChanged"></v-file-component>
                                </v-col>
                            </v-row>
                            <v-col cols="12" md="6" class="mt-4">
                                <v-autocomplete v-if="!potencial.usuario_id" dense outlined prepend-icon="mdi-account-search-outline" v-model="potencial.usuario"
                                  :error-messages="errors.errors['usuario.nombre'] ? errors.errors['usuario.nombre'][0] : null" return-object :items="usuarios" item-value="id" item-text="nombre" label="Seleccione o Cree Usuario Nuevo">
                                </v-autocomplete>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="4">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.nombre'] ? errors.errors['usuario.nombre'][0] : null" v-model="potencial.usuario.nombre" label="Nombre y Apellidos" required>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.nombre_fiscal'] ? errors.errors['usuario.nombre_fiscal'][0] : null" v-model="potencial.usuario.nombre_fiscal" label="Nombre Fiscal" required>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.cif'] ? errors.errors['usuario.cif'][0] : null" v-model="potencial.usuario.cif" label="CIF/DNI">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="4">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.telefono'] ? errors.errors['usuario.telefono'][0] : null" v-model="potencial.usuario.telefono" label="Teléfono" :rules="[rules.number_rule]" counter
                                  maxlength="9" required>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.email'] ? errors.errors['usuario.email'][0] : null" v-model="potencial.usuario.email" label="Email" required>
                                </v-text-field>
                            </v-col>
                            <!-- <v-col cols="12" md="4">
                                <v-select dense outlined :error-messages="errors.errors.role ? errors.errors.role[0] : null" :items="roles"
                                    item-value="id" item-text="role" label="Seleccione un Perfil" v-model="potencial.usuario.role" readonly>
                                </v-select>
                            </v-col> -->
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="4">
                                <v-text-field dense outlined label="Direccion" v-model="potencial.usuario.direccion"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.codigo_postal'] ? errors.errors['usuario.codigo_postal'][0] : null" v-model="potencial.usuario.codigo_postal" label="Codigo Postal"
                                  :rules="[rules.number_rule]" counter maxlength="5" required>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field dense outlined v-model="potencial.usuario.localidad" label="Localidad"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-autocomplete dense outlined v-model="potencial.usuario.provincia_id" :items="provincias" item-value="id" item-text="nombre" label="Provincia">
                                </v-autocomplete>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="6" class="mt-2">
                                <v-text-field dense outlined :error-messages="errors.errors['usuario.cuenta'] ? errors.errors['usuario.cuenta'][0] : null" v-model="potencial.usuario.cuenta" label="Cuenta Bancaria" required></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="potencial.usuario.fecha_alta" transition="scale-transition" offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="potencial.usuario.fecha_alta" label="Fecha de Alta Cliente" append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                                        </v-text-field>
                                    </template>
                                    <v-date-picker color="#1d2735" first-day-of-week="1" v-model="potencial.usuario.fecha_alta" no-title scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn text color="red" @click="menu = false"><strong>Cancelar</strong></v-btn>
                                        <v-btn text color="success" @click="$refs.menu.save(potencial.usuario.fecha_alta)"><strong>OK</strong></v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="12" class="mb-3">
                                <!-- Descripcion -->
                                <small><strong>Observaciones</strong></small>
                                <!-- Editor -->
                                <ckeditor style="cursor:none" :editor="editor" v-model="potencial.usuario.observaciones" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-tab-item>

                <v-tab-item class="pa-3 ma-1">
                    <tab-archivo tipo="potencial" :item="potencial"></tab-archivo>
                </v-tab-item>

            </v-tabs>
        </v-card>
        <v-row class="mt-3">
            <!-- Botones Potencial -->
            <v-col cols="12">
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab :to="{ path: `/registrar-presupuesto?id=${$route.query.id}` }" :loading="isloading" :disabled="isloading" color="blue-grey lighten-2" class="mx-2" v-bind="attrs" v-on="on" readonly>
                            <v-icon class="white--text">mdi-text-box-search-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Ver Presupuesto</span>
                </v-tooltip>
            </v-col>
        </v-row>
        <v-row class="mt-3">
            <!-- Botones Navegacion -->
            <v-col cols="12">
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click="volver" :loading="isloading" :disabled="isloading" color="blue" class="mx-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-arrow-left-bold-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Volver</span>
                </v-tooltip>
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click="savePotencial" :loading="isloading" :disabled="isloading" color="success" class="mx-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-content-save-all</v-icon>
                        </v-btn>
                    </template>
                    <span>Guardar Potencial</span>
                </v-tooltip>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import {
        provincias_mixin
    } from '../../../global_mixins/provincias_mixin';
    import {
        servicios_mixin
    } from '../../../global_mixins/servicios_mixin';
    import {
        estados_mixin
    } from '../../../global_mixins/estados_mixin';
    import VFileComponent from '../../../global_components/VFileComponent.vue';
    import FileInput from '../../../global_components/FileInput.vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    import tabArchivo from '../../../global_components/tabArchivo'

    export default {
        components: {
            'file-input': FileInput,
            VFileComponent,
            tabArchivo
        },
        mixins: [provincias_mixin, servicios_mixin, estados_mixin],
        data() {
            return {
                editor: ClassicEditor,
                editorData: '<p>Escriba Aqui Observaciones o contenido.</p>',
                editorConfig: {
                    toolbar: {
                        items: ['heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'inserttable'],
                    },
                },
                menu: false,
                menu: '',
                menu2: false,
                menu2: '',
                potencial: {
                    id: "",
                    pvp: '',
                    pvp_gasto: 0,
                    usuario: {
                        id: '',
                        user_id: localStorage.getItem('user_id'),
                        nombre: '',
                        nombre_fiscal: '',
                        cif: '',
                        telefono: '',
                        email: '',
                        role: 2,
                        direccion: '',
                        codigo_postal: '',
                        localidad: '',
                        provincia_id: 35,
                        cuenta: '00000000000000000000',
                        fecha_alta: new Date().toISOString().substr(0, 10),
                        observaciones: null,
                        avatar: '',
                    },
                    estado_id: 3,
                    servicio: {},
                    fecha_alta: new Date().toISOString().substr(0, 10),
                    detalle_servicio: null,
                    detalles_gasto: null,
                    archivos: []
                },
                servicio: {
                    id: 1
                },
                estado: {
                    id: 1
                },
                usuario: {
                    id: '',
                    user_id: localStorage.getItem('user_id'),
                    nombre: '',
                    nombre_fiscal: '',
                    cif: '',
                    telefono: '',
                    email: '',
                    role: 2,
                    direccion: '',
                    codigo_postal: '',
                    localidad: '',
                    provincia_id: 35,
                    cuenta: '00000000000000000000',
                    fecha_alta: new Date().toISOString().substr(0, 10),
                    observaciones: null,
                    avatar: '',
                },
                roles: [{
                        id: 1,
                        role: 'Administrador'
                    },
                    {
                        id: 2,
                        role: 'Cliente'
                    }
                ],
                usuarios: [],
                files: [],
                imagePreview: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                user_id: localStorage.getItem('user_id'),
                rules: {
                    number_rule: value => /^\d+$/.test(value) || 'Campo numérico'
                },
            }
        },
        created() {
            this.getUsuarios();

            if (this.$route.query.id) {
                this.getPotencialById(this.$route.query.id)
            }
        },
        methods: {
            getPotencialById(potencial_id) {
                axios.get(`api/get-potencial-by-id/${potencial_id}`).then(res => {
                    this.potencial = res.data
                }, res => {
                    this.$toast.error('Error consultando potencial')
                })
            },

            async savePotencial() {
                try {
                    /*esto no entiendo muy bien que hace*/
                    this.user_id = localStorage.getItem('user_id');

                    this.potencial.user_id = this.user_id;

                    var formData = new FormData()

                    formData.append('potencial', JSON.stringify(this.potencial))

                    let archivos = this.potencial.archivos.filter(archivo => !archivo.id)

                    archivos.forEach((item, i) => formData.append('itemsFiles[' + i + ']', item.file))

                    await axios.post("api/save-potencial", formData);

                    this.$toast.sucs("Proyecto guardado con exito");
                    this.$router.push('/lista-potenciales');
                } catch (error) {
                    console.log(error.response.data.errors);
                    this.$toast.error("Error guardando Potencial, Compruebe todos los campos requeridos");
                }
            },
            /*savePotencial() {
                this.user_id = localStorage.getItem('user_id');
                this.potencial.user_id = this.user_id;
                axios.post('api/save-potencial', this.potencial).then(res => {
                    this.$toast.sucs('Potencial guardado con exito')
                    this.$router.push('/lista-potenciales')
                }, res => {
                    this.$toast.error('Error guardando Potencial, Compruebe todos los campos requeridos')
                })
            },*/
            volver() {
                this.$router.push(`/lista-potenciales`)
            },
            getUsuarios() {
                axios.get(`api/get-usuarios`).then(res => {
                    this.usuarios = res.data.users;
                }, err => {
                    this.$toast.error('Error consultando clientes')
                })
            },
            getUsuarioById(usuario_id) {
                axios.get(`api/get-usuario-by-id/${usuario_id}`).then(res => {
                    this.usuario = res.data.user
                }, res => {
                    this.$toast.error('Error consultando Usuario')
                })
            },
            fileChanged(base_64) {
                this.potencial.usuario.avatar = base_64
            },
            defaultAvatar() {
                this.potencial.usuario.avatar = null;
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
<style>
    div.v-messages.theme--light {
        margin-top: -1px !important;
        margin-bottom: -1px !important;
        padding-top: -1px !important;
        padding-bottom: -1px !important;
    }

    div.v-text-field__details {
        margin-top: -1px !important;
        margin-bottom: -1px !important;
        padding-top: -1px !important;
        padding-bottom: -1px !important;
    }
</style>