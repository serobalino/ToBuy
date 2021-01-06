<template>
    <div class="jumbotron" v-if="lista.lista">
        <h4 class="display-5">{{ lista.lista.titulo_li }}</h4>
        <div class="row">
            <div class="col" v-if="lista.lista.precioxl_li">
                <b>Costo por libra:</b> <span class="precio">{{lista.lista.precioxl_li}}</span>
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
                            <span class="precio">{{ suma('est',items) | moneda }}</span>
                        </td>
                        <td class="text-right bg-danger">
                            <span class="precio">{{ suma('rea',items) | moneda }}</span>
                        </td>
                    </tr>
                </template>
            </b-table>
        </div>
        <hr>
        <div class="row p-0">
            <template v-for="(item,index) in lista.lista.usuarios" >
                <div class="col-auto">
                    <b-badge pill variant="primary" :key="index">
                        {{item.name}} <b-icon icon="x-circle" scale="2" class="boton" variant="danger" v-if="lista.creador_se && lista.id_us!==item.id"/>
                    </b-badge>
                </div>
            </template>
        </div>
        <hr>
        <p class="lead" v-if="lista.creador_se">
            <button class="btn btn-outline-primary btn-sm" type="button" @click="modal=true">
                Agregar colaborador
            </button>
        </p>
        <div v-if="lista.creador_se">
            <div class="input-group input-group-sm">
                <span type="text" class="form-control" v-on:click="copiar">{{lista.lista.code_li}}</span>
                <div class="input-group-append">
                    <button class="btn btn-danger" type="button" v-on:click="copiar" id="tooltip-copiado">
                        <i class="fa fa-copy"/>
                    </button>
                </div>
            </div>
            <b-tooltip :show.sync="copiado" target="to  oltip-copiado">
                Copiado al portapapeles
            </b-tooltip>
        </div>
        <modal-colaborador
            v-model="modal"
            :lista="lista"
        />
    </div>
</template>

<script>

export default {
    name: "ListaDetails",
    data:()=>({
        modal:false,
        copiado:false,
        columnas:[
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
        ]
    }),
    props: {
        lista: {
            type: Object,
            required: true,
        }
    },
    methods:{
        suma: function (key, lista) {
            return lista.reduce((suma, i) => suma + parseFloat(i[key]), 0)
        },
        copiar: function(){
            this.$copyText(this.lista.lista.code_li).then(()=> {
                this.copiado=true;
                // setTimeout(()=>{
                //     this.copiado=false;
                // },3000)
            },()=> {
                alert('Can not copy')
            })
        }
    },
    computed:{
        items(){
            return [
                {
                    det:'Costo de importación',
                    est: this.lista.productos_lista.reduce((a, b) => a+(b.estxc_pr || 0), 0)*(this.lista.lista.precioxl_li || 1),
                    rea: this.lista.productos_lista.reduce((a, b) => a+(b.realxc_pr || 0) , 0)*(this.lista.lista.precioxl_li || 1),
                },
                {
                    det:'Costo de envío local',
                    est: this.lista.lista.enviolocal_li || 0,
                    rea: this.lista.lista.enviolocal_li || 0,
                },
                {
                    det:'Costo de productos',
                    est: this.lista.productos_lista.reduce((a, b) => a+(b.pvpxc_pr || 0) , 0),
                    rea: this.lista.productos_lista.reduce((a, b) => a+(b.pvpxc_pr || 0) , 0),
                }
            ]
        }
    },
    filters: {
        moneda(valor) {
            valor = parseFloat(valor);
            return valor.toFixed(2);
        }
    },
}
</script>

<style scoped>
.boton{
    cursor: pointer;
}
</style>
