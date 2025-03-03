<template>
    <div class="popup-message" v-if="products.length > 0" v-bind:class="{ 'popup-message__open': products.length > 0}">
        <div class="popup-message__inner min-w-730px">
            <div class="popup-message__close" @click="close">
                <img src="/images/close.svg" alt="close"/>
            </div>
            <div class="c-primary text-bold">Confguratie is aangepast</div>
            <!--            <div class="popup-message__body scroll">-->
            <!--                <div class="popup-message__body-message" v-for="product in products">-->
            <!--                    <div>-->
            <!--                        {{ product.name }}-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->

            <div class="mt-5">
                De configuratie is aangepast omdat een standaardonderdeel niet op voorraad is.
                We hebben automatisch een alternatief ingevoerd en de prijs aangepast.Wilt u dit
                product zelf samenstellen, dan raden wij u aan naar het hoofdproduct te gaan.
            </div>


            <template>
                <div class="d-flex-row-between mt-5 fs-16">
                    <div class="popup-message__submit">
                        <a v-bind:href="getUrl(productMaim.masterId)" target="_blank">
                            <input type="submit" value="NAAR HET HOOFDPRODUCT">
                        </a>
                    </div>

                    <div class="popup-message__submit">
                        <input type="submit" @click="close" value="DOORGAAN MET DIT PRODUCT">
                    </div>
                </div>

            </template>

        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>
export default {
    name: "popupProductConfiguratorDefaultChange",
    props: {
        productMaim: {},
        productsConfiguratorDefaultChange: '',
    },
    data() {
        return {
            products: [],
        }
    },
    mounted() {
        this.products = this.productsConfiguratorDefaultChange
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
        productsConfiguratorDefaultChange: {
            handler() {
                this.products = this.productsConfiguratorDefaultChange
            }, deep: true
        }
    },
}
</script>

<style scoped>

</style>