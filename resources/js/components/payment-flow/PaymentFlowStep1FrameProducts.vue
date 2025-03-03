<template>
    <div class="col-md-12 col-sm-12 payment-flow__titles-frame b-black h-100 p-20">
        <div class="d-flex-row-between w-97 mb-2">
            <span class="fs-16">Samengestelde product(en) voor de klant</span>
            <span class="fs-12">Aantal producten: {{ products.length }}</span>
        </div>
        <simplebar class="pf-frame__products_list" data-simplebar-auto-hide="false">
            <div v-for="(product, number) in products">
                <payment-flow-product-card :product="product"
                                           :orderId="orderId"
                                           :number="number"/>
            </div>
        </simplebar>
    </div>
</template>

<script>

import {checkPriceHelper} from "../../helper.js"
import PaymentFlowProductCard from "./PaymentFlowProductCard";

export default {
    name: "PaymentFlowStep1FrameProducts",
    components: {
        PaymentFlowProductCard
    },
    props: {
        products: {
            type: Array,
            required: true
        },
        orderId: {},
    },
    data() {
        return {}
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        clearCart() {
            this.$root.$emit('deleteAllProductInCart')
        },
        changeOrder(customerId) {
            if (customerId) {
                axios.post('/frame/order', {
                    customerId: customerId
                }).then((response) => {
                    window.parent.postMessage("reload", "*");
                }).catch((e) => {
                })
            }
        }
    },
}
</script>

<style scoped>

</style>