<template>
    <div class="popup-message" v-if="products.length > 0" v-bind:class="{ 'popup-message__open': products.length > 0}">
        <div class="popup-message__inner">
            <div class="popup-message__close" @click="close">
                <img src="/images/close.svg" alt="X">
            </div>
            Maximumhoeveelheid {{ productMaim.name }} geïnstalleerd : {{ quantityMax }}
            <div class="popup-message__body scroll">
                Reden:
                <div class="popup-message__body-message" v-for="product in products">
                    <div v-if="product.quantityMax <= quantityMax">
                        Category {{ product.selectedOptionTemp.label }} {{ product.selectProduct.name }} - geselecteerd
                        {{ product.selectedOptionTemp.counter }} *
                        {{ quantity }} = {{ product.selectedOptionTemp.counter * quantity }}
                        Voorraad balans {{ product.selectProduct.quantity }}
                    </div>
                </div>
            </div>
            Om een grotere hoeveelheid te bestellen, moet u de configurator-instellingen wijzigen of contact met ons
            opnemen
            <div class="popup-message__submit">
                <input type="submit" @click="close" value="Ok">
            </div>
        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>
export default {
    name: "popupMessageQuantityMaxProduct",

    props: {
        productMaim: {}
    },
    data() {
        return {
            products: [],
            quantityMax: '',
            quantity: '',
        }
    },
    mounted() {
        this.$root.$on('popupMessageQuantityMaxProduct', (data) => {
            this.products = [];
            this.products = data.products
            this.quantityMax = data.quantityMax
            this.quantity = data.quantityMax > 0 ? data.quantityMax : 1
        })
    },
    methods: {
        close() {
            this.products = []
        },
    },
    destroyed() {
        this.$root.$off('popupMessageQuantityMaxProduct')
    }
}
</script>

<style scoped>

</style>