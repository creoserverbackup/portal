<template>
    <div class="pf-frame-info">
        <p class="pf-frame-info-title">Klant gegevens</p>
        <div class="pf-frame-info-block">
            <div class="row m-0 p-0">
                <div class="col-8 m-0 p-0 pr-4 pf-frame-info-block-left">
                    <payment-flow-step1-frame-info-main-line
                        :labelLeft="'Bedrijfsnaam'"
                        :valueLeft="customer.customerName"
                        :labelRight="'Klantnummer'"
                        :valueRight="customer.customerId" />

                    <payment-flow-step1-frame-info-main-line
                        :labelLeft="'Contact persoon'"
                        :valueLeft="customer.username"
                        :labelRight="'Bedrijfsvorm'"
                        :valueRight="customer.categoryName"
                        :border="true" />

                    <payment-flow-step1-frame-info-main-line
                            :labelLeft="'Straat'"
                            :valueLeft="customer.address"
                            :labelRight="'Huis nr'"
                            :valueRight="customer.house" />

                    <payment-flow-step1-frame-info-main-line
                            :labelLeft="'Postcode'"
                            :valueLeft="customer.postcode"
                            :labelRight="'Woonplaats'"
                            :valueRight="customer.region" />

                    <payment-flow-step1-frame-info-main-line
                            :labelLeft="'Land'"
                            :valueLeft="customer.country"
                            :labelRight="'Postbus'"
                            :valueRight="customer.mailbox"
                            :border="true" />

                    <payment-flow-step1-frame-info-main-line
                            :labelLeft="'Email'"
                            :valueLeft="customer.email" />

                    <payment-flow-step1-frame-info-main-line
                            :labelLeft="'Tel'"
                            :valueLeft="customer.phone" />

                    <payment-flow-step1-frame-info-main-line
                            :labelLeft="'Mobiel'"
                            :valueLeft="customer.phoneMobile" />
                </div>
                <div class="col-4 mr-0 pr-0">
                    <payment-flow-step1-frame-info-main-right :customer="customer" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PaymentFlowStep1FrameInfoMainLine from "./PaymentFlowStep1FrameInfoMainLine";
import PaymentFlowStep1FrameInfoMainRight from "./PaymentFlowStep1FrameInfoMainRight";
export default {
    name: "PaymentFlowStep1FrameInfoMain",
    components: {
        PaymentFlowStep1FrameInfoMainRight,
        PaymentFlowStep1FrameInfoMainLine
    },
    props: {
        customer: '',
    },
}
</script>

<style scoped>

</style>