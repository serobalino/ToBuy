import axios from 'axios';

const PREFIJO="/app/productos";

class Productos {

    index(id) {
        return axios.get(`${PREFIJO}/${id}`);
    }

    store(formulario,lista) {
        formulario.lista=lista.id_li;
        let form_data = new FormData();
        for ( let key in formulario ) {
            if(formulario[key]!==null){
                form_data.append(key, formulario[key]);
            }
        }
        return axios.post(`${PREFIJO}`,form_data);
    }

    update(formulario,lista) {
        formulario.lista=lista.id_li;
        return axios.patch(`${PREFIJO}/${formulario.id}`,formulario);
    }

    delete(producto) {
        return axios.delete(`${PREFIJO}/${producto.id_pr}`);
    }
}
export default new Productos()
