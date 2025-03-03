<template>
    <div class="pf-frame-info">
        <div class="pf-frame-info-block-title">
            <p class="pf-frame-info-title">Kortings coupon toevoegen</p>
            <div class="pf-frame-info-block-title-right" @click="openPageCoupon()">
                Korting coupon aanmaken
            </div>
        </div>
        <div class="pf-frame-info-block">
            <div>
                <payment-flow-step1-frame-info-coupon-search
                        @startSearchCoupon="startSearchCoupon"
                />
            </div>
            <div class="pf-frame-info-coupon-data m-0 p-0 mt-4">
                <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                    <div class="mr-2 pf-frame-info-block-label">Kortings code:</div>
                    <div class="pf-frame-info-block-value">{{ prices.coupon }}</div>
                </div>

                <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                    <div class="mr-2 pf-frame-info-block-label">Type korting:</div>
                    <div class="pf-frame-info-block-value">{{ getCouponType(prices.couponType) }}</div>
                </div>

                <div class="row m-0 p-0 pl-4 pb-2 pt-2">
                    <div class="mr-2 pf-frame-info-block-label">Bedrag:</div>
                    <div class="pf-frame-info-block-value" v-if="prices.priceFullDiscount">
                        {{ checkPrice(prices.priceFullDiscount) }}
                    </div>
                    <div class="pf-frame-info-coupon-data-clear"
                         v-if="prices.coupon"
                         @click="startSearchCoupon('')">X
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import PaymentFlowStep1FrameInfoCouponSearch from "./PaymentFlowStep1FrameInfoCouponSearch";
import {checkPriceHelper} from "../../helper";

export default {
    name: "PaymentFlowStep1FrameInfoCoupon",
    props: {
        prices: '',
    },
    components: {
        PaymentFlowStep1FrameInfoCouponSearch
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        openPageCoupon() {
            window.parent.postMessage("coupon", "*");
        },
        startSearchCoupon(searchWord) {
            this.$emit('startSearchCoupon', searchWord)
        },
        getCouponType(type) {
            if (type == '' || type == undefined || type == 0) {
                return ''
            }

            let arr = new Map([
                ['1', 'Percent'],
                ['2', 'Batch percent'],
                ['3', 'Euro'],
                ['4', 'Transport'],
                ['5', 'Kosten'],
                ['6', 'Quickly'],
                ['7', 'Transaction'],
                ['8', 'Personeels korting'],
                ['9', 'Staffel korting'],
                ['10', 'Inruil korting'],
                ['11', 'Factuur bedrag 50%'],
                ['12', 'Factuur bedrag NULL'],
                ['13', 'Geen BTW korting'],
                ['14', 'Onderdelen korting'],
                ['15', 'Korting diverse'],
            ])

            return arr.get(type)

        }
    }
}
</script>

<style scoped>

</style>