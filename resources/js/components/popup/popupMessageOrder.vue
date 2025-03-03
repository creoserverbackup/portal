<template>
    <div class="popup-message" v-if="data && Object.keys(data).length > 0" v-bind:class="{ 'popup-message__open': Object.keys(data).length > 0}">
        <div class="popup-message__inner">
            <div class="popup-message__close" @click="close">
                <img src="/images/close.svg" alt="X">
            </div>
            <template  v-for="item in data">
            <div class="popup-message__body scroll">
                <div class="popup-message__body-message">
                    {{ item.message }}
                </div>
            </div>
            <div class="popup-message__submit" v-if="item.productIdMain">
                <a v-bind:href="getUrl(item.productIdMain)" target="_blank">
                    <input type="submit" value="Product Link">
                </a>
            </div>
                <div class="popup-message__submit" v-else @click="close">
                    <input type="submit" value="Ok">
            </div>
            </template>
        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>
export default {
    name: "popupMessageOrder",
    data() {
        return {
            data: [],
        }
    },
    mounted() {

        this.$root.$on('popupMessageOrder', (data) => {
            this.data = data
        })

    },
    methods: {
        getUrl(productId) {
            return process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + productId
        },
        close() {
            this.data = []
            this.$emit('setStep', 1)
        },
    },
    watch: {
    },
    destroyed() {
        this.$root.$off('popupMessageOrder')
    }
}
</script>

<style scoped>

</style>