import ListaFichajes from '../componentes/ListaFichajes'

const routes = [
   ...route(`/lista-fichajes`, ListaFichajes, {
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