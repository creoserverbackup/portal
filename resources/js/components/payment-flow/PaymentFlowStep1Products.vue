<template>
    <div class="col-12 col-md-8 col col--2xl-9 payment-flow__titles">
        <div class="fs-25 c-primary mb-3">
            Gekozen product(en)
            <span class="ml-50 c-secondary" v-if="offerte">Offerte overzicht - {{ creoNum }}</span>
        </div>
        <template v-if="products.length">
            <div class="cart-product__products_list scroll">
                <template v-for="(product, number) in products">
                    <payment-flow-product-card
                            :product="product"
                            :orderId="orderId"
                            :number="number"
                            :offerte="offerte"
                    />
                </template>
            </div>
        </template>
        <template v-else>
            <div class="text-center py-5 c-primary">U heeft momenteel geen producten in uw winkelwagen</div>
            <div class="text-right mr-50">
                <router-link to="/">naar startpagina</router-link>
            </div>
        </template>
    </div>
</template>

<script>
import PaymentFlowProductCard from "./PaymentFlowProductCard";

export default {
    name: "PaymentFlowStep1Products",
    components: {
        PaymentFlowProductCard
    },
    props: {
        products: {},
        orderId: {},
        creoNum: '',
        offerte: '',
    },
}
</script>