<template>
    <div class="row m-0 p-0 pr-4 pb-2 pt-2"
         v-bind:class="{ 'bb-gray pb-3': border }">
        <div class="col-7 row m-0 p-0">
            <div class="mr-3 pf-frame-info-block-label">{{ labelLeft }}:</div>
            <div class="pf-frame-info-block-value">{{ valueLeft }}</div>
        </div>
        <div class="row m-0 p-0">
            <div class="mr-3 pf-frame-info-block-label" v-if="labelRight">{{ labelRight }}:</div>
            <div class="pf-frame-info-block-value">{{ valueRight }}</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep1FrameInfoMainLine",
    props: {
        labelLeft: '',
        valueLeft: '',
        labelRight: '',
        valueRight: '',
        border: '',
    },
}
</script>

<style scoped>

</style>