<template>
   <!--  <v-list dense nav>

        <v-list-item :to="{path: '/'}">
            <v-list-item-icon>
                <v-icon color="black">mdi-home</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
                <v-list-item-title class="black--text">Inicio</v-list-item-title>
            </v-list-item-content>
        </v-list-item>

        <template v-if="$route.path !== '/'">
            <v-list-item v-for="item in computedheaders" :key="item.path" :to="{path: item.path}">
                <v-list-item-icon  v-if="item.text != 'Albaranes'">
                    <v-icon color="black">{{item.icon}}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title v-if="item.text != 'Albaranes'"  v-html="item.text"></v-list-item-title>


                    <v-list-group
                        v-if="item.text == 'Albaranes'" sub-group>
                      <template v-slot:activator>
                        <v-list-item-content>
                          <v-list-item-title>Albaranes</v-list-item-title>
                        </v-list-item-content>
                      </template>
                      <v-list-item :to="'/lista-albaranes-enviados/' + user.id">
                            <v-list-item-title >Enviados</v-list-item-title>

                        <v-list-item-icon>
                        
                        </v-list-item-icon>
                      </v-list-item>
                      <v-list-item :to="'/lista-albaranes-recibidos/' + user.id">
                            <v-list-item-title >Recibidos</v-list-item-title>
    
                        <v-list-item-icon>
                        
                        </v-list-item-icon>
                      </v-list-item>
                </v-list-group>
                </v-list-item-content>

               


            </v-list-item>



        </template>

    </v-list> -->


    <v-list>
      <v-list-group
        v-for="item in computedheaders"
        :key="item.text"
        v-model="item.active"
        :class="item.hijo == true ? '' : 'listIcon'"
      >
        

        <template v-slot:activator >
            <router-link :to="item.path" class="link">
                 <v-list-item-icon >
                     
                        <v-icon color="black">{{item.icon}}</v-icon>
                         
                        </v-list-item-icon>
                    </router-link>
                 
                    <router-link :to="item.path" class="link">
                    <v-list-item-title v-text="item.text" style="z-index:10000!important"></v-list-item-title>
                    </router-link>
                 
             
        </template>

        <v-list-item
          v-for="child in item.children"
          :key="child.text"
          :to="child.path" style="margin-left: 15px"
        >
           <v-list-item-icon >
                     
            <v-icon color="black">{{item.icon}}</v-icon>
            
            </v-list-item-icon>
          
            <v-list-item-title v-text="child.text"></v-list-item-title>
        
        </v-list-item>
      </v-list-group>
    </v-list>
</template>

<script>
    import {
        menu_items_mixin
    } from '../../global_mixins/menu_items_mixin'
    export default {
        mixins: [menu_items_mixin],

        computed:{
             user() {
                  return this.$store.getters.getuser
              },
        }, 
        methods:{
            ir(){
                alert("dd")
            }
        }
    };
</script>




<style media="screen">
    a .v-list-item--active{
     color: white !important;
    }
    .link{
        padding: 10px;
        cursor: pointer;
        text-decoration: none;
        color:  black!important;
        display: inline;

    }
    .listIcon .mdi-chevron-down{
        visibility: hidden!important;
    }
</style>