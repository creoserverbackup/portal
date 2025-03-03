<template>
    <div class="product-summary__info scroll" v-if="selectedInOptionsAdded.length > 0">
        <span class="product-summary__details-header">Toegevoegd</span>
        <div v-if="!option.counter || option.counter > 0"
             v-for="(option, index) in selectedInOptionsAdded"
             class="product-summary__detail">
            <span class="product-summary__detail-type">{{ getQnt(option) }}x {{ option.label }}</span>
            <span class="product-summary__detail-name">{{ getOptionName(option) }}</span>
            <span class="product-summary__detail-price">extra: {{ checkPrice(getOptionPrice(option)) }} excl.</span>
            <button v-if="option.counter" v-on:click="deleteOption(option)" class="product-summary__detail-delete">x
            </button>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper.js"
import categories from "../../data/categories";

export default {
    name: "productConfigSelect",
    props: {
        selectedInOptionsAdded: '',
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        getQnt(item) {
            return item.counter ? item.counter : 1
        },
        getOptionName(item) {
            let selected = item.values.find(val => val.selected)
            selected.overInfo = selected.overInfo == undefined ? '' : selected.overInfo
            return selected ? selected.text + selected.overInfo : 'Choose option'
        },
        getOptionPrice(item) {
            let counter = item.counter ? item.counter : 1
            let selected = item.values.find(val => val.selected)
            if (item.base) {
                return selected ? (selected.priceOption * counter) : 'Choose option'
            }
            return selected ? (selected.priceOption * counter - item.priceBase) : 'Choose option'
        },
        deleteOption(option) {
            this.$emit('deleteOption', option.categoryId)
        }
    }
}
</script>

<style scoped>

</style>
