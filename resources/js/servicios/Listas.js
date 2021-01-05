import axios from 'axios';

const PREFIJO="/app/listas";

class Listas {

    index () {
        return axios.get(PREFIJO);
    }

    nuevo (formulario) {
        return axios.post(PREFIJO,formulario);
    }

    update(formulario,lista) {
        formulario.lista=lista.id_li;
        return axios.patch(`${PREFIJO}/${lista.id_li}`,formulario);
    }
}
export default new Listas()
