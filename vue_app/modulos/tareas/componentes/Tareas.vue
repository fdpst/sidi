<template>
    <v-card class="pa-3 ma-3">
        <v-toolbar flat color="#1d2735" dark>
            <v-icon class="white--text" style="font-size: 45px;">mdi-file-tree</v-icon><pre><v-toolbar-title><h2 style="margin-left:20px;"> Tareas</h2></v-toolbar-title></pre>
        </v-toolbar>
        <loader v-if="isloading"></loader>
        <v-tabs horizontal>
            <v-tab> <v-icon left>mdi-book-variant-multiple</v-icon>Añadir Tareas </v-tab>
            <v-tab  v-if="rol == 1"> <v-icon left>mdi-account</v-icon>Buscar Tareas </v-tab>
            <v-tab-item class="pa-3 ma-1">
                <v-card flat>
                    <v-row style="margin-top:10px;">
            <v-col cols="12" md="2">
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="filtros.fecha" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="formatDate" label="Fecha"
                            append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                        </v-text-field>
                    </template>
                    <v-date-picker first-day-of-week="1" v-model="filtros.fecha" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="red" @click="menu = false"><strong>Cancelar</strong></v-btn>
                        <v-btn text color="success" @click="$refs.menu.save(filtros.fecha)"><strong>OK</strong></v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="3">
                <v-autocomplete dense @click:append-outer="openModalProyecto()" append-outer-icon="mdi-plus-circle" outlined v-model="tarea.id_proyecto" :items="proyectos" item-text="nombre" item-value="id" label="Proyectos">
                </v-autocomplete>
            </v-col>

            <v-col cols="12" md="3">
                <v-autocomplete dense @click:append-outer="openModalTipoTarea()" append-outer-icon="mdi-plus-circle" outlined v-model="tarea.id_tipo_tarea" :items="tiposTarea" item-text="nombre" item-value="id" label="Tipo de tarea">
                </v-autocomplete>
            </v-col>
            
            <v-col cols="12" md="2">
                <v-text-field dense outlined type="number" v-model="tarea.tiempo" label="Minutos"> </v-text-field>
            </v-col>
            
        </v-row> 
        <v-row>
            <v-col cols="12" md="10">
                <v-textarea dense outlined height="60px"
                    v-model="tarea.descripcion" label="Descripción">
                </v-textarea>
            </v-col>
            <v-col cols="12" md="1">
                <v-tooltip right>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click= "addTarea" :loading="isloading" :disabled="isloading" color="orange darken-1" class="mt-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-playlist-plus</v-icon>
                        </v-btn>
                    </template>
                    <span>Añadir</span>
                </v-tooltip>
            </v-col>
        </v-row>      
        <v-row style="justify-content:end;">
            <v-col cols="12" md="2">
                <v-text-field readonly disabled v-model="total" label="Total minutos dedicados"></v-text-field>
            </v-col>
        </v-row>  
        <v-data-table style="margin-top:20px;" dense :headers="headers" :items="tareas" :search="search" item-key="id" class="elevation-1" :sort-by="['nombre']" :sort-desc="[false]">
            <template v-slot:item.action="{ item }">
                <v-icon @click="editTarea(item)"small class="mr-2" color="blue">mdi-pencil</v-icon>
                <v-icon @click="deleteTarea(item)" small class="mr-2" color="red">mdi-trash-can</v-icon>
            </template>
        </v-data-table>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card>
                    <v-row style="margin-bottom:15px; margin-top:15px;">
            <v-col cols="12" md="3">
                <v-select dense outlined v-model="filtros2.usuario" :items="usuarios" item-text="nombre" item-value="id" label="Usuarios">
                </v-select>
            </v-col>
            <v-col cols="12" md="3">
                <v-autocomplete dense outlined v-model="filtros2.proyecto" :items="proyectos" item-text="nombre" item-value="id" label="Proyectos">
                </v-autocomplete>
            </v-col>
            <v-col cols="12" md="2">
                <v-menu ref="menu" v-model="menu3" :close-on-content-click="false" :return-value.sync="filtros2.fecha_inicio" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="formatDateFechaIni" label="Fecha Inicio"
                            append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                        </v-text-field>
                    </template>
                    <v-date-picker first-day-of-week="1" v-model="filtros2.fecha_inicio" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="red" @click="menu = false"><strong>Cancelar</strong></v-btn>
                        <v-btn text color="success" @click="$refs.menu.save(filtros2.fecha_inicio)"><strong>OK</strong></v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="2">
                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="filtros2.fecha_fin" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="formatDateFechaFin" label="Fecha Fin"
                            append-icon="mdi-calendar" v-bind="attrs" v-on="on">
                        </v-text-field>
                    </template>
                    <v-date-picker first-day-of-week="1" v-model="filtros2.fecha_fin" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="red" @click="menu2 = false"><strong>Cancelar</strong></v-btn>
                        <v-btn text color="success" @click="$refs.menu2.save(filtros2.fecha_fin)"><strong>OK</strong></v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="1">
                <v-tooltip right>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click= "buscarTareas" :loading="isloading" :disabled="isloading" color="orange darken-1" class="mt-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-filter</v-icon>
                        </v-btn>
                    </template>
                    <span>Filtrar</span>
                </v-tooltip>
            </v-col>
             <v-col cols="12" md="1">
                <v-tooltip right>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn fab @click= "retearCampos" :loading="isloading" :disabled="isloading" color="orange darken-1" class="mt-2" v-bind="attrs" v-on="on">
                            <v-icon class="white--text">mdi-delete-sweep</v-icon>
                        </v-btn>
                    </template>
                    <span>Resetear</span>
                </v-tooltip>
            </v-col>
        </v-row>     
        <v-row style="justify-content:end;">
            <v-col cols="12" md="2">
                <v-text-field readonly disabled v-model="total" label="Total minutos dedicados"></v-text-field>
            </v-col>
        </v-row>  
        <v-data-table dense :headers="headers2" :items="tareas" :search="search" item-key="id" class="elevation-1" :sort-by="['nombre']" :sort-desc="[false]">
        </v-data-table>
                </v-card>
            </v-tab-item>
        </v-tabs>
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5 aviso" style="justify-content: center; background:#1d2735; color:white">
                    Crear Nuevo Proyecto
                </v-card-title>
                <v-card-text style="text-align:center">
                    <v-row style="margin-top:15px;">
                        <v-text-field  dense outlined v-model="proyecto.nombre" label="Nombre"></v-text-field>
                    </v-row>
                </v-card-text>
                <v-card-actions class="pt-3">
                    <v-spacer></v-spacer>
                    <v-btn color="error" large @click="dialog = false;selectedItem={}">Cancelar</v-btn>
                    <v-btn color="success" large @click="saveProyecto()">Guardar</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>  
        <v-dialog v-model="dialogTipoTarea" max-width="500px">
            <v-card>
                <v-card-title class="text-h5 aviso" style="justify-content: center; background:#1d2735; color:white">
                    Crear Nueva Tarea
                </v-card-title>
                <v-card-text style="text-align:center">
                    <v-row style="margin-top:15px;">
                        <v-text-field  dense outlined v-model="tipoTarea.nombre" label="Nombre"></v-text-field>
                    </v-row>
                </v-card-text>
                <v-card-actions class="pt-3">
                    <v-spacer></v-spacer>
                    <v-btn color="error" large @click="dialog = false;selectedItem={}">Cancelar</v-btn>
                    <v-btn color="success" large @click="saveTipoTarea()">Guardar</v-btn>
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
                dialogTipoTarea:false,
                dialog:false,
                menu:false,
                menu2:false,
                menu3:false,
                total: 0,
                editar: false,
                tiempo_tarea_sin_editar: 0,
                tiposTarea:[],
                tarea:{
                    id:'null',
                    id_proyecto:'null',
                    id_tarea: 'null',
                    fecha:'',
                    descripcion:'',
                    tiempo: 0,
                    id_usuario:localStorage.getItem('user_id'),
                    id_tipo_tarea: null
                },
                filtros:{
                    fecha: null,
                    id_usuario: localStorage.getItem('user_id'),
                },
                filtros2:{
                    fecha_inicio: null,
                    fecha_fin: null,
                    usuario: null,
                    proyecto: null
                },
                search: '',  
                headers: [
                    // {text: 'Id',value: 'id',sortable: false},
                    {text: 'Fecha', value: 'fecha',sortable: false},
                    {text: 'Proyecto',value: 'nombre_proyecto',sortable: true},
                    {text: 'Descripcion', value: 'descripcion',sortable: false},
                    {text: 'Tiempo',value: 'tiempo',sortable: false},
                    { text: "Acciones", value: "action", sortable: false },
                ],
                headers2: [
                    // {text: 'Id',value: 'id',sortable: false},
                    {text: 'Fecha', value: 'fecha',sortable: false},
                    {text: 'Tipo Tarea',value: 'tipo_tarea',sortable: true},
                    {text: 'Empleado',value: 'nombre_usuario',sortable: true},
                    {text: 'Proyecto',value: 'nombre_proyecto',sortable: true},
                    {text: 'Descripcion', value: 'descripcion',sortable: false},
                    {text: 'Tiempo',value: 'tiempo',sortable: false},
                ],
                tareas: [],
                proyectos: [],
                proyecto:{
                    id:null,
                    nombre:""
                },
                tipoTarea:{
                    id:null,
                    nombre:""
                },
                rol: '',
                usuarios:[],
            }
        },
        created() {
            this.rol = localStorage.getItem('role');
            this.getUsuarios();
            this.getProyectos();
            this.getTiposTarea();
            const formatYmd = date => date.toISOString().slice(0, 10);
            this.filtros.fecha = formatYmd(new Date());
            this.inicializarFechas();
            
        },
        watch: {
           'filtros.fecha'(n) {
                this.getTareas()
            }
        },
        methods: {
            inicializarFechas(){
                var date = new Date();
                this.filtros2.fecha_inicio = date.setDate(1);
                var currentMonth = date.getMonth(); 
                var nextMonth = ++currentMonth; 
                var nextMonthFirstDay = new Date(date.getFullYear(), nextMonth, 1); 
                var oneDay = 1000 * 60 * 60 * 24; 
                this.filtros2.fecha_fin = new Date(nextMonthFirstDay - oneDay);
            },
            retearCampos(){
                this.inicializarFechas();
                this.filtros2.usuario= null;
                this.filtros2.proyecto= null; 
            },
            saveTipoTarea(){
                axios.post(`api/save-tipo-tarea`, this.tipoTarea).then(res => {                  
                    this.tiposTarea = res.data;
                    this.$toast.success('Tipo de tarea creada correctamente')
                    this.tipoTarea.id = null;
                    this.tipoTarea.nombre = "";
                    this.dialogTipoTarea = false;
                }, err => {
                    this.$toast.error('Error creando tipo de tarea')
                })
            },
            saveProyecto(){
                axios.post(`api/save-proyecto`, this.proyecto).then(res => {                  
                    this.proyectos = res.data;
                    this.$toast.success('Proyecto creado correctamente')
                    this.proyecto.id = null;
                    this.proyecto.nombre = "";
                    this.dialog = false;
                }, err => {
                    this.$toast.error('Error creando proyecto')
                })
            },
            openModalTipoTarea(){
                this.dialogTipoTarea = true
            },
            openModalProyecto(){
                this.dialog = true
            },
            getTiposTarea(){
                axios.post(`api/get-tipos-tarea`).then(res => {                  
                    this.tiposTarea = res.data;
                }, err => {
                    this.$toast.error('Error consultando Fichajes')
                })
            },
            getUsuarios() {
                axios.post(`api/get-usuarios-empleados`).then(res => {                  
                    this.usuarios = res.data.users.data;
                }, err => {
                    this.$toast.error('Error consultando Fichajes')
                })
            },
            buscarTareas() {
                axios.post(`api/buscar-tareas/`, this.filtros2).then(res => {  
                    console.log(res.data)
                    this.tareas = res.data;
                    this.total = 0
                    this.tareas.forEach(element => {
                        this.total = this.total + element.tiempo
                    });
                }, err => {

                })
            },
            getTareas() {
                axios.post(`api/get-tareas/`, this.filtros).then(res => {                  
                    this.tareas = res.data;
                    this.total = 0
                    this.tareas.forEach(element => {
                        this.total = this.total + element.tiempo
                    });
                }, err => {

                })
            },            
            addTarea(){
                this.tarea.fecha = this.filtros.fecha;

                axios.post(`api/save-tarea`, this.tarea).then(res => {                  
                    this.tareas = res.data;
                    this.$toast.success('Tarea creada correctamente')
                    this.total = 0
                    this.tareas.forEach(element => {
                        this.total = this.total + element.tiempo
                    });
                    this.resetCampos()
                }, err => {
                    this.$toast.error('Error creando tarea')
                })
            },
            editTarea(item){
                this.tarea = item;
            },
            deleteTarea(item){
                
                axios.post(`api/delete-tarea/${item.id}`).then(res => {                  
                    this.$toast.success('Tarea eliminada correctamente')
                    this.getTareas();
                }, err => {
                    this.$toast.error('Error eliminado tarea')
                })
            },
            getProyectos() {
                axios.get(`api/get-all-proyectos/`, this.filtros).then(res => {                  
                    this.proyectos = res.data
                }, err => {
                    this.$toast.error('Error consultando Proyectos')
                })
            },
            resetCampos(){
                this.tarea = {
                    id:'null',
                    id_proyecto:'null',
                    fecha:'',
                    descripcion:'',
                    tiempo:'',
                    id_usuario:localStorage.getItem('user_id')
                }
            }      
        },
        computed: {
            isloading: function() {
                return this.$store.getters.getloading
            },
            formatDate() {
                return this.filtros.fecha
                ? moment(this.filtros.fecha).format("DD-MM-YYYY")
                : "";
            },
            formatDateFechaIni() {
                return this.filtros2.fecha_inicio
                ? moment(this.filtros2.fecha_inicio).format("DD-MM-YYYY")
                : "";
            },
            formatDateFechaFin() {
                return this.filtros2.fecha_fin
                ? moment(this.filtros2.fecha_fin).format("DD-MM-YYYY")
                : "";
            }
            
        }
    }
</script>