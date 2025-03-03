<template>
    <div class="form-select__country">

        <select class="form-select cart-s3__type-country" v-bind:list="objectSelect.name"
                v-model="objectSelect.selected"
                :disabled="disabled"
                @change="changeSelect"
                :class="{
                                'first-input' : classAdd === 'first-input',
                                'last-input' : classAdd === 'last-input',
                                }">
            <option v-for="item in objectSelect.value" :value="item.name" :selected="item.name">{{ item.name }}</option>
        </select>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep3SelectCountry",
    props: {
        objectSelect: {
            type: Object,
        },
        classAdd: {
            type: String,
        },
        disabled: {
            default: true,
            type: Boolean,
        },
    },
    methods: {
        changeSelect() {
            this.$emit('changeSelect', this.objectSelect)
        }
    },
};
</script>
