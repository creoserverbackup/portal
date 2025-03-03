<template>
    <div class="row d-flex-row-between m-0">
        <div class="col-sm-12 col-lg-7 m-0 p-0">
            <div class="">
                <div class="ta-c mt-100 d-f-column">

                    <div class=""><img src="images/exclamation.png"></div>
                    <div class="c-red-light mt-4">
                        <span class="fs-30 fw-bold">Helaas, uw betaling is niet geluk!</span><br/>
                    </div>
                </div>
                <div class="c-red-light fs-16 mt-50 mb-20">
                    <p>Helaas is uw betaling voor uw bestelling niet bij ons binnengekomen. De meest voorkomende reden
                    hiervoor is dat uw bankrekening of creditcard een limiet heeft ingesteld, en dat het bedrag van uw
                    bestelling boven dit limiet uitkomt. Controleer alstublieft eerst de instellingen van uw bankrekening.
                    </p>
                    <p>Mocht dit niet het geval zijn, neem dan contact met ons op.</p>
                    <br/>

                </div>
                <div class="fs-16 b-primary p-10 mt-5">
                    <span>
						<span class="c-primary">LETOP!: </span>Vanaf het moment dat uw order is verzonden is het niet meer mogelijk om het afleveradres aan te passen.<br/>
						Indien u constateert dat het afleveradres op uw orderbevestiging niet klopt of dat er tijdens kantooruren niemand aanwezig is op het adres, dan verzoeken wij u contact op te nemen met onze klantenservice.
					</span>
                </div>
            </div>
        </div>
        <div class="step-5-right-block">
            <div class="b-primary mb-3">
                <div class="pl-3 pr-3 pt-2 pb-2 fs-16">
                    <div class="d-flex">
                        <div class="w-45 c-black">Uw ordernummer:</div>
                        <div class="w-55 c-secondary">{{ creoNum }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-45 c-black">Aantal artikelen</div>
                        <div class="w-55 c-secondary">{{ prices.productCount }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-45 c-black">Gekozen betaling</div>
                        <div class="w-55 c-secondary">{{ prices.payMethod }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-45 c-black">Kortingen:</div>
                        <div class="w-55 c-secondary">{{ prices.personalDiscount }}% status
                            korting euro {{
                                prices.priceDiscountCoupon
                            }} coupon korting
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="w-45 c-black">Aflever locatie:</div>
                        <div class="w-55 c-secondary">{{ getTypeDeliveryText() }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-45 c-black">Aflever optie:</div>
                        <div class="w-55 c-secondary">{{ getTypeDeliveryOption() }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-45 c-black">Uw opmerking(en):</div>
                    </div>
                </div>
                <div class="pl-3 pr-3 pt-2 pb-2">
                        <textarea type="text" class="cart-s5__textarea h-100px"
                                  disabled v-if="prices.delivery == undefined"
                                  placeholder="">
                        </textarea>
                    <textarea type="text" class="cart-s5__textarea h-100px"
                              v-else
                              v-model="prices.delivery.description"
                              disabled>
                        </textarea>
                </div>
                <div class="bg-primary fs-14 c-white pr-3 pl-3">
                    <div class="d-flex-row-between l-h-18">
                        <span>BTW tarief</span>
                        <span>{{ prices.nds }}%</span>
                    </div>
                    <div class="d-flex-row-between l-h-18">
                        <span>BTW bedrag:</span>
                        <span>{{ checkPrice(prices.priceFullNDS) }}</span>
                    </div>
                    <div class="d-flex-row-between l-h-18">
                        <span>Total exclusief btw:</span>
                        <span>{{ checkPrice(prices.priceFullDiscountAfter) }}</span>
                    </div>
                    <div class="d-flex-row-between l-h-18">
                        <span>Totaal te betalen:</span>
                        <span>{{ checkPrice(prices.priceFull) }}</span>
                    </div>
                </div>
            </div>
            <router-link to="/catalog" class="mt-3">
                <button class="cart-s5__btn">Terug naar het dashboard</button>
            </router-link>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper.js"
import {mapActions, mapGetters} from 'vuex'

export default {
    name: 'PaymentFlowStepCancel',
    props: {
        products: {
            type: Array,
            required: true
        },
        orderId: {
            type: Number,
            required: true
        },
        user: {
            type: Object,
            required: true
        },
        prices: {
            type: Object,
        },
        creoNum: ''
    },
    data() {
        return {
            isIdealActive: 0,
            isCardsActive: 0,
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false)
        this.$root.$emit('NewLifeLineCustomer')
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        nextStep(step = 1) {
            this.$root.$emit('setStep', step)
        },
        getTypeDeliveryText() {
            let text = ''
            if (this.prices.delivery != undefined) {
                this.typeTextDelivery.forEach((type) => {
                    if (type.value === this.prices.delivery.type) {
                        text = type.text
                    }
                })
            }
            return text
        },
        getTypeDeliveryOption() {
            let text = ''
            if (this.prices.delivery != undefined) {
                this.typeTextDelivery.forEach((type) => {
                    if (type.value === this.prices.delivery.type) {
                        text = type.option
                    }
                })
            }
            return text
        },
    },
    computed: {
        ...mapGetters([
            'GET_TYPE_TEXT_DELIVERY',
        ]),
        typeTextDelivery() {
            return this.GET_TYPE_TEXT_DELIVERY
        }
    },
}
</script>
