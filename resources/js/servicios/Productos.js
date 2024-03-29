import axios from 'axios';

const PREFIJO="/app/productos";

class Productos {

    index(id) {
        return axios.get(`${PREFIJO}/${id}`);
    }

    store(formulario,lista) {
        formulario.lista=lista.id_li;
        let form_data = new FormData();
        let data = {};
        const producto = 'imagen';
        for ( let key in formulario ) {
            if(key===producto && formulario[key]){
                form_data.append(key, formulario[key]);
            }else if (key==='url' && formulario[key]){
                data[key] = encodeURI(formulario[key]);
            }else{
                data[key] = (formulario[key]);
            }
        }
        return axios.post(`${PREFIJO}`,form_data,{
            params:data,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    update(formulario,lista) {
        formulario.lista=lista.id_li;
        return axios.patch(`${PREFIJO}/${formulario.id}`,formulario);
    }

    updateEstado(producto) {
        const formulario = {
            cambioEstado: true,
            titulo : producto.detalle_pr,
            fragil : producto.fragil_pr,
            peso : producto.libras_pr,
            precio : producto.pvp_pr,
            url : producto.url_pr,
            codigoe : producto.erastreo_pr,
            codigo : producto.rastreo_pr,
            estado : producto.estado_pr,
            id : producto.id_pr,
            cantidad : producto.cant_pr,
        }
        return axios.patch(`${PREFIJO}/${formulario.id}`,formulario);
    }

    descargar(formulario){
        return axios.get(`/app/listaPrecios`,{ params: formulario })
    }

    delete(producto) {
        return axios.delete(`${PREFIJO}/${producto.id_pr}`);
    }
}
export default new Productos()
