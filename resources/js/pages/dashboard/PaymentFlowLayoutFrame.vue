<template>
    <header class="dashboard-roof">
        <div class="dashboard-roof__main">
            <payment-flow-step1-frame/>
        </div>
        <div class="dashboard-roof__basket">
        </div>
    </header>
</template>

<script>

import PaymentFlowStep1Frame from "../../components/payment-flow/PaymentFlowStep1Frame";

export default {
    name: "PaymentFlowLayoutFrame",
    components: {
        PaymentFlowStep1Frame
    },
}
</script>