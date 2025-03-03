<template>
    <div class="popup-product-raid-warning popup-message popup-message__open">
        <div class="popup-message__inner popup-product-raid-warning__inner">
            <div class="popup-message__close" @click="close">
                <span class="c-black fs-20 ">X</span>
                <!--                <img src="/images/close.svg" alt="close"/>-->
            </div>
            <label class="popup-product-raid-warning__title c-sc text-bold">Letop!</label>

            <p class="fs-16 mt-4">
                U heeft momenteel opslag schijven gekozen. Wij willen u er op attenderen
                dat hiervoor de correcte <span class="fw-bold bb-black-2">“raid controller”</span> gekozen moet worden. Wanneer u
                een raid configuratie wilt toepassen, is het belangerijk om te weten dat de
                raid controller passend is voor SAS, SATA of NVMe schijven. Mocht u hier
                hulp bij nodig hebben dan kunt u uiteraard contact met ons opnemen.

            </p>

            <div class="mt-25">
                <input class="popup-product-raid-warning__submit" type="submit" @click="close" value="OK">
            </div>
        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>

export default {
    name: "popupProductRaidWarning",

    methods: {
        close() {
            this.$emit('update:popupProductRaidShow', false)
        },
    }
}
</script>

<style lang="scss">

.popup-product-raid-warning {
    font-size: 16px;

    &__inner {
        border: 1px solid black;
        padding: 30px 40px 30px 40px;
        width: 620px;
        min-width: 620px;
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