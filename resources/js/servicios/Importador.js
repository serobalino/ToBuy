import axios from 'axios';

const PREFIJO="/api/importer";

class Importador {

    lista (id) {
        return axios.get(`${PREFIJO}/listas/${id}`);
    }

    producto (producto,bandera) {
        const formulario = {
            estado:bandera,
            peso:producto.librasR_pr,
            id:producto.id_pr
        };
        return axios.post(`${PREFIJO}/productos`,formulario);
    }

    listaUpdate (formulario) {
        const objecto = {
            precio: formulario.precioxl_li,
            envio: formulario.enviolocal_li,
            lista: formulario.id_li
        }
        return axios.post(`${PREFIJO}/listas`,objecto);
    }


}
export default new Importador()
