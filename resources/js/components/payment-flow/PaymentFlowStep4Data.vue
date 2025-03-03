<template>
    <div>
        <div class="b-primary">

            <div class="m-0 p-2 bg-white">
            <textarea type="text" class="cart-s4__textarea h-100px"
                      v-model="orderDescription"
                      disabled
                      placeholder="als het kan de order versturen voor de 23e van agustus">
            </textarea>
                <div class="d-flex-row-between" v-if="!offerte">
                    <button class="cart-data__download-btn" v-on:click="downloadDocument()">
                        <i class="icon-download mr-2" aria-hidden="true"></i>
                        Offerte downloaden
                    </button>
                    <button class="cart-data__print-btn" v-on:click="downloadDocument(true)">
                        <div class="cart-data__icon-print mr-2"></div>
                        Offerte Printen
                    </button>
                </div>
            </div>

            <div class="cart-s4__condition--checkbox mb-2">
                <div class="cart-s4__condition--checkbox__container" @click="mailing = !mailing">
                    <input type="checkbox" class="cart-s4__condition--checkbox" v-model="mailing">
                    <div>
                        Volg de vele IT proffesionals die al bij de Creoserver nieuwsbrief staan ingeschreven
                    </div>
                </div>
            </div>

            <div class="pl-3 pr-3 pt-2 c-white bg-primary fs-18">
                <div class="d-flex-row-between mb-3">
                    <span>Aantal bestelde artikelen</span>
                    <span>{{ prices.productCount }}</span>
                </div>
                <div class="d-flex-row-between" v-if="prices.personalDiscount">
                    <span>Uw CREO korting</span>
                    <span>{{ prices.personalDiscount }}%</span>
                </div>
                <div class="d-f-column fs-12 mb-3" v-if="prices.personalDiscount">
                    <span>korting bedrag {{
                            checkPrice(prices.priceFullDiscountBefore * (prices.personalDiscount / 100))
                        }}</span>
                </div>

                <div class="d-flex-row-between mb-3" v-if="prices.priceDiscountCoupon">
                    <span>Kortingscode</span>
                    <span>{{ checkPrice(prices.priceDiscountCoupon) }}</span>
                </div>
                <div class="d-flex-row-between">
                    <span>Transport kosten</span>
                    <span>{{ checkPrice(prices.priceDelivery) }}</span>
                </div>
                <div class="fs-12 pl-2 pb-3">Type: {{ getTypeDeliveryText() }}</div>
                <div class="d-flex-row-between">
                    <span>Admin. Kosten:</span>
                    <span>{{ checkPrice(prices.priceTransaction) }}</span>
                </div>

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
        <div class="cart-s4__condition--confirmation" :class="{'cart-s4__condition--confirmation--active': condition}"
             @click="handleChangeCondition">

            <input type="checkbox" class="cart-s4__condition--checkbox cart-s4__condition--checkbox--confirmation"
                   v-model="condition">
            <div class="cart-s4__condition--confirmation__title">
                Hiermee gaat u akkoord met de koop verplichting
                volgens onze
                <router-link to="/page/algemene_voorwaarden" class="text-primary">algemene voorwaarden
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper.js"
import {mapActions, mapGetters} from "vuex";
import {default as OrderType} from "../../data/orderType";

export default {
    name: "PaymentFlowStep4Data",
    props: {
        prices: {
            type: Object,
        },
        orderDescription: '',
        orderId: '',
        offerte: '',
    },
    data() {
        return {
            mailing: false,
            condition: false,
        }
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
            'SET_CART'
        ]),
        handleChangeCondition() {
            this.condition = !this.condition
            this.$emit('changeCondition', this.condition);
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        getTypeDeliveryText() {
            let text = ''
            this.typeTextDelivery.forEach((type) => {
                if (this.prices.delivery?.type != undefined && type.value === this.prices.delivery.type) {
                    text = type.text
                }
            })
            return text
        },
        async downloadDocument(print = false) {
            this.GET_LOADING_FROM_REQUEST(true);
            await axios.post('/cart/download/document',
                    {
                        orderId: this.orderId,
                    },
                    {
                        responseType: 'blob'
                    })
                    .then((response) => {
                        this.GET_LOADING_FROM_REQUEST(false);
                        if (print) {
                            const url = window.URL.createObjectURL(new Blob([response.data], {
                                type: "application/pdf;base64"
                            }));

                            let callbackLink = async function (url) {
                                let link = await document.createElement('iframe');
                                link.type = "application/pdf";
                                link.src = url;
                                link.style.visibility = '0';
                                link.style.position = 'absolute';
                                link.style.left = '-999999999px';
                                link.style.top = '-999999999px';
                                await document.body.appendChild(link);
                                await link.contentWindow.focus();
                                return link;
                            };

                            let link = callbackLink(url).then(function (link = link) {
                                link.contentWindow.focus();
                                link.contentWindow.print();

                                setTimeout(() => {
                                    document.body.removeChild(link);
                                }, 60000); // printing service is active only for 60s
                            });
                        } else {
                            const url = window.URL.createObjectURL(new Blob([response.data]));
                            const link = document.createElement('a');
                            // const fileName = response.headers.name + '.pdf';
                            let fileName = 'Offerte.pdf';

                            link.href = url;
                            link.setAttribute('download', fileName);
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    }).catch((e) => {
                        this.GET_LOADING_FROM_REQUEST(false);
                        console.log(e)
                    })
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

<style scoped>

</style>
