<template>
    <ValidationObserver v-slot="{ handleSubmit }" ref="formL">
        <form @submit.prevent="handleSubmit(validar)">
            <div class="form-row align-items-center">
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="título"
                    :rules="{required:true}"
                    tag="div"
                    class="col-auto my-1"
                >
                    <input
                        type="text"
                        class="form-control"
                        v-model="formulario.titulo"
                        :disabled="cargando"
                        placeholder="título de lista"
                    >
                    <small class="text-muted">{{errors[0]}}</small>
                </ValidationProvider>
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="precio por libra"
                    :rules="{required:false,decimal:2,min_value:0,max_value:999}"
                    tag="div"
                    class="col-auto my-1"
                >
                    <input
                        type="number"
                        class="form-control"
                        v-model="formulario.precio"
                        :disabled="cargando"
                        placeholder="Precio por libra"
                        min="0"
                        step="any"
                    >
                    <small class="text-muted">{{errors[0]}}</small>
                </ValidationProvider>

                <div class="col-auto mb-lg-2 mb-5">
                    <button :disabled="cargando" type="submit" class="btn btn-success">
                        <i class="fa fa-cog fa-spin fa-fw" v-if="cargando"></i>
                        <i class="fa fa-save" v-else></i>
                    </button>
                </div>
            </div>
        </form>
    </ValidationObserver>
</template>

<script>
import {Listas} from "../servicios";

export default {
    name: "NuevaLista",
    data: () => ({
        cargando: false,
        formulario: {
            titulo: null,
            precio: null
        }
    }),
    methods: {
        validar: function () {
            this.cargando = true;
            this.enviar();
        },
        limpiar: function () {
            this.formulario = {
                titulo: null,
                precio: null
            };
            this.$refs.formL.reset()
        },
        enviar: function () {
            Listas.nuevo(this.formulario).then(() => {
                this.$emit('actualizar');
                this.cargando = false;
                this.limpiar();
            }).catch(()=>{
                this.cargando = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
