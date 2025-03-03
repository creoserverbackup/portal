<template>
    <div class="popup-product-caddy-warning popup-message popup-message__open">
        <div class="popup-message__inner popup-product-caddy-warning__inner">
            <div class="popup-message__close" @click="close">
                <span class="c-black fs-20 ">X</span>
<!--                <img src="/images/close.svg" alt="close"/>-->
            </div>
            <label class="popup-product-caddy-warning__title c-sc text-bold">Letop!</label>

            <p class="fs-16 mt-4">
            U heeft momenteel opslag schijven besteld. Wij willen u er op attenderen
            dat deze <span class="fw-bold bb-black-2">zonder</span> caddies waar de schijven in behoren, geleverd worden.
                </p>


            <p class="fs-16 mt-3">
                Mocht u deze schijven in uw product willen laten plaatsen, dan zult u bij
            het dropdown veld <span class="fw-bold bb-black-2">“Caddies / Trays”</span> de juiste caddies moeten selecteren.
                </p>

            <div class="mt-25">
                <input class="popup-product-caddy-warning__submit" type="submit" @click="close" value="OK">
            </div>
        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>

export default {
    name: "popupProductCaddyWarning",

    methods: {
        close() {
            this.$emit('update:popupProductCaddyShow', false)
        },
    }
}
</script>

<style lang="scss">

.popup-product-caddy-warning {
    font-size: 16px;

    &__inner {
        border: 1px solid black;
        padding: 30px 40px 30px 40px;
        width: 610px;
        min-width: 610px;
    }

    &__title {
        text-align: center;
        width: 100%;
    }

    &__submit {
        width: 30% !important;
        text-align: center !important;
        margin-left: auto;
        display: block;
        margin-right: auto;
    }

    .popup-message__background {
        background: rgba(255, 255, 255, 0.7);
    }
}

</style>