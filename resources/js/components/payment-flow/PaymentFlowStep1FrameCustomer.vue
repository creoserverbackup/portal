<template>
    <div class="b-black p-20">
        <div class="d-flex-row-between mb-2">
            <span class="fs-16">Voor welke klant is deze samenstelling</span>
            <span class="fs-12">{{ customers.length }} klanten gevonden</span>
        </div>

        <payment-flow-step1-frame-customer-search @startSearchCustomer="startSearchCustomer" ref="searchCustomer"/>
        <payment-flow-step1-frame-info-main :customer="customer"/>
        <payment-flow-step1-frame-info-coupon :prices="prices" @startSearchCoupon="startSearchCoupon"/>
        <payment-flow-step1-frame-info-order @setData="setData" />
        <payment-flow-step1-frame-info-price :prices="prices"/>
        <payment-flow-step1-frame-info-button @clickButton="clickButton" :customer="customer"/>
    </div>
</template>

<script>
import PaymentFlowStep1FrameCustomerSearch from "./PaymentFlowStep1FrameCustomerSearch";
import PaymentFlowStep1FrameInfoMain from "./PaymentFlowStep1FrameInfoMain";
import PaymentFlowStep1FrameInfoCoupon from "./PaymentFlowStep1FrameInfoCoupon";
import PaymentFlowStep1FrameInfoOrder from "./PaymentFlowStep1FrameInfoOrder";
import PaymentFlowStep1FrameInfoPrice from "./PaymentFlowStep1FrameInfoPrice";
import PaymentFlowStep1FrameInfoButton from "./PaymentFlowStep1FrameInfoButton";

export default {
    name: "PaymentFlowStep1FrameCustomer",
    props: {
        customers: '',
        customer: '',
        prices: '',
    },
    components: {
        PaymentFlowStep1FrameInfoButton,
        PaymentFlowStep1FrameInfoPrice,
        PaymentFlowStep1FrameInfoOrder,
        PaymentFlowStep1FrameInfoCoupon,
        PaymentFlowStep1FrameInfoMain,
        PaymentFlowStep1FrameCustomerSearch
    },
    methods: {
        clearForms()
        {
            this.$refs.searchCustomer.clearSearch()
        },
        setData(data)
        {
            this.$emit('setData', data)
        },
        startSearchCustomer(searchWord) {
            this.$emit('startSearchCustomer', searchWord)
        },
        startSearchCoupon(searchWord)
        {
            this.$emit('startSearchCoupon', searchWord)
        },
        clickButton(type)
        {
            this.$emit('clickButton', type)
        }
    },
}
</script>