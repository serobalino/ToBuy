<template>
    <b-modal title="Agregar un colaborador" size="lg" o-close-on-esc no-close-on-backdrop hide-header-close hide-footer centered v-model="bandera">
        <ValidationObserver v-slot="{ handleSubmit }" ref="formC">
            <form @submit.prevent="handleSubmit(validar)">
                <ValidationProvider
                    v-slot="{ errors, classes }"
                    name="producto"
                    :rules="{required:true,email:true}"
                    tag="div"
                    class="form-group"
                >
                    <label class="true">Email</label>
                    <input
                        v-model="formulario.email"
                        type="email"
                        class="form-control"
                        :class="classes"
                    >
                    <div class="invalid-feedback">{{ errors[0] }}</div>
                </ValidationProvider>
                <div class="form-group">
                    <b-form-checkbox
                        v-model="formulario.agregar"
                        :value="true"
                        :unchecked-value="false"
                        :disabled="formulario.contrabandista"
                    >
                        Puede agregar elementos a la lista
                    </b-form-checkbox>
                </div>
                <div class="form-group">
                    <b-form-checkbox
                        v-model="formulario.ver"
                        :value="true"
                        :unchecked-value="false"
                        :disabled="formulario.contrabandista"
                    >
                        Puede ver los productos de los demás colaboradores
                    </b-form-checkbox>
                </div>
                <div class="form-group">
                    <b-form-checkbox
                        v-model="formulario.importador"
                        :value="true"
                        :unchecked-value="false"
                    >
                        Es importador
                    </b-form-checkbox>
                </div>
                <button type="submit" class="btn btn-success" :disabled="cargando">
                    <i class="fa fa-cog fa-spin fa-fw" v-if="cargando"></i>
                    Guardar
                </button>
                <button type="button" class="btn btn-danger" v-on:click="bandera=false" :disabled="cargando">
                    Cancelar
                </button>
            </form>
        </ValidationObserver>
    </b-modal>
</template>

<script>
import Lista from "./chill/Lista";
import {Listas} from "../servicios";

export default {
    name: "ModalColaborador",
    data: () => ({
        bandera: false,
        cargando: false,
        formulario:{
            email:null,
            agregar:true,
            ver:false,
            importador:false,
        }
    }),
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        lista: {
            type: Object
        }
    },
    watch: {
        bandera: function (val) {
            this.$emit('input', val);
        },
        value: function (val) {
            this.bandera = val;
        },
        'formulario.importador': function(val) {
            if(val){
                this.formulario.agregar = false;
                this.formulario.ver = true;
            }
        }
    },
    methods:{
        validar:function(){
            this.cargando = true;
            this.enviar();
        },
        enviar:function(){
            Listas.update(this.formulario,this.lista).then(()=>{
                this.cargando = false;
                this.bandera=false;
                this.$emit('actualizar');
            }).catch((e)=>{
                let texto=""
                for (let key in e.response.data.errors) {
                    e.response.data.errors[key].forEach((element) =>
                        texto+=element+'<br>'
                    );
                }
                this.cargando = false;
                this.$swal({
                    title: "Error",
                    icon: 'error',
                    html: texto,
                    confirmButtonText: 'Ok',
                    cancelButtonAriaLabel: 'Ok',
                });
            })
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
