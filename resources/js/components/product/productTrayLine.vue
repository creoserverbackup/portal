<template>
    <div class="product-configurator" @click="toggle()" @v-click-outside="toggle"
         @focusout="handleFocusOut"
         tabindex="1">
        <div class="product-configurator__line">
            <div class="product-configurator__input" v-bind:class="{ 'z-100 opacity-1': visible }">
                <input type="text" ref="searchInput" v-model="search" @input="startSearch">
            </div>
            <div class="product-configurator__result product-configurator__option-select">
                <div class="product-configurator__label">
                    <div>
                        {{ mainText }}
                        <span class="fw-bold" v-if="tray">{{ getPriceText(tray) }}</span>
                    </div>
                    <div class="c-sc" v-if="checkMaxQuantity"> {{ checkMaxQuantity }}</div>
                </div>
                <div class="product-configurator__arrow" v-if="!disabled"
                     :class="{ 'product-configurator__expanded' : visible }">
                </div>
            </div>
        </div>

        <ul class="scroll" :class="{ hidden : !visible, visible }">
            <li class="product-configurator__label" @click="selectTray('')">
                <div>Geen</div>
            </li>
            <li class="product-configurator__label" v-for="item in trays" @click="selectTray(item)"
                v-bind:class="[{ 'product-configurator__li-selected': tray.id === item.id }]" v-if="item.visible">
                <div>
                    {{ item.name }}
                    <span class="fw-bold">{{ getPriceText(item, true) }}</span>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>

import {checkPriceHelper} from "../../helper.js"

export default {
    name: "productTrayLine",
    props: {
        trays: {},
        tray: '',
    },
    data() {
        return {
            disabled: false,
            visible: false,
            search: '',
        }
    },
    mounted() {
        this.startSearch()
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        handleFocusOut() {
            // this.visible = false
        },
        toggle() {
            if (!this.disabled) {
                this.visible = !this.visible;

                if (this.visible) {
                    this.$refs.searchInput.focus()
                }
            }
        },
        selectTray(tray) {
            if (tray.visible) {
                tray.urlSelect = process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + tray.productId
            } else if (tray) {
                tray.urlSelect = ''
            }

            this.$emit('selectTray', tray)
        },
        getPriceText(tray, selected = false) {
                let quantity = this.tray.counter
                quantity = quantity <= 0 ? 1 : quantity

                if (selected) {
                    quantity = 1
                }

                let price = tray.price * quantity
                let sign = ''
                if (price > 0) {
                    sign = '+ '
                }
                return sign + this.checkPrice(price) + " p.s "
        },
        startSearch()
        {
            this.trays.forEach(value => {
                if (this.search !== '') {
                    value.visible = value.name.includes(this.search)
                } else {
                    value.visible = true
                }
            })
        }
    },
    computed: {
        checkMaxQuantity() {
            if (this.tray && this.tray.counter == this.tray.quantity) {
                return 'Maximum op voorraad!'
            }
            return ''
        },
        mainText() {
            if (this.tray == '') {
                return 'Maak een keuze'
            }
            return this.tray && this.tray.name ? this.tray.name : ''
        },
    }
}
</script>

<style scoped>

</style>
