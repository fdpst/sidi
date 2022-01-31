<template>
    <v-card class="pa-3 ma-3">
        <v-toolbar flat color="#1d2735" dark>

            <v-toolbar-title>
                <h2>Lista chats</h2>
            </v-toolbar-title>

        </v-toolbar>

        <loader v-if="isloading"></loader>

        <v-data-table dense :headers="headers" :items="chats" disable-pagination hide-default-footer item-key="id" class="elevation-0 mt-6">

            <template v-slot:item.unrread="{ item }">
                <router-link :to="{ path: `/chat?id=${item.id}` }" class="action-buttons">
                    <v-chip small dark :color="item.unrread > 0 ? 'green' : 'red'">{{item.unrread}}</v-chip>
                </router-link>
            </template>

        </v-data-table>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                chats: [],
                headers: [{
                        text: 'id',
                        value: 'id',
                    },
                    {
                        text: 'nombre',
                        value: 'nombre',
                    },
                    {
                        text: 'email',
                        value: 'email',
                    },
                    {
                        text: 'Mensajes',
                        value: 'unrread',
                    },
                ]
            }
        },

        created() {
            this.getChats()
        },

        methods: {
            getChats() {
                axios.get(`api/get-chats`).then(res => {
                    this.chats = res.data
                }, res => {
                    this.$toast.error('Error consultando lista de chats')
                })
            }
        },
        computed: {
            isloading() {
                return this.$store.getters.getloading
            }
        }
    }
</script>