<template>
    <b-list-group-item
        class="flex-column align-value.-start"
        :variant="value.librasR_pr && 'success'"
    >
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="mb-1">{{ value.detalle_pr }}</h5>
                <h6 v-if="value.fragil_pr">
                    <b-badge pill variant="danger">Frágil</b-badge>
                </h6>
            </div>
            <small>{{ value.updated_at | fecha }}</small>
        </div>
        <div class="mb-1 row p-0">
            <div class="col-12 col-lg p-1" v-if="value.erastreo_pr || value.rastreo_pr || value.cant_pr">
                <b-badge pill variant="primary" v-if="value.erastreo_pr">Rastreo</b-badge>
                <p v-if="value.erastreo_pr"><b>Empresa: </b>{{ value.erastreo_pr }}</p>
                <p v-if="value.rastreo_pr"><b>TrackID: </b><span class="text-info">{{ value.rastreo_pr }}</span></p>
                <p v-if="value.cant_pr"><b>Cantidad: </b>{{ value.cant_pr }}</p>
                <div v-if="value.cant_pr>1">
                    <b-badge variant="warning">Peso web c/u</b-badge>
                    {{ value.libras_pr | moneda }} lb
                </div>
            </div>
            <div class="col-12 col-lg text-lg-right p-1">
                <b-badge :href="value.url_pr" variant="info" target="_blank">{{ dominio }}</b-badge>
                <div v-if="value.libras_pr">
                    <b-badge variant="success">Peso web</b-badge>
                    {{ value.estxc_pr | moneda }} lb
                </div>
                <div v-if="value.libras_pr && lista.precioxl_li">
                    <b-badge variant="success">Costo de envio estimado</b-badge>
                    <span class="precio">{{ (value.cant_pr * value.libras_pr * lista.precioxl_li) | moneda }}</span>
                </div>
                <div v-if="value.librasR_pr">
                    <b-badge variant="danger">Peso real</b-badge>
                    {{ (value.cant_pr * value.librasR_pr) | moneda }} lb
                </div>
                <div v-if="value.librasR_pr && lista.precioxl_li">
                    <b-badge variant="danger">Costo de envio real</b-badge>
                    <span class="precio">{{ (value.cant_pr * value.librasR_pr * lista.precioxl_li) | moneda }}</span>
                </div>
            </div>
            <div class="col-12 col-lg text-center p-0" v-if="value.img_pr">
                <img :src="value.img_pr" alt="asd" class="img-todo">
            </div>
        </div>
        <small class="d-flex justify-content-between">
            <ValidationObserver v-slot="{ handleSubmit }" ref="formAct">
                <form @submit.prevent="handleSubmit(guardar(false))">
                    <ValidationProvider
                        v-slot="{ errors, classes }"
                        name="peso real"
                        ref="peso"
                        :rules="{required:true,decimal:2}"
                        tag="div"
                        class="input-group input-group-sm pt-2 pr-1 p-lg-0"
                    >
                        <input
                            type="number"
                            class="form-control"
                            :class="classes"
                            v-model.number="value.librasR_pr"
                            @input="validarNumero"
                            placeholder="Peso Real Unitario"
                            min="0"
                            step="any"
                        >
                    </ValidationProvider>
                </form>
            </ValidationObserver>
            <button class="btn btn-sm btn-outline-info" type="button" @click="recibido" v-if="canTrack">
                <i class="fa fa-cog fa-spin fa-fw" v-if="cargandoBt"></i>
                <i class="fa fa-truck" v-else></i>
                Recibido en USA
            </button>
        </small>
    </b-list-group-item>
</template>

<script>
import moment from "moment";
import _ from 'lodash'
import {enumeraciones} from "../../constantes";
import {Importador} from "../../servicios";

export default {
    name: "ProductoImportador",
    props: {
        value: {
            type: Object,
            required: true
        },
        lista: {
            type: Object,
            required: true
        },
    },
    data: () => ({
        cargando: false,
        cargandoBt: false,
    }),
    computed: {
        dominio() {
            if (this.value.url_pr) {
                const url = new URL(this.value.url_pr);
                return url.host
            } else {
                return null
            }
        },
        canTrack() {
            try {
                return enumeraciones.puedeRecibirUSA.findIndex(i => i === this.value.estado_pr) >= 0 && !this.value.librasR_pr;
            } catch (e) {
                return false;
            }
        }
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
        validarNumero:_.debounce(async function() {
            const validate = await this.$refs.peso.validate();
            if (validate.valid) {
                this.guardar(false)
            }
        },500),
        guardar: function (bandera = false) {
            this.cargandoBt = bandera;
            return Importador.producto(this.value, bandera).then(() => {
                this.value.estado_pr = 'Cambiado';
                this.cargandoBt = false;
            }).catch((error) => {
                this.$refs.formAct.setErrors(error.response.data.errors)
                this.cargandoBt = false;
            })
        },
        recibido: function () {
            this.$swal({
                title: this.value.detalle_pr,
                html: '<b>¿Recibió este producto?</b>',
                icon: 'question',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: 'Si',
                confirmButtonAriaLabel: 'No',
                cancelButtonText: 'No',
                cancelButtonAriaLabel: 'Si',
                preConfirm: (aux) => {
                    return this.guardar(true).then(() => {
                        return aux;
                    })
                }
            });
        }
    }
}
</script>

<style scoped>
p, h3, .invalid-feedback {
    margin: 0;
}

h6, h5 {
    display: inline;
}
</style>
