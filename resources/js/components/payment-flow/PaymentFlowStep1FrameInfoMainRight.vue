<template>
    <div class="pf-frame-info-block-right row m-0 p-0 pr-4 pb-2 pt-2">
        <div>
            <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                <div class="mr-3 pf-frame-info-block-label">Aantal voorgaande orders:</div>
                <div class="pf-frame-info-block-value" v-if="customer.countOrder">{{ customer.countOrder }}</div>
            </div>
            <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                <div class="mr-3 pf-frame-info-block-label">Krediet mogelijkheid:</div>
                <div class="pf-frame-info-block-value" v-if="customer.saleId">
                    {{ customer.canBuyAccount == 1 ? 'Ja' : 'Nee' }}
                </div>
            </div>

            <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                <div class="mr-3 pf-frame-info-block-label">Tier prijslijst:</div>
                <div class="pf-frame-info-block-value" v-if="customer.saleId">Tier {{ customer.saleId }}</div>
            </div>
        </div>
        <div>
            <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                <div class="mr-3 pf-frame-info-block-label">Kvk nr:</div>
                <div class="pf-frame-info-block-value">{{ customer.kvk }}</div>
            </div>

            <div class="row m-0 p-0 pr-4 pb-2 pt-2">
                <div class="mr-3 pf-frame-info-block-label">BTW nr:</div>
                <div class="pf-frame-info-block-value">{{ customer.btw }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep1FrameInfoMainRight",
    props: {
        customer: '',
    },
}
</script>

<style scoped>

</style>