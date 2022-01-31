import Contabilidad from '../Contabilidad.vue'
import ListaIngresos from '../ingresos/ListaIngresos'
import FormIngreso from '../ingresos/FormIngreso'

import ListaGastos from '../gastos/ListaGastos'
import FormGasto from '../gastos/FormGasto'
import FormUpdateGasto from '../gastos/FormUpdateGasto'

import TiposGasto from '../gastos/TiposGasto'

import Lote from '../Lote.vue'

const routes = [
   ...route(`/contabilidad`, Contabilidad, {
      Auth: true,

   }),
   ...route(`/lista-ingresos`, ListaIngresos, {
      Auth: true,

   }),
   ...route('/guardar-ingreso', FormIngreso, {
      Auth: true,

   }),
   ...route(`/lista-gastos`, ListaGastos, {
      Auth: true,

   }),
   ...route('/guardar-gasto', FormGasto, {
      Auth: true,

   }),
   ...route('/update-gasto/:id', FormUpdateGasto, {
      Auth: true,

   }),
   ...route('/enviar-facturas', Lote, {
      Auth: true,

   }),
   ...route(`/tipos-gasto`, TiposGasto, {
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
