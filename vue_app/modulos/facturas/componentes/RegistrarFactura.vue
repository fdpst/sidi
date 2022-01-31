<template>
  <v-container>
    <loader v-if="isloading"></loader>
    <v-card>
      <v-toolbar flat color="#1d2735" dark>
        <v-toolbar-title>Guardar / Editar Factura</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <div class="font-weight-bold mb-3 black--text">
          DATOS DEL CLIENTE Y FECHA
        </div>
        <v-row>
          <v-col cols="12" md="4">
            <v-autocomplete
              dense
              outlined
              v-model="id_proyecto"
              :items="proyectos"
              item-value="id"
              item-text="usuario.nombre"
              label="Cliente"
            >
            </v-autocomplete>
          </v-col>
          <!-- <v-col cols="12" md="4">
            <v-text-field
              v-model="cliente"
              outlined
              dense
              label="Cliente"
            ></v-text-field>
          </v-col> -->
          <v-col cols="12" md="4">
            <v-menu
              v-model="menu2"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="fecha"
                  outlined
                  dense
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="fecha"
                @input="menu2 = false"
              ></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
        <div class="font-weight-bold mb-3 black--text">
          DATOS DEL SERVICIO A FACTURAR
        </div>
        <v-row>
          <v-col cols="12" md="3">
            <v-text-field
              v-model="description"
              outlined
              dense
              label="Descripcion"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="2">
            <v-text-field
              type="number"
              v-model="quantity"
              outlined
              dense
              label="Cantidad"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="2">
            <v-text-field
              type="number"
              v-model="price"
              outlined
              dense
              label="Precio"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="2">
            <v-text-field
              type="number"
              v-model="imp"
              outlined
              dense
              label="Importe"
            ></v-text-field>
          </v-col>
        </v-row>
        <div class="mb-5">
          <v-btn
            @click="HandlerAdd()"
            color="primary"
            class="white--text"
            rounded
          >
            Agregar servicio
          </v-btn>
        </div>

        <v-data-table
          dense
          :headers="headers"
          :items="items"
          disable-pagination
          hide-default-footer
          class="elevation-1"
        >
          <template v-slot:item.action="{ item }">
            <!-- <v-icon @click="edit(item)" color="blue">mdi-pencil</v-icon> -->
            <v-icon @click="editItem(item)" color="primary">mdi-pencil</v-icon>
            <v-icon @click="deleteItem(item)" color="red">mdi-delete</v-icon>
          </template>
        </v-data-table>

        <div class="font-weight-bold mb-3 black--text my-3">
          DESCUENTOS Y TOTALES
        </div>
        <v-row justify="space-between">
          <v-col cols="12" md="2">
            <v-text-field
              type="numeric"
              v-model="desc"
              outlined
              dense
              label="Descuento %"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <v-card class="px-2 py-2">
              <span class="font-weight-bold">Sub Total: {{ subTotal() }}€</span>
              <br />
              <span class="font-weight-bold">Descuento: {{ descuento() }}€</span>
              <br />
              <span class="font-weight-bold">Iva: {{ HandlerIva() }}€</span>
              <br />
              <span class="font-weight-bold"
                >Total: {{ subTotal() - descuento() + HandlerIva() }}€</span
              >
            </v-card>
          </v-col>
        </v-row>

        <v-row>
          <v-checkbox
            label="incluir IVA 21%"
            color="primary"
            v-model="incl_iva"
            @change="HandlerIva()"
          ></v-checkbox>
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
              @click="$router.push('/lista-facturas')"
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
              @click="guardarFactura"
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
          <span>Guardar Factura</span>
        </v-tooltip>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      menu2: false,
      id_proyecto: "",
      cliente: "",
      fecha: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
        .toISOString()
        .substr(0, 10),
      description: "",
      quantity: 0,
      price: 0,
      imp: 0,
      desc: 0,
      iva: 0,
      headers: [
        { text: "Descripcion", value: "descripcion", sortable: false },
        { text: "Cantidad", value: "cantidad", sortable: false },
        { text: "Precio", value: "precio", sortable: false },
        { text: "Importe", value: "importe", sortable: false },
        { text: "Acciones", value: "action", sortable: false },
      ],
      items: [],
      id: 0,
      total: 0,
      id_items_factura: [],
      incl_iva: false,
      proyectos: [],
    };
  },

  created() {
    if (this.$route.query.id != undefined) {
      this.getFacturaId(this.$route.query.id);
    }

    this.getAllProyectos();
  },

  watch: {
    quantity(n) {
      console.log("Entra en cantidad");
      this.imp = parseFloat(n * this.price).toFixed(2);
    },
    price(n) {
      console.log("Entra en precio");
      this.imp = parseFloat(n * this.quantity).toFixed(2);
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
  methods: {
    async getAllProyectos() {
      try {
        const response = await axios.get(`api/get-all-proyectos`);
        this.proyectos = response.data.filter(
          (index) => index.usuario !== null
        );

        if (this.$route.query.proyecto != undefined) {
          this.id_proyecto = parseInt(this.$route.query.proyecto);
        }
      } catch (error) {
        console.log(error);
      }
    },

    async getFacturaId(id) {
      try {
        const response = await axios.get(`api/show-facturas/${id}`);
        this.items = response.data.items_factura;
        this.id_proyecto = response.data.id_proyecto;
        this.desc = response.data.descuento;
        this.subTotal();
      } catch (error) {
        console.log(error);
      }
    },
    HandlerAdd() {
      if (
        this.description != "" &&
        this.quantity != "" &&
        this.price != "" &&
        this.imp != ""
      ) {
        const item = {
          descripcion: this.description,
          cantidad: this.quantity,
          precio: this.price,
          importe: this.imp,
          id: Date.now() + "a",
        };
        this.items.push(item);
        this.id = Date.now() + "a";
        this.description = "";
        this.quantity = "";
        this.price = "";
        this.imp = "";
        this.subTotal();
      }
    },

    edit(item) {
      this.description = item.description;
      this.quantity = item.quantity;
      this.price = item.price;
      this.imp = item.imp;
    },

    deleteItem(item) {
      const search = this.items.findIndex((val) => (val.id = item.id));
      if (search > -1) {
        this.items.splice(search, 1);
      }

      if (item.id.includes("a") == false) {
        this.id_items_factura.push(item.id);
      }
    },

    editItem(item) {
      const search = this.items.filter((val) => (val.id = item.id));
      console.log("***", search);
      if (search.length > 0) {
        this.description = search[0].descripcion;
        this.quantity = search[0].cantidad;
        this.price = search[0].precio;
        this.imp = search[0].importe;
        this.id = search[0].id;
      }
      this.deleteItem(item);
    },

    subTotal() {
      // console.log('this.items',this.items)
      const total = this.items.reduce((acc, arr) => {
        acc = parseInt(acc) + parseInt(arr.importe);
        return acc;
      }, 0);
      return total;
    },

    descuento() {
        const descuentoNeto = this.subTotal() * this.desc/100;
        return descuentoNeto;
    },

    HandlerIva() {
      if (this.incl_iva == true) {
        const number = this.subTotal() - this.descuento();
        const mult = number * 21/100;
        //console.log(mult);
        this.iva = mult;
        return mult;
      } else {
        this.iva = 0;
        return 0;
      }
    },

    async guardarFactura() {
      try {
        const request = {
          id_proyecto: this.id_proyecto,
          total: this.subTotal() - this.descuento() + this.HandlerIva(),
          descuento: this.desc,
          fecha: this.fecha,
          items_factura: this.items,
          status_iva: this.incl_iva
        };
        // console.log("descuento", request);
        console.log("params", this.$route.query.id);
        if (this.$route.query.id) {
          /**
           * Editar
           */
          await axios.post(
            "api/update-facturas/" + this.$route.query.id,
            request
          );

          /**
           * Eliminar items
           */
          if (this.id_items_factura.length > 0) {
            const request_item = {
              id_items_factura: this.id_items_factura,
            };
            await axios.post("api/delete-items-facturas/", request);
          }

          this.$toast.sucs("Editado con exitos");
        } else {
          /**
           * Registro
           */
          await axios.post("api/store-facturas", request);
          this.$toast.sucs("Registrado con exitos");
        }
        // await axios.post("api/store-facturas", request);
        // this.$toast.sucs("Registrado con exitos");
        this.$router.push("/lista-facturas");
      } catch (error) {
        const error_data = Object.values(error.response.data.error);
        const msg_error = error_data.flat();
        console.log(msg_error);

        let lt = `<ul>`;

        for (const iterator of msg_error) {
          lt = lt + `<li>${iterator}</li>`;
        }

        lt = lt + `</ul>`;
        this.$toast.error("Error guardando la factura" + lt);
      }
    },
  },
};
</script>

<style>
</style>
