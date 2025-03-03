<template>
    <div class="mt-5 c-white d-flex-row-between">
        <div class="w-70 p-15 fs-16 bg-primary">
            <div class="row">
                <div class="col-4 mr-3 pr-0">Sub totaal excl.:</div>
                <div class="">{{ checkPrice(prices.priceFullDiscountAfter) }}</div>
            </div>

            <div class="row">
                <div class="col-4 mr-3 pr-0">Korting bedrag.:</div>
                <div class="">{{ checkPrice(prices.priceFullDiscount) }}</div>
            </div>

            <div class="row">
                <div class="col-4 mr-3 pr-0">Btw percentage.:</div>
                <div class="">{{ prices.nds }}%</div>
            </div>

            <div class="row">
                <div class="col-4 mr-3 pr-0">Btw bedrag.:</div>
                <div class="">{{ checkPrice(prices.priceFullNDS) }}</div>
            </div>

            <div class="row">
                <div class="col-4 mr-3 pr-0">Sub totaal incl.:</div>
                <div class="">{{ checkPrice(prices.priceFullWithNDS) }}</div>
            </div>

        </div>
        <div class="d-flex-column-between w-28 p-15 ta-c bg-primary">
            <div class="fs-16">Totaal te betalen</div>
            <div class="fs-24">{{ checkPrice(prices.priceFullWithNDS) }}</div>
        </div>
    </div>

</template>

<script>

import {checkPriceHelper} from "../../helper.js"

export default {
    name: "PaymentFlowStep1FrameInfoPrice",
    props: {
         prices: '',
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
    }
}
</script>

<style scoped>

</style>