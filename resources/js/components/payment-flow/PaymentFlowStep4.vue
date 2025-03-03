<template>
    <div class="row">
        <div class="d-flex col-md-8 col-sm-12 m-0 p-0 row">;
<!--            <div class="c-red w-100 ta-c mb-10 fs-18 fw-bold">-->
<!--                Dear customer, at this moment iDeal/Paypal payments doesn't work, you can only place order by using banktransfer or payment on credit.-->
<!--            </div>-->
            <div class="col-md-6 col-sm-12">
                <div class="pf-method-pay" v-bind:class="checkActivePay('ideal')">
                    <div class="pf-method-pay__body" @click="changeBodyHandler('ideal')">
                        <input class="w-auto mb-auto mr-15" type="checkbox" :checked="bankCheckbox == 'ideal'">
                        <img src="images/bank/ideal-bank.png" alt="ideal" class="pf-method-pay__logo">
                        <div class="pf-method-pay__title">Makkelijk, snel en vertrouwd betalen met ideal</div>
                    </div>
                    <div class="pf-method-pay__added">
                        <div class="pl-5">
                            <div class="pf-method-pay__radio-container">
                                <input type="radio" id="abnamro" class="pf-method-pay__radio"
                                       v-model="bankRadio" :value="'ideal_ABNANL2A'">
                                <label for="abnamro">
                                    <img src="images/bank/abnamro.png" alt="bank logotype">
                                </label>
                            </div>
                            <div class="pf-method-pay__radio-container">
                                <input type="radio" name="bank-top" id="ingbank" class="pf-method-pay__radio"
                                       v-model="bankRadio" :value="'ideal_INGBNL2A'" alt="bank logotype">
                                <label for="ingbank">
                                    <img src="images/bank/ingbank.png" alt="bank logotype">
                                </label>
                            </div>
                            <div class="pf-method-pay__radio-container">
                                <input type="radio" name="bank-top" id="rabobank" class="pf-method-pay__radio"
                                       v-model="bankRadio" :value="'ideal_RABONL2U'" alt="bank logotype">
                                <label for="rabobank">
                                    <img src="images/bank/rabobank.png">
                                </label>
                            </div>
                            <div class="pf-method-pay__radio-container">
                                <input type="radio" name="bank-top" id="sns" class="pf-method-pay__radio"
                                       v-model="bankRadio" :value="'ideal_SNSBNL2A'" alt="bank logotype">
                                <label for="sns">
                                    <img src="images/bank/sns.png" alt="bank logotype">
                                </label>
                            </div>
                        </div>
                        <select class="w-100" @change="changeBodyHandler('ideal')">
                            <option value="">Other banks</option>
                            <option v-for="bank in IdealBanks" v-bind:value="bank.id">{{ bank.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'creditcard'"
                                              :icon="'creditcard.jpg'"
                                              :rateBank="prices.ratesBank.creditcard.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'bancontact'"
                                              :icon="'mrcash.jpg'"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'belfius'"
                                              :icon="'belfius.jpg'"
                                              :rateBank="prices.ratesBank.belfius.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'kbc'"
                                              :icon="'kbc-logo.jpg'"
                                              :rateBank="prices.ratesBank.kbc.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'giropay'"
                                              :icon="'giro-pay.jpg'"
                                              :rateBank="prices.ratesBank.giropay.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'bancaires'"
                                              :icon="'bancaires.png'"
                                              :rateBank="prices.ratesBank.bancaires.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'postepay'"
                                              :icon="'postepay.png'"
                                              :rateBank="prices.ratesBank.postepay.priceTransaction"/>

            </div>
            <div class="col-md-6 col-sm-12">
                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'sofort'"
                                              :icon="'sofort.png'"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'eps'"
                                              :icon="'eps.png'"
                                              :rateBank="prices.ratesBank.eps.priceTransaction"/>


                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'paypal'"
                                              :icon="'paypal.jpg'"
                                              :rateBank="prices.ratesBank.paypal.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'applepay'"
                                              :icon="'applePay.png'"
                                              :rateBank="prices.ratesBank.applepay.priceTransaction"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'cashpickup'"
                                              :icon="'cash-pick-up.jpg'"/>

                <payment-flow-method-pay-bank @changeMethodPay="changeBodyHandler"
                                              :bankCheckbox="bankCheckbox"
                                              :nameBank="'banlogo'"
                                              :icon="'ban-logo.jpg'"/>

                <div class="pf-method-pay" v-if="canBuyAccount() == 1"
                     v-bind:class="checkActivePay('oprekening')">
                    <div class="pf-method-pay__body" @click="changeBodyHandler('oprekening')">
                        <input class="w-auto mb-auto mr-15" type="checkbox"
                               :checked="bankCheckbox == 'oprekening'">
                        <img src="images/bank/op-rekening.jpg" alt="" class="pf-method-pay__logo">
                        <div class="pf-method-pay__title">
                            Uw klantnummer heeft bij ons de mogelijkheid om op rekening te bestellen
                            <div><span class="red">Letop!:</span> informeer naar uw betalingstermijn</div>
                        </div>
                    </div>
                </div>

                <payment-flow-method-pay-qr-code :orderId="orderId" v-if="!offerte"/>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 cart-data cart-data-s4">
            <payment-flow-step4-data :prices="prices"
                                     :orderDescription="orderDescription"
                                     :orderId="orderId"
                                     :offerte="offerte"
                                     @changeCondition="changeCondition"
            />
            <div class="buttons d-flex mt-2">
                <button class="cart-s4__btn mr-1" v-on:click="clearCart()" v-if="!offerte">Annuleren</button>
                <button class="cart-s4__btn mr-1" v-on:click="nextStep(3)">Vorige</button>
                <button class="cart-s4__btn" v-bind:disabled="!condition" v-on:click="cartPay">Betalen</button>
            </div>

            <div class="c-secondary w-100 ta-c mt-25 fs-18 fw-bold">
                Je hebt 60 minuten om de bestelling af te ronden
            </div>

        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import PaymentFlowMethodPayBank from "./PaymentFlowMethodPayBank";
import PaymentFlowMethodPayQrCode from "./PaymentFlowMethodPayQrCode";
import PaymentFlowStep4Data from "./PaymentFlowStep4Data";

export default {
    name: 'PaymentFlowStep4',
    components: {
        PaymentFlowStep4Data,
        PaymentFlowMethodPayQrCode,
        PaymentFlowMethodPayBank
    },
    props: {
        products: '',
        orderId: '',
        orderDescription: '',
        user: '',
        prices: '',
        offerte: '',
    },
    data() {
        return {
            IdealBanks: [],
            qrCode: '',
            bankCheckbox: '',
            bankRadio: false,
            transactionPrice: 0,
            condition: false,
            time: null,
        }
    },
    mounted() {
        this.$root.$emit('getProductCount')
        this.setMethodPay()
        this.getIdealBankAndQR()
        this.GET_LOADING_FROM_REQUEST(false)

        this.time = setTimeout(() => this.timeOver(), 1000 * 3600);
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
            'SET_CART'
        ]),
        checkActivePay(namePay) {
            if (this.bankCheckbox != namePay && this.bankCheckbox != false) {
                return 'disabled'
            } else if (this.bankCheckbox == namePay) {
                return 'checked'
            }
        },
        changeBodyHandler(item) {
            this.bankRadio = false;
            if (item == this.bankCheckbox) {
                this.bankCheckbox = '';
            } else {
                this.bankCheckbox = item;
            }
            this.setMethodPay()
        },
        setMethodPay() {
            axios.post('/cart/pay/method', {
                orderId: this.orderId,
                method: this.bankCheckbox,
            }).then((response) => {
                this.$root.$emit('getProductCount')
            }).catch(function (e) {
                console.log(e)
            })
        },
        changeRadio() {
            this.bankRadio = false;
        },
        clearCart() {
            this.$root.$emit('deleteAllProductInCart')
        },
        nextStep(step = 1) {
            this.$emit('setStep', step)
        },
        cartPay() {
            let method = this.bankCheckbox

            if (method == 'cashpickup' || method == 'banlogo' || method == 'byUid' || method == 'oprekening') {
                this.cartPayCredit(method)
            } else if (this.bankCheckbox) {
                console.log("method " + method)
                this.GET_LOADING_FROM_REQUEST(true);
                axios.post('/cart/pay/bank', {
                    orderId: this.orderId,
                    method: method,
                    bankRadio: this.bankRadio,
                }).then((response) => {
                    this.GET_LOADING_FROM_REQUEST(false);
                    if (response.data === '') {
                        this.$root.$emit('popupMessages', 'This type of payment is currently unavailable')
                    } else {
                        this.SET_CART([]);
                        window.location.href = response.data;
                    }
                }).catch((e) => {
                    this.GET_LOADING_FROM_REQUEST(false);
                    console.log(e)
                })
            }
        },
        cartPayCredit(method) {
            this.GET_LOADING_FROM_REQUEST(true);
            axios.post('/cart/pay/credit', {
                orderId: this.orderId,
                method: method,
                orderDescription: this.orderDescription,
            }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                if (!response.data.error) {
                    this.SET_CART([]);
                    window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/payment-flow?step=5&order=" + response.data.orderId
                }
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },
        getIdealBankAndQR(qrcode = false) {
            axios.post(`/cart/pay/ideal-qrcode`, {
                orderId: this.orderId,
                qrcode: this.qrcode,
            }).then((response) => {
                this.IdealBanks = response.data.ideal
                // this.qrCode = response.data.src
            })
        },
        checkRadio() {
            if (this.bankRadio) {
                this.bankCheckbox = 'ideal'
            }
        },
        changeCondition(value) {
            this.condition = value
        },

        timeOver() {
            this.$root.$emit('popupMessageOrder', [{
                        message: 'De tijd doorgebracht op de betaalpagina is verlopen'
                    }]
            )
        }
    },
    watch: {
        bankRadio: {
            deep: true,
            handler() {
                this.checkRadio()
            }
        },
    },
    destroyed() {
        clearInterval(this.time)
    },
}
</script>
