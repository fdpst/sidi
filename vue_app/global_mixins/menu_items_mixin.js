export const menu_items_mixin = {
    data() {
        return {
            items: [
                /*{
                    path: `/`,
                    icon: 'mdi-home-outline',
                    text: 'Inicio',
                    user: [1, 3]
                },*/
                //
                { // OJO NO BORRAR
                    path: '/lista-usuario',
                    icon: 'mdi-account-supervisor-circle',
                    text: 'Usuarios',
                    user: [1]
                },
                /*{
                    path: `/lista-clientes`,
                    icon: 'mdi-account-tie',
                    text: 'Clientes',
                    user: [1]
                },
                {
                    path: `/lista-fichajes`,
                    icon: 'mdi-card-account-details-outline',
                    text: 'Fichajes',
                    user: [1,3]
                },*/
                {
                    path: `/tareas`,
                    icon: 'mdi-file-tree',
                    text: 'Tareas',
                    user: [1,3]
                },
                /*{
                    path: '/estadisticas',
                    icon: 'mdi-chart-line-stacked',
                    text: 'Estadísticas',
                    user: [1],
                },*/
            ]
        }
    },

    computed: {
        user() {
            return this.$store.getters.getuser
        },
        computedheaders: function() {
            if (this.user.role != 0) {
                return this.items.filter(x => {
                    return x.user.some(userole => userole == this.user.role)
                })
            }
        }
    }
}