<template>
    <div class="row">
        <div class="col-12 col col--2xl-11">
            <div class="row">
                <div class="col-6 col-sm-4 col col--xl-20" v-for="(step, index) in steps">
                    <div class="payment-flow__steps_item"
                         v-bind:class="[step.passed ? 'c-white bg-secondary' : '']">{{ step.name }}
                    </div>
                </div>
            </div>

            <payment-flow-step-cancel @setStep="setActiveStep"
                                      :orderDescription="orderDescription"
                                      :orderId="orderId"
                                      :creoNum="creoNum"
                                      :prices="prices"
                                      :user="user"
                                      :products="products"
                                      v-if="orderType === 'cancel'"/>

            <template v-for="(step) in steps" v-if="reRenderKey != 0 && orderType !== 'cancel'">
                <payment-flow-step1 @setStep="setActiveStep"
                                    @checkCoupon="checkCoupon"
                                    @getDeliveryInfo="getDeliveryInfo"
                                    :orderId="orderId"
                                    :offerte="offerte"
                                    :creoNum="creoNum"
                                    :orderDescription="orderDescription"
                                    :prices="prices"
                                    :products="products"
                                    v-if="step.isActive && step.value === 1"
                                    :key="reRenderKey"
                />

                <payment-flow-step2 class="payment-flow__steps_item__step-2" @setStep="setActiveStep"
                                    @getUserInCart="getInfoUserForCart"
                                    :orderId="orderId"
                                    :user="user"
                                    :offerte="offerte"
                                    v-if="step.isActive && step.value === 2"/>
                <payment-flow-step3 @setStep="setActiveStep"
                                    @getDeliveryInfo="getDeliveryInfo"
                                    :orderId="orderId"
                                    :user="user"
                                    :prices="prices"
                                    :deliveryInfo="deliveryInfo"
                                    :products="products"
                                    :offerte="offerte"
                                    v-if="step.isActive && step.value === 3"/>
                <payment-flow-step4 @setStep="setActiveStep"
                                    :orderId="orderId"
                                    :user="user"
                                    :prices="prices"
                                    :orderDescription="orderDescription"
                                    :products="products"
                                    :offerte="offerte"
                                    v-if="step.isActive && step.value === 4"/>
                <payment-flow-step5 @setStep="setActiveStep"
                                    :orderDescription="orderDescription"
                                    :orderId="orderId"
                                    :creoNum="creoNum"
                                    :prices="prices"
                                    :user="user"
                                    :products="products"
                                    v-if="step.isActive && step.value === 5"/>
            </template>
        </div>
        <popup-message-order @setStep="setActiveStep"/>
    </div>
</template>

<script>
import {setTheme} from '../../plugins/dark-mode'
import {mapGetters, mapActions} from 'vuex'
import PaymentFlowStep1 from "../../components/payment-flow/PaymentFlowStep1";
import PaymentFlowStep2 from "../../components/payment-flow/PaymentFlowStep2";
import PaymentFlowStep3 from "../../components/payment-flow/PaymentFlowStep3";
import PaymentFlowStep4 from "../../components/payment-flow/PaymentFlowStep4";
import PaymentFlowStep5 from "../../components/payment-flow/PaymentFlowStep5";
import PopupMessageOrder from "../popup/popupMessageOrder";
import PaymentFlowStepCancel from "./PaymentFlowStepCancel";

export default {
    name: "PaymentFlow",
    components: {
        PaymentFlowStepCancel,
        PopupMessageOrder,
        PaymentFlowStep5,
        PaymentFlowStep4,
        PaymentFlowStep3,
        PaymentFlowStep2,
        PaymentFlowStep1,
    },
    data() {
        return {
            reRenderKey: 0,
            orderId: 0,
            creoNum: 0,
            orderType: '',
            products: [],
            steps: [
                {
                    name: 'Stap 1: uw winkelwagen',
                    value: 1,
                    isActive: true,
                    passed: true,
                },
                {
                    name: 'Stap 2: uw gegevens',
                    value: 2,
                    isActive: false,
                    passed: false,
                },
                {
                    name: 'Stap 3: aflevering',
                    value: 3,
                    isActive: false,
                    passed: false,
                },
                {
                    name: 'Stap 4: betaling',
                    value: 4,
                    isActive: false,
                    passed: false,
                },
                {
                    name: 'Stap 5: bevestiging',
                    value: 5,
                    isActive: false,
                    passed: false,
                },
            ],
            user: {},
            orderDescription: '',
            deliveryInfo: {},
            prices: {},
            coupon: '',
            offerte: false,
        }
    },
    created() {
        Echo.channel('order').listen('MessageOrder', (data) => {

            console.log(" MessageOrder MessageOrder MessageOrder")
            console.log(data)

            if (data.orderId == this.orderId) {
                this.$root.$emit('popupMessageOrder', data.data)
            }
        })

        Echo.channel('customer').listen('UpdateCustomer', (data) => {
            if (this.customerId() == data.customerId) {
                this.checkStepOrder()
            }
        })
    },
    mounted() {
        this.$root.$on('getProductCount', () => {
            this.getProductCount()
        })
        this.$root.$on('getProductsInCart', () => {
            this.getProductsInCart()
        })
        this.$root.$on('deleteProductWithCart', (product) => {
            this.deleteProduct(product)
        });
        this.$root.$on('deleteAllProductInCart', () => {
            this.deleteProducts()
        });

        // this.$root.$on('changeQuantityProduct', (data) => {
        //     this.checkEditQuantity(data)
        // })

        this.$root.$on('UpdateOrderUser', (data) => {
            this.getCartId()
        })

        this.checkStepOrder()
    },
    methods: {
        ...mapActions([
            'SET_CART',
            'SET_RE_RENDER_KEY',
        ]),
        addChannelOrder() {
            Echo.channel('order-' + this.orderId).listen('UpdateOrder', (data) => {
                this.getProductsInCart()
            })

            console.log('channel  = ' + 'order-' + this.orderId)
            Echo.channel('order-' + this.orderId).listen('ChangeOrderStatusPay', (data) => {
                console.log(" data ChangeOrderStatusPay data")
                console.log(data)
                window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/payment-flow?step=5&order=" + this.orderId
            })
        },
        checkEditQuantity(data) {
            this.products.forEach((product) => {
                if (product.productId == data.productId && product.quantity > data.quantity) {
                    this.getProductsInCart()
                }

                if (product.config != undefined && product.config.length > 0) {
                    product.config.forEach((config) => {
                        if (config.productId == data.productId && config.quantity * product.quantity > data.quantity) {
                            this.getProductsInCart()
                        }
                    })
                }
            });
        },
        async checkStepOrder() {
            await this.checkUser()
        },
        async getInfoForStep() {

            if (this.$route.query?.step === 'cancel') {
                this.orderType = 'cancel'
            } else {
                await this.setActiveStep(5)
            }
            await this.getProductsInCart()
        },
        checkUser() {
            if (this.$route.query.step !== undefined && this.$route.query.order !== undefined) {
                axios.post('/cart/user/check', {
                    order: this.$route.query.order,
                    step: this.$route.query.step,
                }).then((response) => {
                    if (response.data.orderId) {
                        this.orderId = response.data.orderId
                        this.creoNum = response.data.creoNum
                        this.coupon = response.data.coupon

                        this.getInfoForStep()
                    } else {
                        this.getCartId()
                    }
                })
            } else {
                this.getCartId()
            }
        },
        checkCoupon(coupon) {
            this.coupon = coupon
            axios.post(`/cart/prices`, {
                orderId: this.orderId,
                step: this.getActiveStep(),
                coupon: coupon,
            }).then((response) => {
                this.prices = response.data
                if (response.data.coupon != undefined && response.data.coupon != '') {
                    this.coupon = response.data.coupon
                }
            }).catch((errors) => {
                console.log(errors)
            })
        },
        getProductCount() {
            axios.get(`/cart/prices?orderId=${this.orderId}&step=${this.getActiveStep()}`).then((response) => {
                this.prices = response.data
                if (response.data.coupon != undefined && response.data.coupon != '') {
                    this.coupon = response.data.coupon
                }
            }).catch((e) => {
                console.log(e)
            })
        },
        async deleteProduct(product) {
            axios.post('/del/cart/product', {
                product: product,
                frame: this.$route.path.indexOf('frame') > -1
            }).then((response) => {
                this.getProductsInCart()
            })
        },
        async deleteProducts() {
            if (this.orderId) {
                await axios.delete(`/cart/products/${this.orderId}`)
                await this.getProductsInCart()
                await this.setActiveStep()
            }
        },
        setActiveStep($default = 1) {
            let $passed = true
            this.steps.forEach((step) => {
                step.passed = $passed
                step.isActive = false
                if (step.value == $default) {
                    step.isActive = true
                    $passed = false
                }
            })
        },
        getActiveStep() {
            let active = 1
            this.steps.forEach((step) => {
                if (step.isActive) {
                    active = step.value
                }
            })
            return active
        },
        getCartId() {
            if (this.orderId == 0) {
                axios.post(`/cart/id`, {
                    frame: this.$route.path.indexOf('frame') > -1,
                    orderId: this.$route.query.orderId,
                    type: this.$route.query.type
                }).then((response) => {

                    console.log("orderId result orderId")
                    console.log(response.data)

                    if (response.data.product) {
                        let message = 'Je kunt het niet accepteren. Producten die momenteel niet op voorraad zijn:'
                        response.data.product.forEach(item => {
                            message += ' ' + item.name + ';'
                        });

                        this.$root.$emit('popupMessages', message)
                    } else {

                        if (response.data.offerte != undefined) {
                            this.orderId = response.data.orderId
                            this.offerte = true
                            this.creoNum = response.data.creoNum
                        } else {
                            this.orderId = response.data
                        }
                        this.addChannelOrder()
                        this.getDeliveryInfo()
                        this.getProductsInCart()
                        this.getProductCount()
                        this.getInfoUserForCart()
                    }
                })
            } else {
                this.getProductsInCart()
            }
        },
        getProductsInCart() {
            if (this.orderId > 0) {
                axios.get(`/cart/orders/${this.orderId}?frame=${this.$route.path.indexOf('frame') > -1}`)
                        .then((response) => {
                            if (response.data.length > 0) {
                                this.products = response.data
                            } else {
                                this.products = []
                            }

                            ++this.reRenderKey
                            // this.SET_CART(this.products);
                            this.SET_RE_RENDER_KEY();
                            this.getProductCount()
                        })
            }
        },
        getInfoUserForCart() {
            axios.post('/get/cart/customer', {
                orderId: this.orderId,
            }).then((response) => {
                this.user = response.data
            })
        },

        getDeliveryInfo() {
            if (this.orderId) {
                axios.get(`/cart/delivery/${this.orderId}`).then((response) => {
                    this.deliveryInfo = response.data
                    if (typeof this.deliveryInfo !== 'undefined') {
                        this.orderDescription = this.deliveryInfo.description
                    }
                })
            }
        }
    },
    computed: {
        ...mapGetters([
            'GET_CART'
        ]),
        cart() {
            return this.GET_CART;
        },
    },
    watch: {
        cart: {
            deep: true,
            handler() {
                // if (this.$route.query.step == undefined && this.$route.query.order == undefined) {
                //     this.setActiveStep(1)
                // }
            }
        },
    },
    destroyed() {
        Echo.channel('order').stopListening('MessageOrder')
        Echo.channel('customer').stopListening('UpdateCustomer')
        Echo.channel('order-' + this.orderId).stopListening('UpdateOrder')
        Echo.channel('order-' + this.orderId).stopListening('ChangeOrderStatusPay')

        // this.$root.$off('changeQuantityProduct')
        this.$root.$off('getProductCount')
        this.$root.$off('getProductsInCart')
        this.$root.$off('deleteProductWithCart')
        this.$root.$off('deleteAllProductInCart')
        this.$root.$off('UpdateOrderUser')
    }
}
</script>
