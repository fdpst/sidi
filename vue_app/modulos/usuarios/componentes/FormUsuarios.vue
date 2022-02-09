<template>
    <v-container>
        <loader v-if="isloading"></loader>
        <v-form>
            <v-card class="my-0 py-0">
                <v-toolbar flat color="#1d2735" dark>
                    <v-toolbar-title>Guardar / Editar Usuario</v-toolbar-title>
                </v-toolbar>
                <loader v-if="isloading"></loader>
                
                <v-tabs horizontal>
                    <v-tab>
                        <v-icon left>mdi-account-supervisor-circle</v-icon>Datos del Usuario
                    </v-tab>
                    <v-tab-item class="pa-2 my-0">
                        <v-card flat>
                            <v-row dense>
                                <v-col cols="12" md="4">
                                    <v-text-field dense outlined :error-messages="errors.errors['nombre'] ? errors.errors['nombre'][0] : null"
                                        v-model="usuario.nombre" label="Nombre" required>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field dense outlined :error-messages="errors.errors['email'] ? errors.errors['email'][0] : null" v-model="usuario.email" label="Email" required></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select dense outlined :error-messages="errors.errors['role'] ? errors.errors['role'][0] : null" :items="roles"
                                        item-value="id" item-text="role" label="Seleccione un Perfil" v-model="usuario.role">
                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-card>
            <v-row class="mt-3"> <!-- Botones Navegacion -->
                <v-col cols="12">
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn fab @click="volver" :loading="isloading" :disabled="isloading" color="blue" class="mx-2" v-bind="attrs" v-on="on">
                                <v-icon class="white--text">mdi-arrow-left-bold-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Volver</span>
                    </v-tooltip>
                    <v-tooltip top v-if="this.editMode == true">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn fab @click="updateUsuario" :loading="isloading" :disabled="isloading" color="success" class="mx-2" v-bind="attrs" v-on="on">
                                <v-icon class="white--text">mdi-account-convert</v-icon>
                            </v-btn>
                        </template>
                        <span>Actualiza Usuario</span>
                    </v-tooltip>
                    <v-tooltip top v-if="this.editMode == false">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn fab @click="saveUsuario" :loading="isloading" :disabled="isloading" color="success" class="mx-2" v-bind="attrs" v-on="on">
                                <v-icon class="white--text">mdi-content-save-all</v-icon>
                            </v-btn>
                        </template>
                        <span>Guarda Usuario</span>
                    </v-tooltip>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
    import FileInput from '../../../global_components/FileInput.vue'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import VFileComponent from '../../../global_components/VFileComponent.vue';
     export default {
        components: { 'file-input' : FileInput, VFileComponent },
        data() {
            return {
                menu: false,
                menu: '',
                editMode: false,
                usuario: {
                    id: '',
                    nombre: '',
                    email: '',
                    role: 2,
                },
                rules: {
                    number_rule: value => /^\d+$/.test(value) || 'Campo numérico'
                },
                roles:[
                    {
                        id: 1,
                        role: 'Administrador',
                    },
                    {
                        id: 3,
                        role: 'Empleado',
                    }
                ],
                editor: ClassicEditor,
                editorData: '<p>Escriba Aqui Observaciones o contenido.</p>',
                editorConfig: {
                    toolbar: {
                        items: ['heading','bold','italic','bulletedList','numberedList','link','inserttable'],
                    },
                },
                provincias:[],
                files: [],
                imagePreview: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                user_id: localStorage.getItem('user_id'),
            }
        },

        created() {
            if (this.$route.query.id) {
                this.editMode = true
                this.getUsuarioById(this.$route.query.id)
            }
        },
        methods: {
            volver(){
                this.$router.push(`/lista-usuario`)
            },
            updateUsuario() {
                let formDataUpdate = new FormData()
                for (let fileSave of this.files) {
                    formDataUpdate.append('imagen[]', fileSave, fileSave.name)
                }
                formDataUpdate.append('usuario', JSON.stringify(this.usuario))
                axios.post('api/update-usuario/'+ this.usuario.id, formDataUpdate).then(res => {
                    console.log(res)
                    this.$toast.sucs('Usuario actualizado con éxito')
                }, res => {
                    console.log(res)
                    this.$toast.error('Error guardando usuario')
                })
            },
            saveUsuario() {
                let formDataSave = new FormData()
                for (let fileSave of this.files) {
                    formDataSave.append('imagen[]', fileSave, fileSave.name)
                }
                formDataSave.append('usuario', JSON.stringify(this.usuario))
                axios.post('api/save-usuario', formDataSave).then(res => {
                    this.$toast.sucs('Usuario guardado con éxito')
                    // this.$router.push('/lista-usuario')
                }, res => {
                    this.$toast.error('Error guardando usuario')
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
                this.usuario.avatar = base_64
            },

            defaultAvatar() {
                this.usuario.avatar = null;
            }
        },
        computed: {
            isloading() {
                return this.$store.getters.getloading
            },
            errors() {
                return this.$store.getters.geterrors
            },
            uri(){
                return window.location.origin
            },
            idUser(){
                return localStorage.user_id
            }
        }
    };
</script>
<style>
    .inputFile{
        padding: 100%;
        position: absolute;
        opacity: 0.1;
    }
    .inputFile[type]{
        cursor: copy;
    }
</style>
<style>
    /* Oculta el file imput debajo de la foto */
    .v-input__slot
    {
        background-color: transparent !important;
    }
    .v-file-input__text
    {
        color: transparent !important;
    }
    .mdi-close::before
    {
        color: transparent !important;
    }
</style>
