<template>
    <div class="pf-frame-info-button">
        <div class="w-70 d-flex-row-between">
            <div class="pf-frame-info-button__btn back-color-red mr-2" @click="clickButton('cancel')">
                Annuleren
            </div>
            <div class="pf-frame-info-button__btn mr-2" @click="clickButton('offerte_print')"
                 v-bind:class="[customer.customerId ? 'bg-primary' : 'bg-gray']">
                Printen
            </div>
            <div class="pf-frame-info-button__btn" @click="clickButton('offerte')"
                 v-bind:class="[customer.customerId ? 'bg-primary' : 'bg-gray']">
                Send offerte
            </div>
        </div>
        <div class="w-28">
            <div class="pf-frame-info-button__btn" @click="clickButton('product')"
                 v-bind:class="[customer.customerId ? 'bg-primary' : 'bg-gray']">
                Send bestelling
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep1FrameInfoButton",
    props: {
        customer: '',
    },
    methods: {
        clickButton(type)
        {
            if (this.customer.customerId) {
                this.$emit('clickButton', type)
            }
        }
    }
}
</script>


<style lang="scss">

@import "resources/sass/abstracts/variables";

.pf-frame-info-button {

    margin-top: 5px;
    color: white;
    display: flex;
    flex-direction: row;
    justify-content: space-between;

    &-left {
        width: 70%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    &__btn {
        text-align: center;
        height: 40px;
        line-height: 2.5;
        width: 100%;
        font-size: 16px;
        cursor: pointer;

        &-printer {
            width: 70%;
        }

        &-disable {
            cursor: none;
            background-color: $dark--thin;
        }
    }
}

</style>