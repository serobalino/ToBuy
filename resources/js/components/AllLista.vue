<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Listas de compras</div>
            <div class="card-body">
                <nueva-lista
                    @actualizar="consulta"
                />
            </div>
            <div class="card-body">
                <b-list-group>
                    <template v-for="(item,index) in lista">
                        <lista
                            :item="item"
                            :key="index"
                        />
                    </template>
                </b-list-group>
            </div>
        </div>
    </div>
</template>

<script>

import {Listas} from "../servicios";

export default {
    name: "AllLista",
    data: () => ({
        lista: []
    }),
    methods: {
        consulta: function(){
            Listas.index().then(response=>{
                this.lista=response.data;
            })
        }
    },
    mounted() {
        this.consulta();
    }
}
</script>

<style scoped>

</style>
