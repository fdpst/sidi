<template>
  <div class="">
    <v-app-bar elevation="4" app>
      <v-app-bar-nav-icon @click.stop="toggleDrawer"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>

      <div class="text-center" >
        <v-btn @click="fichar" icon style="margin-right=5px;">
            <v-icon large >mdi-card-account-details-outline</v-icon>
        </v-btn>
        <v-menu open-on-hover bottom offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on">
              <v-img class="d-none d-sm-flex" height="30px" width="30px" :src="'logo.png'"></v-img>
            </v-btn>
          </template>
          <v-list>
            <v-list-item class="px-4 mx-4">
              <v-btn @click="doLogout" icon>
                Salir
                <v-icon>mdi-login-variant</v-icon>
              </v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-app-bar>
  </div>
</template>

<script>
import auth from "../../auth/auth.js";
export default {
  methods: {
    toggleDrawer() {
      this.$emit("toggleDrawer");
    },
    doLogout() {
      auth.logout();
    },
    fichar(){
     axios.post(`api/fichar/${localStorage.getItem('user_id')}`).then(res => {
          this.$toast.sucs('Fichaje realizado con exito')
          
      }, res => {
          this.$toast.error('Error realizando fichaje')
      })
    }
  },
};
</script>

<style media="screen">
  button {
    background-color: white !important;
  }
</style>