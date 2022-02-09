<template>
   
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