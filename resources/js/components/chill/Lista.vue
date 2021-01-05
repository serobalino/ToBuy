<template>
    <b-list-group-item :href="item.route_li" class="flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">{{ item.titulo_li }}</h5>
            <small>{{ item.updated_at | fecha }}</small>
        </div>
    </b-list-group-item>
</template>

<script>
import moment from "moment";

export default {
    name: "Lista",
    props: {
        item: {
            type: Object
        }
    },
    filters: {
        fecha(valor) {
            const date = moment(valor);
            return date.fromNow();
        }
    }
}
</script>

<style scoped>

</style>
