import FormPotenciales from '../componentes/FormPotenciales'
import ListaPotenciales from '../componentes/ListaPotenciales'

const routes = [
   ...route('/guardar-potencial', FormPotenciales, {
      Auth: true,      
   }),
   ...route(`/lista-potenciales`, ListaPotenciales, {
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