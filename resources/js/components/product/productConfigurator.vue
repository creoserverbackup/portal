<template>
    <div v-if="product.options.length">
        <product-configurator-out-stock-message-line v-if="product.products_configurator_out_stock.length > 0 && product.masterId" :product="product" />
        <div class="product-main__config-heading"
             :class="{'product-main__config-heading--bold': product.sale}">
            Configureer hier zelf uw eigen product
        </div>
        <div class="product-main__config-item mb-3 fs-16 fw-300 d-flex position-relative" v-for="options in product.options">
                <div class="product-main__config-setting">{{ options.label }}</div>
                <div class="product-main__config-select">
                    <div class="w-100 d-flex">
                        <div class="product-main__config-select-item-col product-main__config-select-item-col--select">
                            <div class="d-flex w-100"
                                 :class="{'product-main__wrap-select-success': options.values.length > 0 && options.counter > 0}">
                                <div class="product-main__wrap-select-col product-main__wrap-select-col--select">
                                    <product-configurator-line
                                            :options="options"
                                            :selectedInOptions="selectedInOptions"
                                            @selectOption="selectOption"
                                    />
                                </div>
                            </div>
                        </div>
                        <product-configurator-url-select v-if="options.urlSelect != '' && options.urlSelect != undefined"
                                                         :options="options" />
                        <div class="product-main__config-select-item-col product-main__config-select-item-col--input-count">
                            <input class="product-main__config-count" type="number"
                                   v-if="options.isNumberAllowed"
                                   :disabled="options.disabled"
                                   v-bind:class="[
                                       options.values.length > 0 && options.counter > 0 ? 'product-main__config-count-success' : '',
                                       options.counter == getMaxOptionQuantity(options) ? 'c-sc' : '',
                                       ]"
                                   v-on:input="verifyData(options)"
                                   v-bind:max="getMaxOptionQuantity(options)"
                                   v-bind:min="getMinOptionQuantity(options)"
                                   v-model="options.counter">
                            <input disabled v-else class="product-main__config-count"
                                   v-bind:placeholder="getPlaceholder(options)">
                        </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProductConfiguratorLine from "./productConfiguratorLine";
import {default as Categories} from "../../data/categories";
import ProductConfiguratorUrlSelect from "./productConfiguratorUrlSelect";
import ProductConfiguratorOutStockMessageLine from "./productConfiguratorOutStockMessageLine";

export default {
    name: "productConfigurator",
    components: {
        ProductConfiguratorOutStockMessageLine,
        ProductConfiguratorUrlSelect,
        ProductConfiguratorLine
    },
    props: {
        product: {},
        selectedInOptions: {},
    },
    data() {
        return {
            time: null,
        }
    },
    methods: {
        verifyData: function (select) {
            let selectProduct = select.values.find((value) => {
                return (value.selected === true)
            })

            if (select.counter > selectProduct.quantity) {
                select.counter = selectProduct.quantity
            }

            if (selectProduct.hardDiskRecalculate) {
                if (this.hardDiskSlot > this.hardDiskSlotProduct) {

                    select.counter = this.hardDiskSlotProduct - this.hardDiskCounterAnotherLine(select.categoryId)

                    this.startPopupQuantityMessage()
                }
            }

            if (select.maxQuantity && select.counter > select.maxQuantity) {
                select.counter = select.maxQuantity
            }

            if (select.counter < selectProduct.baseQuantity) {
                select.counter = selectProduct.baseQuantity
            }
            this.$emit('rebuildOptions')
        },
        getMaxOptionQuantity(select) {
            let selectProduct = select.values.find((value) => {
                return (value.selected === true)
            })

            if (selectProduct.quantity < selectProduct.baseQuantity) {
                return selectProduct.baseQuantity
            }

            if (select.maxQuantity && selectProduct.quantity > select.maxQuantity) {
                return select.maxQuantity
            }

            return selectProduct.quantity
        },
        getMinOptionQuantity(select) {
            let selectProduct = select.values.find((value) => {
                return (value.selected === true)
            })
            return selectProduct.baseQuantity < 1 ? 1 : selectProduct.baseQuantity
        },
        getPlaceholder(options) {
            return options.isProduct ? options.counter ? options.counter : 0 : 'nvt'
        },
        selectOption(option, selectProduct) {
            let hardDiskCounterAnotherLine = this.hardDiskCounterAnotherLine(option.categoryId)

            if (selectProduct.hardDiskRecalculate && this.hardDiskSlotProduct <= hardDiskCounterAnotherLine) {

                this.startPopupQuantityMessage()

                this.$emit('selectOption', 0, selectProduct)
            } else {
                this.$emit('selectOption', option.article, selectProduct)
            }
        },
        startPopupQuantityMessage()
        {
            clearTimeout(this.time);
            this.time = setTimeout(() => this.popupQuantityMessage(), 300);
        },
        popupQuantityMessage()
        {
            this.$root.$emit('popupMessages',
                    'Het is onmogelijk om meer harde schijven te plaatsen vanwege het feit dat alle slots zijn gevuld.' +
                    'Maximum aantal slots : ' + this.hardDiskSlotProduct)
        },

        hardDiskCounterAnotherLine(categoryId) {

            let hardDiskSlotSet = 0
            this.product.options.forEach((option) => {

                if (option.hardDiskRecalculate && option.categoryId != categoryId &&
                        option.counter > 0 && option.categoryId != Categories.backplane) {

                    let selectProduct = option.values.find((value) => {
                        return (value.selected === true)
                    })

                    if (selectProduct && option.counter > 0) {
                        hardDiskSlotSet += +option.counter
                    }
                }
            })

            return hardDiskSlotSet
        }
    },
    computed: {
        hardDiskSlot() {
            let hardDiskSlotSet = 0
            this.product.options.forEach((option) => {

                if (option.hardDiskRecalculate) {
                    let selectProduct = option.values.find((value) => {
                        return (value.selected === true)
                    })

                    if (selectProduct && option.hardDiskRecalculate && option.counter > 0) {
                        hardDiskSlotSet += +option.counter
                    }
                }

            })
            return hardDiskSlotSet
        },
        hardDiskSlotProduct() {
            let hardDiskSlotProductMax = this.product.hard_disk_slot
            this.product.options.forEach((option) => {

                if (option.categoryId == Categories.backplane) {
                    let selectProduct = option.values.find((value) => {
                        return (value.selected === true)
                    })

                    if (selectProduct && option.counter > 0) {
                        hardDiskSlotProductMax += +option.counter * selectProduct.hardDiskRecalculate
                    }
                }

            })
            return hardDiskSlotProductMax
        },
    },
}
</script>

<style scoped>

</style>
