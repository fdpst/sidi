import VueRouter from 'vue-router'
import auth from '../auth/auth.js'

import Inicio from '../base_components/inicio/Inicio.vue'
import Login from '../base_components/login/Login.vue'

/* importar rutas */

import rutas_proveedores from '../modulos/proveedores/rutas/rutas_proveedores'
import rutas_albaranes from '../modulos/albaranes/rutas/rutas_albaranes'
import rutas_recibos from '../modulos/recibos/rutas/rutas_recibos'
import rutas_contabilidad from '../modulos/contabilidad/rutas/rutas_contabilidad'
import rutas_promociones from '../modulos/promociones/rutas/rutas_promociones'
import rutas_estadisticas from '../modulos/estadisticas/rutas/rutas_estadisticas'
import rutas_morosos from '../modulos/morosos/rutas/rutas_morosos'
import rutas_usuarios from '../modulos/usuarios/rutas/rutas_usuarios'
import rutas_reservas from '../modulos/reservas/rutas/rutas_reservas'
import rutas_gestor_documentos from '../modulos/gestor_documental/rutas/rutas_gestor_documentos'
import rutas_datos_de_empresa from '../modulos/datos_de_empresa/rutas/rutas_datos_de_empresa'


import testrutas from '../modulos/testrutas'

import rutas_proyectos from '../modulos/proyectos/rutas/rutas_proyectos'
import rutas_potenciales from '../modulos/potenciales/rutas/rutas_potenciales'
import rutas_passwords from '../modulos/passwords/rutas/rutas_passwords'

import rutas_facturas from '../modulos/facturas/rutas/rutas_facturas'

import rutas_fichajes from '../modulos/fichajes/rutas/rutas_fichajes'

import rutas_tareas from '../modulos/tareas/rutas/rutas_tareas'

import rutas_chat from '../modulos/chat/rutas/rutas_chat'


/* importar rutas */

const base_routes = [
    ...route('/login', Login),
    ...route('/', Inicio, {
        Auth: true
    }),
    ...rutas_proveedores,
    ...rutas_albaranes,
    ...rutas_recibos,
    ...rutas_contabilidad,
    ...rutas_promociones,
    ...rutas_estadisticas,
    ...rutas_morosos,
    ...rutas_usuarios,
    ...rutas_reservas,
    ...rutas_gestor_documentos,
    ...rutas_datos_de_empresa,
    ...testrutas,
    ...rutas_proyectos,
    ...rutas_potenciales,
    ...rutas_passwords,
    ...rutas_facturas,
    ...rutas_fichajes,
    ...rutas_tareas,
    ...rutas_chat
]

const routes = [
    ...base_routes
]

const router = new VueRouter({
    routes
})

function route(path, component = Default, meta = {}) {
    return [{
        path,
        component,
        meta
    }]
}

router.beforeEach((to, from, next) => {
    if (to.meta.Auth) {
        const authUser = localStorage.getItem('role')
        if (!auth.authenticated()) {
            next({
                path: '/login',
                query: {
                    redirect: to.fullPath
                }
            })
        } else if (to.meta.req_user) {
            (authUser == 2) ? next(): next('/404')
        } else if (to.meta.req_admin) {
            (authUser == 1) ? next(): next('/404')
        } else if (to.meta.req_admin_or_user) {
            (authUser == 1 || authUser == 2) ? next(): next('/404')
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router