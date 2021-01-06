<template>
    <div v-if="lista">
        <div class="jumbotron" >
            <h4 class="display-5">{{ lista.titulo_li }}</h4>
            <div class="row">
                <div class="col" v-if="lista.precioxl_li">
                    <b>Costo por libra:</b> <span class="precio">{{ lista.precioxl_li }}</span>
                </div>
                <b-table striped hover :items="items" :fields="columnas">
                    <template #cell(est)="data">
                        <span class="precio">{{ data.value | moneda }}</span>
                    </template>
                    <template #cell(rea)="data">
                        <span class="precio">{{ data.value | moneda }}</span>
                    </template>
                    <template v-slot:custom-foot>
                        <tr>
                            <td class="text-right">
                                <b>Total:</b>
                            </td>
                            <td class="text-right">
                                <span class="precio">{{ suma('est', items) | moneda }}</span>
                            </td>
                            <td class="text-right bg-danger">
                                <span class="precio">{{ suma('rea', items) | moneda }}</span>
                            </td>
                        </tr>
                    </template>
                </b-table>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6 p-3 bg-success">
                    <ValidationObserver v-slot="{ handleSubmit }" ref="formAct">
                        <form @submit.prevent="handleSubmit(formularioLista)">
                            <ValidationProvider
                                v-slot="{ errors, classes }"
                                name="precio x libra"
                                :rules="{required:true,decimal:2,min_value:0,max_value:999}"
                                tag="div"
                                class="form-group"
                            >
                                <label class="true">Precio por libra (USD)</label>
                                <input
                                    type="number"
                                    v-model.number="lista.precioxl_li"
                                    class="form-control"
                                    :class="classes"
                                    placeholder="7.00"
                                    min="0"
                                    :disabled="preciosLd"
                                    step="any">
                                <small class="invalid-feedback">{{ errors[0] }}</small>
                            </ValidationProvider>
                            <ValidationProvider
                                v-slot="{ errors, classes }"
                                name="envío local"
                                :rules="{required:true,decimal:2,min_value:0,max_value:999}"
                                tag="div"
                                class="form-group"
                            >
                                <label class="true">Precio de envío local</label>
                                <input
                                    type="number"
                                    v-model.number="lista.enviolocal_li"
                                    class="form-control"
                                    :class="classes"
                                    placeholder="5.25"
                                    min="0.00"
                                    :disabled="preciosLd"
                                    step="any">
                                <small class="invalid-feedback">{{ errors[0] }}</small>
                            </ValidationProvider>
                            <button type="submit" class="btn btn-primary" :disabled="preciosLd">Actualizar Datos
                            </button>
                        </form>
                    </ValidationObserver>
                </div>
            </div>
            <hr>
            <div class="row p-0">
                <span class="pr-1">Lista de:</span>
                <template v-for="(item,index) in lista.dueno">
                    <span :key="index">{{ item.name }}</span>
                </template>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-lg-6 col-12 p-0">
                <div class="card">
                    <div class="card-header">
                        Productos
                    </div>
                    <div class="card-body">
                        <b-list-group>
                            <template v-for="(item,index) in lista.productos">
                                <ProductoImportador
                                    :key="index"
                                    v-model="lista.productos[index]"
                                    :lista="lista"
                                />
                            </template>
                        </b-list-group>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="text-center">
        <b-spinner style="width: 5rem; height: 5rem;" label="Cargando"></b-spinner>
    </div>
</template>

<script>
import {Importador} from "../../servicios";

export default {
    name: "ListaDetailLimited",
    props: {
        id: {
            type: String,
            require: true
        }
    },
    data: () => ({
        lista: null,
        columnas: [
            {
                key: 'det',
                label: 'Detalle',
                sortable: true,
                // variant: 'danger'
            },
            {
                key: 'est',
                label: 'Estimado',
                class: 'text-right',
                sortable: true,
                // variant: 'danger'
            },
            {
                key: 'rea',
                label: 'Real',
                class: 'text-right',
                sortable: true,
                variant: 'danger'
            }
        ],
        preciosLd: false,
    }),
    methods: {
        consultar: function () {
            Importador.lista(this.id).then((response) => {
                this.lista = response.data;
            })
        },
        suma: function (key, lista) {
            return lista.reduce((suma, i) => suma + parseFloat(i[key]), 0)
        },
        formularioLista() {
            this.preciosLd = true;
            Importador.listaUpdate(this.lista).then(() => {
                this.$swal({
                    html: 'Se ha actualizado exitosamente los precios de la Lista',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                this.preciosLd = false;
            }).catch(() => {
                this.preciosLd = false;
            });
        }
    },
    computed: {
        items() {
            return [
                {
                    det: 'Costo de importación',
                    est: this.lista.productos.reduce((a, b) => a + (b.estxc_pr || 0), 0) * (this.lista.precioxl_li || 1),
                    rea: this.lista.productos.reduce((a, b) => a + ((b.cant_pr)*(b.librasR_pr||0)*(this.lista.precioxl_li || 1)), 0),
                },
                {
                    det: 'Costo de envío local',
                    est: this.lista.enviolocal_li || 0,
                    rea: this.lista.enviolocal_li || 0,
                },
            ]
        }
    },
    filters: {
        moneda(valor) {
            valor = parseFloat(valor);
            return valor.toFixed(2);
        }
    },
    created() {
        this.consultar();
    }
}
</script>

<style scoped>
.invalid-feedback {
    margin: 0;
}
</style>
