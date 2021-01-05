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
            </div>
            <div class="col-12 col-lg text-lg-right p-1">
                <b-badge :href="value.url_pr" variant="info" target="_blank">{{ dominio }}</b-badge>
                <div v-if="value.libras_pr">
                    <b-badge variant="success">Peso web</b-badge>
                    {{ value.estxc_pr | moneda}} lb
                </div>
                <div v-if="value.libras_pr && lista.precioxl_li">
                    <b-badge variant="success">Costo de envio estimado</b-badge>
                    <span class="precio">{{ (value.cant_pr * value.libras_pr * lista.precioxl_li) | moneda }}</span>
                </div>
                <div v-if="value.librasR_pr">
                    <b-badge variant="danger">Peso real</b-badge>
                    {{ (value.cant_pr * value.librasR_pr ) | moneda }} lb
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
                <form @submit.prevent="handleSubmit(guardar)">
                    <ValidationProvider
                        v-slot="{ errors, classes }"
                        name="peso real"
                        :rules="{required:true,decimal:2}"
                        tag="div"
                        class="input-group input-group-sm pt-2 pr-1 p-lg-0"
                    >
                        <input
                            type="number"
                            class="form-control"
                            :class="classes"
                            v-model.number="value.librasR_pr"
                            placeholder="Peso Real Unitario"
                            :disabled="cargando"
                            min="0"
                            step="any"
                        >
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" :disabled="cargando">
                                <i class="fa fa-cog fa-spin fa-fw" v-if="cargando" />
                                <i class="fa fa-save" v-else />
                            </button>
                        </div>
                    </ValidationProvider>
                </form>
            </ValidationObserver>
            <button class="btn btn-sm btn-outline-info" type="button" @click="recibido">
                <i class="fa fa-truck"></i>
                Recibido en USA
            </button>
        </small>
    </b-list-group-item>
</template>

<script>
import moment from "moment";

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
    data:()=>({
        cargando: false,
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
        costoEnvioLocal() {

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
        guardar: function () {

        },
        recibido: function () {
            //
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
