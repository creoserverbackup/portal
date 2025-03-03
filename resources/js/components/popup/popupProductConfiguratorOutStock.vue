<template>
    <div class="popup-message" v-if="products.length > 0" v-bind:class="{ 'popup-message__open': products.length > 0}">
        <div class="popup-message__inner">
            <div class="popup-message__close" @click="close">
                <img src="/images/close.svg" alt="close"/>
            </div>
            Aankoop is niet mogelijk wegens niet op voorraad:
            <div class="popup-message__body scroll">
                <div class="popup-message__body-message" v-for="product in products">
                    <div>
                        {{ product.name }}
                    </div>
                </div>
            </div>
            <template v-if="productMaim.masterId">
                <div class="popup-message__submit">
                    <input type="submit" @click="close" value="Ok">
                </div>

                <div class="popup-message__submit">
                    <a v-bind:href="getUrl(productMaim.masterId)" target="_blank">
                        <input type="submit" value="Ga naar het hoofdproduct ?">
                    </a>
                </div>
            </template>
            <template v-else>
                <div class="popup-message__submit">

                    <router-link to="/contact-center">
                        <input type="submit" value="Neem contact met ons op">
                    </router-link>
                </div>
            </template>
        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>
export default {
    name: "popupProductConfiguratorOutStock",
    props: {
        productMaim: {},
        productsConfiguratorOutStock: '',
    },
    data() {
        return {
            products: [],
        }
    },
    mounted() {
        this.products = this.productsConfiguratorOutStock
    },
    methods: {
        getUrl(productId) {
            return process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + productId
        },
        close() {
            this.products = []
        },
    },
    watch: {
        productsConfiguratorOutStock: {
            handler() {
                this.products = this.productsConfiguratorOutStock
            }, deep: true
        }
    },
}
</script>

<style scoped>

</style>