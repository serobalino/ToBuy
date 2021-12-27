<template>
    <b-modal :title="producto ? producto.detalle_pr : 'Nuevo producto'" size="lg" hide-footer centered
             v-model="bandera">
        <ValidationObserver v-slot="{ handleSubmit }" ref="formP">
            <form @submit.prevent="handleSubmit(validar)">
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="producto"
                    :rules="{required:true}"
                    tag="div"
                    class="form-group"
                >
                    <label class="true">Detalle de producto</label>
                    <input
                        v-model="formulario.titulo"
                        type="text"
                        class="form-control"
                        :class="classes"
                    >
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="estado"
                    :rules="{required:true}"
                    tag="div"
                    class="form-group"
                >
                    <label class="true">Estado</label>
                    <b-form-select
                        v-model="formulario.estado"
                        :options="estados"
                        :class="classes"
                    />
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="código de rastreo"
                    :rules="{required:!!formulario.codigoe,alpha_dash:true}"
                    tag="div"
                    class="form-group"
                >
                    <label :class="{true:!!formulario.codigoe}">Código de rastreo</label>
                    <input
                        v-model="formulario.codigo"
                        type="text"
                        class="form-control"
                        :class="classes"
                    >
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="empresa currier"
                    :rules="{required:!!formulario.codigo,}"
                    tag="div"
                    class="form-group"
                >
                    <label :class="{true:!!formulario.codigo}">Empresa currier</label>
                    <input
                        v-model="formulario.codigoe"
                        type="text"
                        class="form-control"
                        :class="classes"
                    >
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="url del producto"
                    :rules="{regex:/(http(s)?:\/\/.)(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g}"
                    tag="div"
                    class="form-group"
                >
                    <label>URL del producto</label>
                    <input
                        v-model="formulario.url"
                        type="url"
                        class="form-control"
                        :class="classes"
                    >
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="precio pagado"
                    :rules="{decimal:2,min_value:0.1,max_value:max.moneda}"
                    tag="div"
                    class="form-group"
                >
                    <label>Precio pagado producto unitario</label>
                    <div class="input-group">
                        <input
                            v-model.number="formulario.precio"
                            type="number"
                            class="form-control"
                            :class="classes"
                            min="1"
                            step="any"
                        >
                        <div class="input-group-prepend">
                            <span class="input-group-text precio">{{ precioCantidad | moneda }}</span>
                        </div>
                    </div>
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="peso"
                    :rules="{decimal:1,min_value:0,max_value:max.peso}"
                    tag="div"
                    class="form-group"
                >
                    <label>Peso en libras unitario</label>
                    <b-icon icon="question-circle-fill" id="libras" class="mb-1" aria-label="Help"></b-icon>
                    <b-popover show triggers="hover" target="libras">
                        Ingresa en <a href="https://www.digitaldutch.com/unitconverter/mass" target="_blank">esta</a> página para convertir unidades
                    </b-popover>
                    <div class="input-group">
                        <input
                            v-model.number="formulario.peso"
                            type="number"
                            class="form-control"
                            :class="classes"
                            min="0"
                            step="any"
                        >
                        <div class="input-group-prepend">
                            <span class="input-group-text precio">{{ librasCantidad | moneda }}</span>
                        </div>
                    </div>
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="cantidad"
                    :rules="{integer:true,min_value:1,max_value:max.peso}"
                    tag="div"
                    class="form-group"
                >
                    <label>Cantidad</label>
                    <input
                        v-model.number="formulario.cantidad"
                        type="number"
                        class="form-control"
                        :class="classes"
                    >
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <div class="form-group">
                    <b-form-checkbox
                        v-model="formulario.fragil"
                        name="frágil"
                        :value="1"
                        :unchecked-value="0"
                    >
                        Es frágil
                    </b-form-checkbox>
                </div>
                <ValidationProvider
                    v-if="!producto"
                    v-slot="{ errors, classes }"
                    name="imagen"
                    :rules="{image:true,size:5000}"
                    tag="div"
                    class="form-group"
                >
                    <label>Imágen</label>
                    <b-form-file
                        v-model="formulario.imagen"
                        :state="Boolean(formulario.imagen)"
                        accept="image/*"
                        :class="classes"
                        data-browse="Examinar"
                        placeholder="Elige una imagen para este producto"
                        drop-placeholder="Arrastra una imagen aqui"
                    ></b-form-file>
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>

                <button type="submit" class="btn btn-success" :disabled="cargando">
                    <i class="fa fa-cog fa-spin fa-fw" v-if="cargando"></i>
                    Guardar
                </button>
                <button type="button" class="btn btn-danger" v-on:click="bandera=false">
                    Cancelar
                </button>
            </form>
        </ValidationObserver>
    </b-modal>
</template>

<script>
import {Productos} from "../servicios";

export default {
    name: "ModalProducto",
    data: () => ({
        max:{
            moneda: 9999,
            peso: 999,
        },
        bandera: false,
        cargando: false,
        formulario: {
            titulo: null,
            imagen: null,
            fragil: 0,
            peso: null,
            precio: null,
            url: null,
            codigoe: null,
            codigo: null,
            estado: 'Por comprar',
            cantidad: 1,
        },
        estados: ['Por comprar','Comprado','Enviado en USA','Cancelado']
    }),
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        producto: {
            type: Object
        },
        lista: {
            type: Object
        }
    },
    computed: {
        precioCantidad(){
            const precio    =   this.formulario.precio * 1;
            const cantidad  =   1 * this.formulario.cantidad;
            return (precio * cantidad);
        },
        librasCantidad(){
            const peso    =   this.formulario.peso * 1;
            const cantidad  =   1 * this.formulario.cantidad;
            return (peso * cantidad);
        }
    },
    filters: {
        moneda(valor) {
            valor = parseFloat(valor);
            return valor.toFixed(2);
        }
    },
    watch: {
        producto: function (val) {
            if (val) {
                this.formulario.titulo = val.detalle_pr;
                // this.formulario.imagen = val.;
                this.formulario.fragil = val.fragil_pr;
                this.formulario.peso = val.libras_pr;
                this.formulario.precio = val.pvp_pr;
                this.formulario.url = val.url_pr;
                this.formulario.codigoe = val.erastreo_pr;
                this.formulario.codigo = val.rastreo_pr;
                this.formulario.estado = val.estado_pr;
                this.formulario.id = val.id_pr;
                this.formulario.cantidad = val.cant_pr;
            }else{
                this.limpiar();
            }
        },
        value: function (val) {
            this.bandera = val;
        },
        bandera: function (val) {
            this.$emit('input', val);
        }
    },
    methods: {
        validar: function () {
            this.cargando = true;
            this.enviar();
        },
        errorFn: function() {
            this.cargando = false;
            this.$swal({
                html: `<b>Ha ocurrido un error vuelve a intentar</b>`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })
        },
        async enviar(){
            if(this.producto){
                await Productos.update(this.formulario,this.lista);
            }else{
                await Productos.store(this.formulario,this.lista);
            }
            this.$emit('actualizar');
            this.bandera = false;
            this.limpiar();
            this.cargando = false;
        },
        limpiar: function () {
            this.formulario.titulo = null;
            this.formulario.imagen = null;
            this.formulario.fragil = 0;
            this.formulario.peso = null;
            this.formulario.precio = null;
            this.formulario.url = null;
            this.formulario.codigoe = null;
            this.formulario.codigo = null;
            this.formulario.estado = 'Por comprar';
            this.formulario.id = null;
            this.formulario.cantidad = 1;
        }
    },
    created() {
        this.bandera = this.value;
    }
}
</script>

<style scoped>
label, .invalid-feedback {
    margin: 0;
}
</style>
