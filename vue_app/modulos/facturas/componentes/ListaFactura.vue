<template>
  <v-card class="pa-3 ma-3">
    <v-toolbar flat color="#1d2735" dark>
      <v-icon class="white--text" style="font-size: 45px">
        mdi-file-table-outline
      </v-icon>
      <pre><v-toolbar-title><h2>Lista Factura</h2></v-toolbar-title></pre>
    </v-toolbar>
    <loader v-if="isloading"></loader>
    <v-tooltip right>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          fab
          :to="{ path: `/registrr-facturas` }"
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
      <span>Nueva Factura</span>
    </v-tooltip>
    <v-row>
      <v-col cols="12" md="4">
        <v-text-field
          prepend-icon="mdi-account-search"
          v-model="search"
          label="Buscar"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-data-table
      dense
      :headers="headers"
      :items="proyectos"
      :search="search"
      :items-per-page="15"
      item-key="id"
      class="elevation-1"
      :sort-by="['usuario.nombre']"
      :sort-desc="[false]"
    >
      <template v-slot:item.url="{ item }">
        <a :href="item.url" target="_blank">
          <v-icon> mdi-file-pdf-box </v-icon>
        </a>
      </template>

      <template v-slot:item.total="{ item }"> {{ item.total }} € </template>

      <template v-slot:item.action="{ item }">
        <router-link
          :to="{ path: `/registrr-facturas?id=${item.id}` }"
          class="action-buttons"
        >
          <v-icon
            small
            class="mr-2"
            color="#1d2735"
            style="font-size: 25px"
            title="EDITAR"
            >mdi-pencil-outline</v-icon
          >
        </router-link>
      </template>
    </v-data-table>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      search: "",
      proyectos: [],
      headers: [
        { text: "N° Factura", value: "nro_factura", sortable: false },
        { text: "Fecha", value: "fecha", sortable: false },
        { text: "Cliente", value: "proyecto.usuario.nombre", sortable: false },
        { text: "Total", value: "total", sortable: false },
        { text: "PDF", value: "url", sortable: false },
        { text: "Acciones", value: "action", sortable: false },
      ],
    };
  },
  created() {
    this.getAllFacturas();
  },
  methods: {
    getAllFacturas() {
      axios.get(`api/index-facturas`).then(
        (res) => {
          this.proyectos = res.data;
        },
        (res) => {
          this.$toast.error("Error consultando proyectos");
        }
      );
    },

    deleteProyecto(item) {
      axios.get(`api/delete-proyecto/${item.id}`).then(
        (res) => {
          this.proyectos.splice(this.proyectos.indexOf(item), 1);
          this.$toast.sucs("Proyecto eliminado");
        },
        (err) => {
          this.$toast.error("Error eliminando Proyecto");
        }
      );
    },
  },

  computed: {
    isloading: function () {
      return this.$store.getters.getloading;
    },
  },
};
</script>
