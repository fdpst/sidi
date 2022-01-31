import FormProyecto from '../componentes/FormProyecto'
import ListaProyectos from '../componentes/ListaProyectos'
import ListaClientes from '../componentes/ListaClientes'
import RegistrarPresupuesto from '../componentes/RegistrarPresupuesto'

const routes = [
   ...route('/guardar-proyecto', FormProyecto, {
      Auth: true,
   }),
   ...route(`/lista-proyectos`, ListaProyectos, {
      Auth: true,
   }),
   ...route(`/lista-clientes`, ListaClientes, {
      Auth: true,
   }),
   ...route(`/registrar-presupuesto`, RegistrarPresupuesto, {
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
