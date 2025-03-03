<template>
    <div>
        <span class="product-summary__subtitle">Prijs actueel geconfigureerd</span>
        <template v-if="product.options.length">
            <span class="product-summary__subtitle mt-3">Base:</span>
            <div class="product-summary__price-line">
                <span class="fs-20">
                    {{ checkPrice(prices.priceBase * Number(quantity)) }}
                    <span class="fs-12">excl.</span>
                </span>
                <i v-if="!needNds">No VAT needed</i>
                <span class="product-summary__price-incl" v-if="needNds">
                    incl.: {{
                        checkPrice(prices.priceBase * Number(quantity) * (100 + NDS) / 100)
                    }}
                </span>
            </div>

            <span class="product-summary__subtitle mt-3">Configurator:</span>
            <div class="product-summary__price-line">
                <span class="fs-20">
                {{ checkPrice((prices.priceConfigurator - prices.priceConfiguratorBase) * Number(quantity)) }}
                    <span class="fs-12">excl.</span>
                </span>
                <i v-if="!needNds">No VAT needed</i>
                <span class="product-summary__price-incl" v-if="needNds">
                    incl.: {{
                        checkPrice((prices.priceConfigurator - prices.priceConfiguratorBase) * Number(quantity) * (100 + NDS) / 100)
                    }}
                </span>
            </div>

            <span class="product-summary__subtitle mt-3">Total:</span>
            <div class="product-summary__price-line">
                <span class="fs-20">
                    {{ checkPrice(prices.priceFull * Number(quantity)) }}
                    <span class="fs-12">excl.</span>
                </span>
                <i v-if="!needNds">No VAT needed</i>
                <span class="product-summary__price-incl" v-if="needNds">
                      incl.: {{ checkPrice(prices.priceFull * Number(quantity) * (100 + NDS) / 100) }}
                </span>
            </div>

        </template>
        <template v-else>
            <span class="product-summary__subtitle mt-3">Total:</span>
            <div class="product-summary__price-line">
                <span class="fs-20">
                    {{ checkPrice(prices.priceBase * Number(quantity)) }}
                    <span class="fs-12">excl.</span>
                </span>
                <i v-if="!needNds">No VAT needed</i>
                <span class="product-summary__price-incl" v-if="needNds">
                        incl.: {{ checkPrice(prices.priceBase * Number(quantity) * (100 + NDS) / 100) }}
                    </span>
            </div>
        </template>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import {checkPriceHelper} from "../../helper";

export default {
    name: "productPrices",
    props: {
        product: '',
        quantity: '',
        prices: ''
    },
    data() {
        return {
            NDS: 21,
        }
    },
    mounted() {
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
    },
    computed: {
        ...mapGetters([
            'GET_NEED_NDS'
        ]),
        needNds: function () {
            return this.GET_NEED_NDS;
        },
    }
}
</script>

<style scoped>

</style>
