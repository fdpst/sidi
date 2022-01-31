import ListaFactura from '../componentes/ListaFactura'
import RegistrarFactura from '../componentes/RegistrarFactura'

const routes = [
    ...route(`/lista-facturas`, ListaFactura, {
        Auth: true,
    }),
    ...route(`/registrr-facturas`, RegistrarFactura, {
        Auth: true,
    }),
]

function route(path, component = Default, meta = {}) {
    return [{
        path,
        component,
        meta
    }]
}

export default routes