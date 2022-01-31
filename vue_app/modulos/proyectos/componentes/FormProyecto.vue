<template>
    <v-container>
        <loader v-if="isloading"></loader>
        <v-card>
            <v-toolbar flat color="#1d2735" dark>
                <v-toolbar-title>Guardar / Editar Proyecto</v-toolbar-title>
            </v-toolbar>
            <v-tabs horizontal>
                <v-tab>
                    <v-icon left>mdi-book-variant-multiple</v-icon>Proyecto
                </v-tab>
                <v-tab>
                    <v-icon left>mdi-account</v-icon>Cliente
                </v-tab>
                <v-tab>
                    <v-icon left>mdi-list-status</v-icon>Estado del Proyecto
                </v-tab>
                <v-tab>
                    <v-icon left>mdi-folder-multiple-outline</v-icon>Archivo
                </v-tab>

                <v-tab-item class="pa-3 ma-1">
                    <v-card flat>
                        <v-row dense>
                            <v-text-field dense outlined v-model="proyecto.nombre" label="Nombre"></v-text-field>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="6" class="pt-3 pl-0 pb-0">
                                <v-select dense outlined :error-messages="
                   errors.errors['proyecto.servicio_id'] ? errors.errors['proyecto.servicio_id'][0] : null
                 " v-model="proyecto.servicio_id" :items="servicios" item-text="nombre" item-value="id" label="Producto Contratado">
                                </v-select>
                            </v-col>
                            <v-col cols="12" md="6" class="pl-3 pt-0 pb-0">
                                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="proyecto.fecha_alta" transition="scale-transition" :error-messages="
                   errors.errors['proyecto.fecha_alta']
                     ? errors.errors['proyecto.fecha_alta'][0]
                     : null
                 " offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="proyecto.fecha_alta" label="Fecha de Alta Servicio" append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                                        </v-text-field>
                                    </template>
                                    <v-date-picker color="#1d2735" first-day-of-week="1" v-model="proyecto.fecha_alta" no-title scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn text color="red" @click="menu2 = false"><strong>Cancelar</strong></v-btn>
                                        <v-btn text color="success" @click="$refs.menu2.save(proyecto.fecha_alta)"><strong>OK</strong></v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="12">
                                <!-- Descripcion -->
                                <small><strong>Detalles del Servicio contratado</strong></small>
                                <!-- Editor -->
                                <ckeditor style="cursor: none" :editor="editor" v-model="proyecto.detalle_servicio" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="3" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-select dense outlined :error-messages="
                     errors.errors['proyecto.estado_id'] ? errors.errors['proyecto.estado_id'][0] : null
                   " v-model="proyecto.estado_id" :items="estados" item-text="nombre" item-value="id" label="Estado">
                                    </v-select>
                                </v-col>
                            </v-col>
                            <v-col cols="12" md="3" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-text-field dense outlined :error-messages="
                     errors.errors['proyecto.pvp'] ? errors.errors['proyecto.pvp'][0] : null
                   " v-model="proyecto.pvp" label="Precio Proyecto">
                                    </v-text-field>
                                </v-col>
                            </v-col>
                            <v-col cols="12" md="3" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-text-field dense outlined v-model="proyecto.pvp_gasto" label="Gasto Externo"></v-text-field>
                                </v-col>
                            </v-col>
                            <v-col cols="12" md="3" class="mx-0 my-0 px-0 py-0">
                                <v-col cols="12" md="12">
                                    <v-text-field dense outlined v-model="proyecto.porc_realizado" label="% Realizado"></v-text-field>
                                </v-col>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <v-col cols="12" md="12">
                                <!-- Descripcion -->
                                <small><strong>Detalles de Gasto Externo</strong></small>
                                <!-- Editor -->
                                <ckeditor style="cursor: none" :editor="editor" v-model="proyecto.detalles_gasto" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-tab-item>

                <v-tab-item class="pa-3 ma-1">
                    <form-usuario :usuarios="usuarios" :provincias="provincias" :editorConfig="editorConfig" :editor="editor" :editorData="editorData" :errors="errors" :proyecto="proyecto"></form-usuario>
                </v-tab-item>

                <!-- Estado del Proyecto -->

                <v-tab-item class="pa-3 ma-1">
                    <v-card flat>
                        <div class="font-weight-bold mb-3 black--text">
                            PROGRESO DEL PROYECTO
                        </div>
                        <v-row dense>
                            <v-col cols="12" md="6" class="mt-2">
                                <v-text-field v-model="descripcion" outlined dense label="Descripcion"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-menu ref="menu3" v-model="menu3" :close-on-content-click="false" :return-value.sync="fecha" transition="scale-transition" :error-messages="
                               errors.errors.fecha
                               ? errors.errors.fecha[0]
                               : null
                           " offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="fecha" label="Fecha" append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                                        </v-text-field>
                                    </template>
                                    <v-date-picker color="#1d2735" first-day-of-week="1" v-model="fecha" no-title scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn text color="red" @click="menu2 = false"><strong>Cancelar</strong></v-btn>
                                        <v-btn text color="success" @click="$refs.menu3.save(fecha)"><strong>OK</strong></v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col md="6">
                                <v-checkbox label="Finalizado" color="primary" v-model="finalizado"></v-checkbox>
                            </v-col>
                        </v-row>
                        <div class="mb-5">
                            <v-btn @click="addStatus()" color="primary" class="white--text" rounded>
                                Agregar progreso
                            </v-btn>
                        </div>

                        <v-data-table :headers="headers" :items="itemsEstado" :items-per-page="5" class="elevation-1">
                            <template v-slot:item.fecha="{ item }">
                                {{ item.fecha.substr(0, 10) }}
                            </template>
                            <template v-slot:item.finalizado="{ item }">
                                <v-chip dense @click="changeFinalizado(item)" class="ma-2 white--text" :color="item.finalizado ? 'green' : 'red'">
                                    {{ item.finalizado ? 'finalizado' : 'en progreso' }}
                                </v-chip>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <v-icon @click="editItem(item)" color="primary">mdi-pencil</v-icon>
                                <v-icon @click="deleteItem(item)" color="red">mdi-delete</v-icon>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-tab-item>

                <v-tab-item class="pa-3 ma-1">
                    <tab-archivo tipo="proyecto" :item="proyecto"></tab-archivo>
                </v-tab-item>
            </v-tabs>
        </v-card>

        <v-row class="mt-3">
            <!-- Botones Proyecto -->
            <v-col cols="12">
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab :to="{ path: `/registrar-presupuesto?id=${$route.query.id}` }" :loading="isloading" :disabled="isloading" color="blue-grey lighten-2" class="mx-2" v-bind="attrs" v-on="on" readonly>
                            <v-icon class="white--text">mdi-text-box-search-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Ver Presupuesto</span>
                </v-tooltip>
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab :to="{ path: `/registrr-facturas?proyecto=${$route.query.id}` }" :loading="isloading" :disabled="isloading" class="mx-2" v-bind="attrs" v-on="on" readonly style="background-color: #1d2735 !important">
                            <v-icon class="white--text">mdi-calculator</v-icon>
                        </v-btn>
                    </template>
                    <span>Crear Factura</span>
                </v-tooltip>
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click="saveProyecto" :loading="isloading" :disabled="isloading" class="mx-2" v-bind="attrs" v-on="on" readonly style="background-color: #1d2735 !important">
                            <v-icon class="white--text">mdi-file-pdf</v-icon>
                        </v-btn>
                    </template>
                    <span>Ver Facturas Enviadas</span>
                </v-tooltip>
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click="saveProyecto" :loading="isloading" :disabled="isloading" color="amber accent-3" class="mx-2" v-bind="attrs" v-on="on" readonly>
                            <v-icon class="white--text">mdi-at</v-icon>
                        </v-btn>
                    </template>
                    <span>Enviar Mail</span>
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
                        <v-btn fab @click="saveProyecto" :loading="isloading" :disabled="isloading" color="success" class="mx-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-content-save-all</v-icon>
                        </v-btn>
                    </template>
                    <span>Guardar Proyecto</span>
                </v-tooltip>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    import {
        provincias_mixin
    } from "../../../global_mixins/provincias_mixin";
    import {
        servicios_mixin
    } from "../../../global_mixins/servicios_mixin";
    import {
        estados_mixin
    } from "../../../global_mixins/estados_mixin";
    import FileInput from "../../../global_components/FileInput.vue";
    import VFileComponent from '../../../global_components/VFileComponent.vue';
    import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

    import formUsuario from './formUsuario'
    import tabArchivo from '../../../global_components/tabArchivo'

    export default {
        components: {
            'file-input': FileInput,
            VFileComponent,
            formUsuario,
            tabArchivo
        },
        mixins: [provincias_mixin, servicios_mixin, estados_mixin],
        data() {
            return {
                editor: ClassicEditor,
                editorData: "<p>Escriba Aqui Observaciones o contenido.</p>",
                editorConfig: {
                    toolbar: {
                        items: [
                            "heading",
                            "bold",
                            "italic",
                            "bulletedList",
                            "numberedList",
                            "link",
                            "inserttable",
                        ],
                    },
                },
                menu: false,
                menu: "",
                menu2: false,
                menu2: "",
                menu3: false,
                menu3: "",
                proyecto: {
                    id: "",
                    pvp: "",
                    pvp_gasto: 0,
                    usuario: {
                        id: "",
                        user_id: localStorage.getItem("user_id"),
                        nombre: "",
                        nombre_fiscal: "",
                        cif: "",
                        telefono: "",
                        email: "",
                        role: 2,
                        direccion: "",
                        codigo_postal: "",
                        localidad: "",
                        provincia_id: 35,
                        cuenta: "00000000000000000000",
                        fecha_alta: new Date().toISOString().substr(0, 10),
                        observaciones: null,
                        avatar: null,
                    },
                    estado_id: 2,
                    servicio: {},
                    fecha_alta: new Date().toISOString().substr(0, 10),
                    detalle_servicio: null,
                    detalles_gasto: null,
                    nombre: '',
                    archivos: [],
                    porc_realizado: 0,
                },

                servicio: {
                    id: 1
                },
                estado: {
                    id: 1
                },

                descripcion: "",
                fecha: new Date().toISOString().substr(0, 10),
                estado_proyecto_id: "",
                id_items_proyecto: [],
                finalizado: false,
                headers: [{
                        text: "Descripcion",
                        value: "descripcion",
                        sortable: false
                    },
                    {
                        text: "Fecha",
                        value: "fecha",
                        sortable: false
                    },
                    {
                        text: "Estado",
                        value: "finalizado",
                        sortable: false
                    },
                    {
                        text: "Acciones",
                        value: "action",
                        sortable: false
                    }
                ],
                roles: [{
                        id: 1,
                        role: "Administrador"
                    },
                    {
                        id: 2,
                        role: "Cliente"
                    },
                ],
                usuarios: [],
                files: [],
                imagePreview: [],
                csrf: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                user_id: localStorage.getItem("user_id"),
                base_64_image: null,
                items: [],
                itemsEstado: [],
            };
        },
        created() {
            this.getUsuarios();
            if (this.$route.query.id) {
                this.getProyectoById(this.$route.query.id);
            }
        },
        //   watch: {
        //     file(val) {
        //       if (val != undefined) {
        //         const currentDate = new Date();
        //         const date = currentDate.getDate();
        //         const month = currentDate.getMonth();
        //         const year = currentDate.getFullYear();
        //         const hour = currentDate.getHours();
        //         const minutes = currentDate.getMinutes();
        //         const seconds = currentDate.getSeconds();

        //         this.items.push({
        //           name: val.name,
        //           date: `${year}-${month}-${date} ${hour}:${minutes}:${seconds}`,
        //         });
        //       }
        //     },
        //   },
        methods: {
            deleteFile(item) {
                const index = this.items.findIndex((val) => val.name == item.name);
                if (index > -1) {
                    this.items.splice(index, 1);
                }
            },

            //adaptamos el getProyectoById para traernos los items de estados en la edición
            async getProyectoById(proyecto_id) {
                try {
                    const response = await axios.get(`api/get-proyecto-by-id/${proyecto_id}`);
                    this.proyecto = response.data;
                    this.itemsEstado = response.data.estados_proyecto;
                    this.pushItemsEstadoId(this.itemsEstado);
                } catch (error) {
                    console.log(error);
                }
            },

            pushItemsEstadoId(items) {
                const itemsPreview = items;
                Object.keys(itemsPreview).forEach((i) => {
                    this.id_items_proyecto.push(itemsPreview[i].id);
                });
            },

            /*updateFileList(items) {
                this.files = items
            },*/

            async saveProyecto() {
                try {
                    var formData = new FormData()
                    formData.append('proyecto', JSON.stringify(this.proyecto))
                    formData.append('itemsEstado', JSON.stringify(this.itemsEstado))
                    formData.append('idItemsEstado', JSON.stringify(this.id_items_proyecto))

                    let archivos = this.proyecto.archivos.filter(archivo => !archivo.id)

                    archivos.forEach((item, i) => formData.append('itemsFiles[' + i + ']', item.file))

                    let res = await axios.post("api/save-proyecto", formData)

                    this.proyecto = res.data.proyecto

                    this.$toast.sucs("Proyecto guardado con exito");
                } catch (error) {
                    console.log(error.response.data.errors);
                    this.$toast.error("Error guardando Proyecto, Compruebe todos los campos requeridos");
                }
            },

            volver() {
                this.$router.push(`/lista-proyectos`);
            },

            getUsuarios() {
                axios.get(`api/get-usuarios`).then(
                    (res) => {
                        this.usuarios = res.data.users;
                    },
                    (err) => {
                        this.$toast.error("Error consultando clientes");
                    }
                );
            },

            setFiles(files) {
                const filesPreview = files;
                Object.keys(filesPreview).forEach((i) => {
                    const file = filesPreview[i];
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview.push(reader.result);
                    };
                    this.imagePreview = [];
                    reader.readAsDataURL(file);
                });
                if (files !== undefined) {
                    this.files = files;
                    this.disableUploadButtonImage = false;
                }
            },

            getMethodsForm() {
                axios.get(`api/get-methods-form`).then(
                    (res) => {
                        this.provincias = res.data.provincias;
                    },
                    (res) => {
                        this.$toast.error("Error consultando Usuario");
                    }
                );
            },

            getUsuarioById(usuario_id) {
                axios.get(`api/get-usuario-by-id/${usuario_id}`).then(
                    (res) => {
                        this.usuario = res.data.user;
                    },
                    (res) => {
                        this.$toast.error("Error consultando Usuario");
                    }
                );
            },


            addStatus() {
                if (this.descripcion != "") {
                    const estado = {
                        descripcion: this.descripcion,
                        fecha: this.fecha,
                        finalizado: this.finalizado,
                        id: this.estado_proyecto_id != "" ? this.estado_proyecto_id : Date.now() + "a",
                    }
                    //console.log(estado)
                    let idstring = estado.id.toString()
                    if (idstring.includes("a")) {
                        this.itemsEstado.push(estado);
                        this.id_items_proyecto.push(estado.id);
                    } else {
                        const search = this.itemsEstado.findIndex((val) => (val.id = estado.id));
                        this.itemsEstado[search].descripcion = estado.descripcion
                        this.itemsEstado[search].fecha = estado.fecha
                        this.itemsEstado[search].finalizado = estado.finalizado
                    }
                    /*this.itemsEstado.push(estado);
                    this.id_items_proyecto.push(estado.id);*/
                }
                this.estado_proyecto_id = "";
                this.descripcion = "";
                this.finalizado = false;
                this.fecha = new Date().toISOString().substr(0, 10);
            },

            /*HandlerAddStatus() {
              if (
                this.description != ""
              ) {
                const itemE = {
                  descripcion: this.description,
                  fecha: this.fecha,
                  finalizado: this.finalizado,
                  id: Date.now() + "a",
                };
                this.itemsEstado.push(itemE);
                this.id_items_proyecto.push(itemE.id);
                this.id = Date.now() + "a";
                this.description = "";
                this.finalizado = false;
                this.fecha = new Date().toISOString().substr(0, 10);
              }
            },*/

            changeFinalizado(itemE) {
                axios.get(`api/update-project-status/${itemE.id}`).then(res => {
                    itemE.finalizado = !itemE.finalizado
                    this.$toast.sucs('Estado actualizado')
                }, err => {
                    this.$toast.error('Debe guardar/actualizar antes de poder cambiar el estado')
                })
            },

            deleteItem(item) {
                let idstring = item.id.toString()
                if (idstring.includes("a")) {
                    const search = this.itemsEstado.findIndex((val) => (val.id === idstring));
                    if (search > -1) {
                        this.itemsEstado.splice(search, 1);
                        this.id_items_proyecto.splice(search, 1);
                    }
                } else {
                    const search = this.itemsEstado.findIndex((val) => (val.id == item.id));
                    if (search > -1) {
                        this.itemsEstado.splice(search, 1);
                        this.id_items_proyecto.splice(search, 1);
                    }
                    axios.get(`api/delete-proyecto-estado/${item.id}`).then(res => {
                        this.$toast.sucs('Estado existente borrado exitosamente')
                    }, err => {
                        this.$toast.error('Error al borrar estado')
                    })
                }
            },

            editItem(item) {
                this.estado_proyecto_id = item.id
                this.descripcion = item.descripcion
                this.fecha = item.fecha.substr(0, 10)
                this.finalizado = item.finalizado
                /*const search = this.itemsEstado.filter((val) => (val.id = item.id));
                console.log("***", search);
                if (search.length > 0) {
                    this.description = search[0].descripcion
                    this.fecha = search[0].fecha.substr(0, 10)
                    this.finalizado = search[0].finalizado
                    this.estado_proyecto_id = search[0].id
                }*/
                //this.deleteItem(item);
            },
        },
        computed: {
            isloading() {
                return this.$store.getters.getloading;
            },
            errors() {
                return this.$store.getters.geterrors;
            },
        },
    };
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