<template>
    <div class="container-fluid pl-4 pr-4 m-0">
        <lista-details
            :lista="lista"
            @actualizar="consulta"
        />
        <div class="d-flex justify-content-center">
            <div class="col-lg-6 col-12 p-0">
                <div class="card">
                    <div class="card-header">
                        Productos
                        <button class="btn btn-success btn-sm" type="button" v-if="lista.add_se || lista.creador_se" v-on:click="nuevo">
                            Agregar
                        </button>
                        <button class="btn btn-info btn-sm" type="button" v-if="lista.add_se || lista.creador_se" v-on:click="reporte">
                            Generar Reporte
                        </button>
                    </div>
                    <div class="card-body">
                        <b-list-group>
                            <Producto
                                v-for="(item,index) in lista.productos_lista"
                                :key="index"
                                :item="item"
                                :lista="lista"
                                @seleccionado="seleccionar"
                                @eliminar="confirmarEliminacion"
                                @actualizar="consulta"
                            />
                        </b-list-group>
                    </div>
                </div>
            </div>
        </div>
        <modal-producto
            v-model="modal"
            :producto="producto"
            :lista="lista"
            v-on:actualizar="consulta"
        />
    </div>
</template>

<script>
import {Productos} from "../servicios";
import moment from "moment";

export default {
    name: "AllProductos",
    data: () => ({
        lista: {},
        modal: false,
        producto: null,
    }),
    props: {
        id: {
            required: true,
        }
    },
    methods: {
        consulta() {
            Productos.index(this.id).then(response => {
                this.lista = response.data;
            })
        },
        nuevo() {
            this.modal=true;
            this.producto=null;
        },
        seleccionar(item) {
            this.modal=true;
            this.producto=item;
        },
        confirmarEliminacion(item) {
            this.$swal({
                title: item.detalle_pr,
                html: '<b>¿Deseas eliminarlo?</b>',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: 'Si',
                confirmButtonAriaLabel: 'No',
                cancelButtonText: 'No',
                cancelButtonAriaLabel: 'Si',
                preConfirm: (aux) => {
                    return this.eliminar(item)
                }
            });
        },
        reporte(){
            this.$swal({
                title: 'Ganacia',
                html: 'Ingresa el valor de ganacia siendo 1 -> 0%',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                inputValidator: (value) => {
                    if (!value || value<=1 || value>=100) {
                        return 'Tienes que ingresar un valor mayor a 1'
                    }
                },
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: 'Generar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                preConfirm: (texto) => {
                    const formulario= {
                        id: this.id,
                        ganacia: texto
                    }
                    return Productos.descargar(formulario).then((res)=>{
                        window.open(res.request.responseURL, "_blank");
                    })
                }
            })
        },
        eliminar(producto){
            return Productos.delete(producto).then(()=> {
                this.$swal({
                    html: `<b>${producto.detalle_pr}</b>`+' se ha eliminado con éxito',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                })
                this.consulta();
                return producto;
            });
        }
    },
    mounted() {
        this.consulta();
    }
}
</script>

<style scoped>

</style>
