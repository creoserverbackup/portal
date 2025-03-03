<template>
    <div>
        <button v-if="isSoldOut" class="product-btn btn--s-success-bg-hover-dark-green pulse-success"
                v-on:click="showPopupPreOrder" type="button">Dit product aanvragen
        </button>
        <button class="product-btn btn--p-primary" v-else type="button" v-on:click="addProduct()">
            In winkelmandje
        </button>

        <button class="product-btn btn--s-success-bg-hover-dark-green pulse-success-3" v-if="!isFrame"
                v-on:click="showLiveChat">Live chat over dit product
        </button>
        <button class="product-btn btn--s-secondary-bg-hover-dark-blue" v-if="!isFrame" @click="downloadOffer"
                v-bind:class="[product.orderAvailable === 0 ? 'bg-gray bg-gray-hover' : '']">
            Download als offerte
        </button>
    </div>
</template>

<script>
import {mapActions} from "vuex";
import {getMessageError} from "../../utils";

export default {
    name: "productButton",
    props: {
        isSoldOut: '',
        product: '',
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
            'ADD_PRODUCT_TO_CART',
            'SET_SHOW_CHAT_MODAL',
            'SET_CHAT_FIRST_MESSAGE',
        ]),
        addProduct() {
            this.$emit('addProduct')
        },
        downloadOffer() {

            if (this.product.orderAvailable === 0) {
                this.$root.$emit('popupMessages', 'Dit product is helaas niet op voorraad. Neemt u alstublieft contact met ons op voor mogelijke alternatieven.')
            } else {
                this.$emit('downloadOffer')
            }
        },
        showLiveChat() {
            this.SET_SHOW_CHAT_MODAL(true)
        },
        showPopupPreOrder() {
            this.$root.$emit('popupPreOrder', {product: this.product})
        },
    },
    computed: {
        isFrame() {
            return this.$route.path.indexOf('frame') > -1
        },
    }
}
</script>

<style scoped>

</style>