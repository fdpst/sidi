export const menu_items_mixin = {
    data() {
        return {
            items: [{
                    path: `/chat`,
                    icon: 'mdi-home-outline',
                    text: 'Prueba Chat',
                    user: [2]
                },
                {
                    path: `/lista-chats`,
                    icon: 'mdi-home-outline',
                    text: 'Prueba Lista Chats',
                    user: [1]
                },
                {
                    path: `/`,
                    icon: 'mdi-home-outline',
                    text: 'Inicio',
                    user: [1, 3]
                },
                //
                { // OJO NO BORRAR
                    path: '/lista-usuario',
                    icon: 'mdi-account-supervisor-circle',
                    text: 'Usuarios',
                    user: [1]
                },
                {
                    path: `/lista-reservas`,
                    icon: 'mdi-calendar-clock',
                    text: 'Reserva de Sala',
                    user: [1,3]
                },
                {
                    path: `/lista-clientes`,
                    icon: 'mdi-account-tie',
                    text: 'Clientes',
                    user: [1]
                },
                {
                    path: `/lista-potenciales`,
                    icon: 'mdi-file-powerpoint',
                    text: 'Potenciales',
                    user: [1,3]
                },
                {
                    path: `/lista-facturas`,
                    icon: 'mdi-file-table-outline',
                    text: 'Facturas',
                    user: [1]
                },
                {
                    path: `/lista-passwords`,
                    icon: 'mdi-form-textbox-password',
                    text: 'Contraseñas',
                    user: [1]
                },
                {
                    path: `/lista-fichajes`,
                    icon: 'mdi-card-account-details-outline',
                    text: 'Fichajes',
                    user: [1,3]
                },
                {
                    path: `/tareas`,
                    icon: 'mdi-file-tree',
                    text: 'Tareas',
                    user: [1,3]
                },
                {
                    path: `/lista-promociones`,
                    icon: 'mdi-bullhorn',
                    text: 'Promociones',
                    user: [1, 3]
                },
                {
                    path: `/contabilidad`,
                    icon: 'mdi-calculator-variant',
                    text: 'Contabilidad',
                    user: [1],
                    hijo: true,
                    children: [{
                            path: `/lista-ingresos`,
                            icon: 'mdi-calculator-variant',
                            text: 'Ingresos',
                            user: [1],
                        },
                        {
                            path: `/lista-gastos`,
                            icon: 'mdi-calculator-variant',
                            text: 'Gastos',
                            user: [1],
                        },
                        {
                            path: `/tipos-gasto`,
                            icon: 'mdi-calculator-variant',
                            text: 'Tipos Gastos',
                            user: [1],
                        },
                    ]
                },
                {
                    path: '/estadisticas',
                    icon: 'mdi-chart-line-stacked',
                    text: 'Estadísticas',
                    user: [1],
                },
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