<template>
    <div class="pf-frame-info">
        <p class="pf-frame-info-title">Order eisen</p>
        <div class="pf-frame-info-block">
            <div class="pf-frame-info-order">
                <div class="pf-frame-info-order-data">
                    <div class="mr-2 pf-frame-info-block-label mt-3">Aantal dagen geldig:</div>
                    <div class="pf-frame-info-order-value">
                        <input type="number" v-model="date" class="pf-frame-info-order-value-input" @input="setData">
                    </div>
                </div>

                <div class="pf-frame-info-order-data">
                    <div class="mr-2 pf-frame-info-block-label mt-3">Btw percentage:</div>
                    <div class="pf-frame-info-order-value">
                        <select class="pf-frame-info-order-value-select pt-1" v-model="btw" @change="setData">
                            <option v-for="option in optionsBtw" v-bind:value="option.value">
                                {{ option.value }}%
                            </option>
                        </select>
                    </div>
                </div>

<!--                <div class="pf-frame-info-order-data">-->
<!--                    <div class="mr-2 pf-frame-info-block-label mt-3">Order op krediet:</div>-->
<!--                    <div class="pf-frame-info-order-value">-->
<!--                        <select class="pf-frame-info-order-value-select pt-1" v-model="credit">-->
<!--                            <option v-for="option in optionsCredit" v-bind:value="option.value">-->
<!--                                {{ option.value }}-->
<!--                            </option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep1FrameInfoOrder",
    data() {
        return {
            date: 30,
            btw: 21,
            credit: 'Nee',

            optionsBtw: [
                {
                    value: 21,
                },
                {
                    value: 9,
                },
                {
                    value: 6,
                },
                {
                    value: 0,
                },
            ],
            optionsCredit: [
                {
                    value: 'Nee',
                },
                {
                    value: 'Yes',
                },
            ],
        }
    },
    methods: {
        setData() {
            this.$emit('setData', {
                date : this.date,
                nds : this.btw,
            })
        },
    }
}
</script>

<style scoped>

</style>