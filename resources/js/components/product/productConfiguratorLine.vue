<template>
    <div class="product-configurator" @click="toggle()" @v-click-outside="toggle"
         @focusout="handleFocusOut"
         tabindex="1">
        <div class="product-configurator__line">
            <div class="product-configurator__input" v-bind:class="{ 'z-100 opacity-1': visible }">
                <input type="text" ref="searchInput" v-model="search" @input="startSearch">
            </div>
            <div class="product-configurator__result"
                 v-bind:class="[
             disabled ? 'product-configurator__disabled' : '',
             geenOption ? 'product-configurator__option-geen' : '',
             // main && (main.quantity >= 0 || main.installed) ? 'product-configurator__option-select' : '',
             main && (main.quantity >= 0 || main.quantity <= 0 || main.installed) ? 'product-configurator__option-select' : '',
             ]">
                <div class="product-configurator__label" v-bind:class="[main && main.installed ? 'c-g' : '']">
                    <div class="d-flex w-70 ws-nowrap text-nowrap">
                        <span class="text-nowrap max-w-70">{{ mainText }}</span>

                        <span class="ws-nowrap text-nowrap" v-if="options.hardDiskRecalculate != false && main.sku && main.sku.length > 0">
                         <span class="td-underline"> [P/N: {{ main.sku }} ]</span> </span>

                        <span class="fw-bold">&nbsp;{{ getPriceText(main) }}</span>
                        <template
                                v-if="main && main.base && main.baseQuantity > 0 && main && main.baseQuantity == options.counter">
                            <span class="fw-bold">+ Default</span>
                        </template>
                    </div>
                    <div class="c-sc ws-nowrap text-nowrap" v-if="checkMaxQuantity"> {{ checkMaxQuantity }}</div>
                    <div class="c-gray ws-nowrap text-nowrap" v-else-if="checkMaxQuantityRam"> {{ checkMaxQuantityRam }}</div>
                    <div class="c-gray ws-nowrap text-nowrap" v-else-if="checkMaxQuantityCPU"> {{ checkMaxQuantityCPU }}</div>
                </div>
                <div class="product-configurator__arrow" v-if="!disabled"
                     :class="{ 'product-configurator__expanded' : visible }">
                </div>
            </div>
        </div>

        <ul class="scroll" :class="{ hidden : !visible, visible }">
            <li class="product-configurator__label" v-for="option in options.values" @click="selectOption(option)"
                v-bind:class="[{ 'product-configurator__li-selected': main.article === option.article }]" v-if="option.visible">
                <div>
                    {{ option.text }}

                    <span v-if="options.hardDiskRecalculate != false && option.sku && option.sku.length > 0">
                         <span class="td-underline">[P/N: {{ option.sku }} ]</span> </span>

                    <span class="fw-bold">{{ getPriceText(option, true) }}</span>
                    <template v-if="option.base && option.baseQuantity > 0">
                        <span class="fw-bold">+ Default</span>
                    </template>
                    <span class="fw-300" v-if="checkViewStock"
                          v-bind:class="[
                                    { 'c-success': option.quantity > 5 },
                                    { 'c-secondary': option.quantity <= 5 },
                                    { 'c-white': main.article === option.article }
                                    ]">
                        <span v-if="option.quantity > 1">
                        - {{ option.quantity }} {{ option.quantity > 1 ? 'stuks' : 'stuk' }}
                        </span>
                    </span>
                    <span class="fw-300" v-if="option.state && option.state != 0 && option.state != 2"
                          v-bind:class="[
                                    { 'c-red': option.state == 1 },
                                    { 'c-black': option.state == 2 || option.state == 3 },
                                    ]"
                    >- {{ getState(option.state) }}
                    </span>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>

import {checkPriceHelper} from "../../helper.js"
import categories from "../../data/categories";

export default {
    name: "productConfiguratorLine",
    props: {
        options: {},
        selectedInOptions: {},
    },
    data() {
        return {
            main: {
                label: ''
            },
            disabled: false,
            visible: false,
            change: false,
            search: '',
        }
    },
    mounted() {
        this.main = this.options.values.find((value) => {
            return (value.selected === true)
        })

        this.isDisabled()
        this.startSearch()
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        handleFocusOut() {
            // this.visible = false
        },
        isDisabled() {
            this.disabled = this.options.disabled || (this.options.values.length == 1 && !this.options.isProduct)
                    || (this.options.values.length == 1 && !this.options.disabled)
        },
        toggle() {
            if (!this.disabled) {
                this.visible = !this.visible;

                if (this.visible) {
                    this.$refs.searchInput.focus()
                }
            }
        },
        selectOption(option) {
            this.main = option;
            this.change = true
            this.$emit('selectOption', option, this.options)
        },
        getPriceText(option, selected = false) {
            if ((option.baseQuantity != undefined && option.baseQuantity == this.options.counter && option.baseQuantity != 0)
                    || option.value == 0) {
                return ''
            } else {
                let quantity = this.options.counter
                quantity = quantity <= 0 ? 1 : quantity

                if (selected) {
                    quantity = 1
                }

                let price = option.priceOption * quantity - this.options.priceBase
                let sign = ''
                if (price > 0) {
                    sign = '+ '
                }
                return sign + this.checkPrice(price) + " p.s "
            }
        },
        getState(state) {
            if (state == 1) {
                return 'New condition'
            } else if (state == 2) {
                return 'Refurbished'
            } else if (state == 3) {
                return 'Recertified'
            }
        },
        startSearch()
        {
            this.options.values.forEach(value => {
                if (this.search !== '') {
                    value.visible = value.text.includes(this.search)
                } else {
                    value.visible = true
                }
            })
        }
    },
    watch: {
        selectedInOptions: {
            deep: true,
            handler() {
                this.main = this.options.values.find((value) => {
                    return (value.selected === true)
                })
            }
        },
    },
    computed: {
        geenOption() {
            return this.main && this.main.isProduct == false && this.options.values.length > 1
        },
        checkMaxQuantity() {
            if (this.options.counter == this.options.maxQuantity && this.options.maxQuantity >= 1) {
                return 'Maximum capiciteit bereikt!'
            }
            if (this.main && this.options.counter == this.main.quantity) {
                return 'Maximum op voorraad!'
            }
            return ''
        },
        checkMaxQuantityCPU() {
            if (this.options.categoryId == categories.cpu && this.options.maxQuantity > 0) {
                return 'Totaal ' + this.options.maxQuantity + ' CPU sockets op deze server'
            }
            return ''
        },
        checkMaxQuantityRam() {
            if (this.options.categoryId == categories.ram && this.options.maxQuantity > 0) {
                return 'Totaal ' + this.options.maxQuantity + ' geheugensloten op deze server'
            }
            return ''
        },
        checkViewStock() {
            if (this.options.categoryId === categories.ram ||
                    this.options.categoryId === categories.sata_ssd ||
                    this.options.categoryId === categories.sata_m2_ssd ||
                    this.options.categoryId === categories.sas_hdd ||
                    this.options.categoryId === categories.sas_ssd ||
                    this.options.categoryId === categories.nvme_m2_ssd ||
                    this.options.categoryId === categories.nvme_u2_ssd ||
                    this.options.categoryId === categories.nvme_u3_ssd ||
                    this.options.categoryId === categories.nvme_pci_ssd ||
                    this.options.categoryId === categories.sata_hdd
            ) {
                return true
            }

            return false
        },
        mainText() {
            if (this.main && this.main.isProduct == false) {
                if (this.change) {
                    return this.main.text
                }
                return 'Maak een keuze'
            }
            return this.main && this.main.text ? this.main.text : ''
        },
    }
}
</script>

<style scoped>

</style>
