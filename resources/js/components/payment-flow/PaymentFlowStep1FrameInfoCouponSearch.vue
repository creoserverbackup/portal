<template>
    <div class="search-form__field">
        <input class="search-form__input" v-bind:list="coupon.name"
               v-model="searchWord"
               :placeholder="coupon.placeholder"
               @input="searchCoupon()">
        <button class="search-form__submit" ref="sendWord" v-on:click="searchCoupon()">
            <span class="c-gray">GO</span>
        </button>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep1FrameInfoCouponSearch",
    data() {
        return {
            searchWord: '',
            coupon: {
                name: 'coupon',
                selected: '',
                placeholder: 'korting coupon invoeren ...',
                value: [],
            },
        }
    },
    methods: {
        searchCoupon() {
            this.$emit('startSearchCoupon', this.searchWord)
        },
    },
}
</script>

<style scoped>

</style>