<template>
    <div class="row  payment-flow__step1-main-div">
        <div class="col-md-12 col-sm-12 payment-flow__titles-frame">
            <simplebar class="payment-flow__products_list" data-simplebar-auto-hide="false">
                <div v-for="(product, number) in products">
                    <payment-flow-product-card :product="product"
                                               :orderId="orderId"
                                               :number="number"/>
                </div>
            </simplebar>
        </div>
        <div class="col-md-12 col-sm-12 payment-frame__btn">
            <div class="payment-frame__btn-number">Klantnummer: {{ customerId }}</div>
            <div class="payment-frame__btn-accept" v-on:click="changeOrder(customerId)">
                Toevoegen aan klant winkelwagen
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'
import {checkPriceHelper} from "../../helper.js"
import PaymentFlowProductCard from "./PaymentFlowProductCard";

export default {
    components: {PaymentFlowProductCard},
    props: {
        products: {
            type: Array,
            required: true
        },
        orderId: {},
        customerId: {},
        uid: {},
    },
    computed: {
        ...mapGetters([
            'GET_THEME',
            'GET_LOCALE',
            'GET_LIFELINE',
        ]),
        locale: function () {
            return this.GET_LOCALE
        }
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
