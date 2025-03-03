<template>
    <div class="c-black configurator-out-stock">
        <div class="p-10 fs-16">
            <span class="text-black fw-bold">Letop!:</span>
            Dit product heeft een onderdeel niet op voorraad en staat daarom op aanvraag. Om u toch een alternatief te kunnen bieden adviseren wij u naar de zelfsamenstel variant van dit product te gaan.
            <a v-bind:href="getUrl(product.masterId)" target="_blank">Klik hier</a>
        </div>
    </div>
</template>

<script>
export default {
    name: "productConfiguratorOutStockMessageLine",
    props: {
        product: {},
    },
    methods: {
        getUrl(productId) {
            return process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + productId
        },
    },
}
</script>

<style lang="scss">

.configurator-out-stock {
    border-color: #ffc107;
    background: #feedb7;
}

</style>