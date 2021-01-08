<template>
    <b-list-group-item
        class="flex-column align-items-start"
        :variant="item.librasR_pr && 'success'"
    >
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="mb-1">{{ item.detalle_pr }}</h5>
                <h6 v-if="item.fragil_pr">
                    <b-badge pill variant="danger">Frágil</b-badge>
                </h6>
            </div>
            <small>{{ item.updated_at | fecha }}</small>
        </div>
        <div class="mb-1 row p-0">
            <div class="col-12 col-lg p-1" v-if="item.erastreo_pr || item.rastreo_pr || item.cant_pr">
                <b-badge pill variant="primary" v-if="item.erastreo_pr">Rastreo</b-badge>
                <p v-if="item.erastreo_pr"><b>Empresa: </b>{{ item.erastreo_pr }}</p>
                <p v-if="item.rastreo_pr"><b>TrackID: </b><span class="text-info">{{ item.rastreo_pr }}</span></p>
                <p v-if="item.cant_pr"><b>Cantidad: </b>{{ item.cant_pr }}</p>
            </div>
            <div class="col-12 col-lg text-lg-center p-1">
                <b-badge :href="item.url_pr" variant="info" target="_blank">{{ dominio }}</b-badge>
                <h4 class="m-0" v-if="item.pvpxc_pr">
                    <span class="badge badge-pill badge-light precio">{{ item.pvpxc_pr | moneda }}</span>
                </h4>
                <div v-if="item.libras_pr">
                    <b-badge variant="success">Peso web</b-badge>
                    {{ item.estxc_pr }} lb
                </div>
                <div v-if="item.librasR_pr">
                    <b-badge variant="danger">Peso real</b-badge>
                    {{ item.realxc_pr }} lb
                </div>
                <div v-if="item.realxc_pr && lista.lista.precioxl_li">
                    <b-badge variant="warning">Costo de envio</b-badge>
                    <span class="precio">{{ (item.realxc_pr * lista.lista.precioxl_li) | moneda }}</span>
                </div>
                <div v-else-if="item.estxc_pr && lista.lista.precioxl_li">
                    <b-badge variant="warning">Costo de envio</b-badge>
                    <span class="precio">{{ (item.estxc_pr * lista.lista.precioxl_li) | moneda }}</span>
                </div>
            </div>
            <div class="col-12 col-lg text-center p-0" v-if="item.img_pr">
                <img :src="item.img_pr" alt="asd" class="img-todo">
            </div>
        </div>
        <small class="d-flex justify-content-between">
            <div>
                <span>{{ lista.id_us === item.id_us ? 'Mi orden' : 'Orden de ' + item.dueno.name }}</span>
                <b-badge variant="dark">{{ item.estado_pr }}</b-badge>
            </div>
            <template v-if="puedeEditar">
                <button class="btn btn-sm btn-danger" type="button" @click="eliminar" v-if="item.id_us===lista.id_us">
                    Borrar
                </button>
                <button class="btn btn-sm btn-success" type="button" @click="seleccionar" v-if="item.id_us===lista.id_us">
                    Editar
                </button>
            </template>
            <template v-if="puedeRecibir">
                <button class="btn btn-sm btn-info" type="button" @click="recibido" v-if="item.id_us===lista.id_us">
                    Recibido
                </button>
            </template>

            <h3 v-if="precioProducto">
                <span class="precio">{{ precioProducto  | moneda }}</span>
            </h3>
        </small>
    </b-list-group-item>
</template>

<script>
import moment from "moment";
import {enumeraciones} from "../../constantes";
import {Productos} from "../../servicios";

export default {
    name: "Producto",
    props: {
        item: {
            type: Object,
            required: true
        },
        lista: {
            type: Object,
            required: true
        }
    },
    computed: {
        dominio() {
            if (this.item.url_pr) {
                const url = new URL(this.item.url_pr);
                return url.host
            } else {
                return null
            }
        },
        precioProducto() {
            try {
                if(this.item.realxc_pr){
                    return this.item.pvpxc_pr + (this.item.realxc_pr * this.lista.lista.precioxl_li)
                }else{
                    return this.item.pvpxc_pr + (this.item.estxc_pr * this.lista.lista.precioxl_li)
                }
            } catch (e) {
                return 0;
            }
        },
        puedeRecibir() {
            const a = enumeraciones.puedeRecibirUser.findIndex(i=>i===this.item.estado_pr);
            return a >= 0;
        },
        puedeEditar() {
            const a = enumeraciones.puedeEditarUser.findIndex(i=>i===this.item.estado_pr);
            return a >= 0;
        },
    },
    filters: {
        fecha(valor) {
            const date = moment(valor);
            return date.fromNow();
        },
        moneda(valor) {
            valor = parseFloat(valor);
            return valor.toFixed(2);
        }
    },
    methods: {
        seleccionar: function () {
            this.$emit('seleccionado', this.item);
        },
        eliminar: function () {
            this.$emit('eliminar', this.item);
        },
        cambiarEstado: function(){
            return Productos.updateEstado(this.item,this.lista).then((response)=>{
                return response;
            })
        },
        recibido: function() {
            this.$swal({
                title: this.item.detalle_pr,
                html: '<b>¿Recibió este producto?</b>',
                icon: 'question',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: 'Si',
                confirmButtonAriaLabel: 'No',
                cancelButtonText: 'No',
                cancelButtonAriaLabel: 'Si',
                preConfirm: (aux) => {
                    return this.cambiarEstado().then(()=>{
                        this.$emit('actualizar');
                        return aux;
                    });
                }
            });
        }
    }
}
</script>

<style scoped>
p, h3 {
    margin: 0;
}

h6, h5 {
    display: inline;
}
</style>
