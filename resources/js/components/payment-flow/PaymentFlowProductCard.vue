<template>
    <div class="row cart-product">
        <div class="col-3 cart-product__image pt-5">
            <span class="cart-product__number">.{{ 1 + number }}</span>
            <span class="cart-product__image-col">
                <img :src="product.image" :alt="product.name">
            </span>
        </div>
        <div class="col-7 cart-product__product scroll">
            <span class="cart-product__product_name">{{ product.markName }} {{ product.name }}</span>
            <div v-for="item in attributes" class="mb-2 d-flex fs-14 w-100 l-h-12">
                <div class="w-30 o-hidden t-o-ellipsis">{{ item.name }}:</div>
                <div class="w-70">
                    <template v-for="(itemValue,key) in item.values">
                        <template v-if="key !== 0 && key < item.values.length">/</template>
                        <template v-if="itemValue.value === true">
                            <i class="text-success icon-check"/>
                        </template>
                        <template v-else-if="itemValue.value === false">
                            <i class="text-red icon-close"/>
                        </template>
                        <template v-else>
                            {{ $t(`common.valueOfTypeList.${itemValue.type}`, {value: itemValue.value}) }}
                        </template>
                    </template>
                </div>
            </div>

            <div v-if="product.config.length > 0">
                <div class="c-primary mt-3 fs-17">Configurator product</div>
                <div v-for="configurator in product.config" class="mb-2 d-flex fs-14 w-100 l-h-12">
                    <div class="w-30 o-hidden t-o-ellipsis">{{ configurator.name }}:</div>
                    <div class="w-70">
                        <p class="m-0">{{ configurator.info.name }}</p>
                        <p class="m-0">article: {{ configurator.info.article }}</p>
                        <p class="m-0">quantity: {{ configurator.info.quantity }}</p>
                        <p class="m-0" v-if="configurator.info.state != 0 && configurator.info.state != 2">condition:
                            <span v-bind:class="[
                                    { 'c-red': configurator.info.state == 1 },
                                    { 'c-black': configurator.info.state == 2 || configurator.info.state == 3 },
                                    ]">{{ getState(configurator.info.state) }}
                                </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2 p-2 c-white bg-primary">
            <div class="cart-product__buttons" v-if="!offerte">
                <button class="cart-product__delete" v-on:click="removeProduct()"></button>
            </div>
            <div class="d-flex-column-between h-100">
                <div class="w-97">
                    <span class="fs-24">Aantal</span>
                    <div class="cart-product__pseudo-number">
                        <span class="cart-product__pseudo-number-decr" v-if="!offerte"
                              @click="changeProductCount('minus')">-</span>
                        <input type="number" class="cart-product__summary"
                               v-model="counter"
                               v-on:change="changeProductCount()"
                               v-bind:max="product.maxCounter"
                               v-bind:step="getStep()"
                               v-bind:disabled="offerte"
                               v-bind:min="getMinCounter()">
                        <span class="cart-product__pseudo-number-inc" v-if="!offerte"
                              @click="changeProductCount('plus')">+</span>
                    </div>
                </div>
                <div class="cart-product__price-data">
                    <span class="fs-14">Totaal prijs</span>
                    <hr>
                    <span class="fs-24">{{ checkPrice(Number(counter) * product.price) }}
                        <span class="fs-17">excl.</span>
                    </span>
                    <span class="fs-14">{{ checkPrice(Number(counter) * product.priceWithNDS) }}
                        <span class="fs-12">incl.</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from '../../helper'

export default {
    props: {
        product: {
            type: Object,
            required: true
        },
        orderId: {},
        number: {
            type: Number,
            required: true
        },
        offerte: ''
    },
    data() {
        return {
            step: 1,
            counter: this.product.quantity,
        }
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        removeProduct() {
            this.$root.$emit('deleteProductWithCart', this.product)
        },
        getMinCounter() {
            return this.product.multiBatch ? this.product.multiBatch : 1
        },
        getStep() {
            this.step = this.product.multiBatch ? this.product.multiBatch : 1
            return this.step
        },
        changeProductCount(type) {
            let timeCounter = this.counter
            if (type === 'minus') {
                timeCounter = this.counter > 1 ? (this.counter - this.step) : 1;
            } else if (type === 'plus') {
                timeCounter = this.counter < this.product.maxCounter ? (this.counter + this.step) : this.product.maxCounter;
            }

            timeCounter = timeCounter < this.step ? this.step : timeCounter

            if (this.product.multiBatch) {
                let ost = this.counter % this.product.multiBatch

                if (ost < this.product.multiBatch && ost != 0) {
                    timeCounter = this.product.multiBatch
                }
            }

            if (this.oldCounter != timeCounter) {
                axios.post('/cart/quantity', {
                    prodId: this.product.productId,
                    quantity: timeCounter,
                    orderId: this.orderId,
                }).then((response) => {
                    // this.counter = timeCounter
                    this.$root.$emit('getProductsInCart')
                }).catch(function (e) {
                    console.log(e)
                })
            } else {
                this.counter = timeCounter
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
        }
    },
    watch: {
        product: {
            deep: true,
            handler() {
                this.counter = this.product.quantity
            }
        },
    },
    computed: {
        attributes() {
            return this.product?.attributes.filter(item => item.hook === 'base') ?? []
        },
        oldCounter: function () {
            return this.product.quantity
        }
    }
}
</script>
