<template>
    <div>
        <div class="row mr-0 ml-0 mb-4 payment-flow__steps_item__step-3__wrapper">
            <type-delivery @checkTypeDelivery="checkTypeDelivery"
                           :user="deliveryInfo"
                           :types="types.server"
                           :available="deliveryInfo.permission.includes(1)"
                           :country="country"
                           :active="getTypeDelivery(1)"
                           :icon="'icon-delivery-post'"
            />
            <type-delivery @checkTypeDelivery="checkTypeDelivery"
                           :user="deliveryInfo"
                           :types="types.laptop"
                           :available="deliveryInfo.permission.includes(2)"
                           :country="country"
                           :active="getTypeDelivery(2)"
                           :icon="'icon-delivery-transport'"
            />
            <type-delivery-pickup @checkTypeDelivery="checkTypeDelivery"
                                  :user="deliveryInfo"
                                  :type="type"
                                  :deliveryInfo="deliveryInfo"
                                  :types="types.pickup"
                                  :active="getTypeDelivery(3)"/>
            <type-delivery @checkTypeDelivery="checkTypeDelivery"
                           :user="deliveryInfo"
                           :types="types.dropShip"
                           :available="deliveryInfo.permission.includes(4)"
                           :country="country"
                           :active="getTypeDelivery(4)"
                           :icon="'icon-delivery-dropship'"
            />
            <type-delivery @checkTypeDelivery="checkTypeDelivery"
                           :user="deliveryInfo"
                           :types="types.zoneEU"
                           :country="country"
                           :available="deliveryInfo.permission.includes(5)"
                           :active="getTypeDelivery(5)"
                           :icon="'icon-delivery-eu'"
            />
        </div>

        <payment-flow-step3-bottom class="payment-flow__steps_item__step-3__bottom"
                                   ref="bottom"
                                   :prices="prices"
                                   :extraPrice="extraPrice"
                                   :titleDelivery="titleDelivery"
                                   :titlePriceDelivery="titlePriceDelivery"
                                   :selectDelivery="selectDelivery"
                                   :deliveryInfo="deliveryInfo"
                                   :types="types"
                                   :offerte="offerte"
                                   @clearCart="clearCart"
                                   @checkBoxQuickly="checkBoxQuickly"
                                   @setCheckboxes="setCheckboxes"
                                   @nextStep="nextStep"
        />
    </div>
</template>

<script>
import TypeDelivery from "./TypeDelivery";
import TypeDeliveryPickup from "./TypeDeliveryPickup";
import PaymentFlowStep3Bottom from "./PaymentFlowStep3Bottom";
import {getMessageError} from "../../utils";

export default {
    name: 'PaymentFlowStep3',
    components: {
        PaymentFlowStep3Bottom,
        TypeDeliveryPickup,
        TypeDelivery

    },
    props: {
        products: {
            type: Array,
            required: true
        },
        user: {
            type: Object,
            required: true
        },
        deliveryInfo: {
            type: Object,
            required: true
        },
        orderId: {},
        prices: {
            type: Object,
        },
        offerte: '',
    },
    data() {
        return {
            userLaptop: {},
            selectDelivery: false,
            titleDelivery: '',
            priceDelivery: 0,
            titlePriceDelivery: 0,
            checkboxes: [],
            types: {
                server: {
                    type: 1,
                    title: 'Per post verzending',
                    subTitle: 'Eigen adres per post',
                    selected: false,
                    priceDelivery: 0
                },
                laptop: {
                    type: 2,
                    title: 'Eigen bezorging door Creoserver',
                    subTitle: 'Creo transport',
                    selected: false,
                    priceDelivery: 0
                },
                pickup: {
                    type: 3,
                    title: 'Zelf afhalen',
                    subTitle: 'Zelf afhalen',
                    selected: false,
                    priceDelivery: 0
                },
                dropShip: {
                    type: 4,
                    title: 'Dropshipping',
                    subTitle: 'Dropshipping',
                    selected: false,
                    priceDelivery: 0
                },
                zoneEU: {
                    type: 5,
                    title: 'Internationaal transport binnen EU',
                    subTitle: 'Buitenlandse verzending',
                    selected: false,
                    priceDelivery: 0
                },
            },
            dropShip: {
                customerName: '',
                username: '',
                namens: '',
                address: '',
                house: '',
                postcode: '',
                region: '',
                email: '',
                phone: '',
                country: '',
            },
            zoneEU: {
                customerName: '',
                username: '',
                namens: '',
                address: '',
                house: '',
                postcode: '',
                region: '',
                email: '',
                phone: '',
                country: '',
            },

            type: '',
            extraPrice: 0,
            form: {},
            country: {
                name: 'country',
                selected: '',
                placeholder: 'Field country',
                value: [],
            },
            viewError: true
        }
    },
    mounted() {
        this.getCountry()
        this.checkCouponDelivery()
        this.setData()
        this.checkDeliveryInfo()
    },
    methods: {
        checkSelectType(name) {
            let result = false
            for (let number in this.types) {
                if (this.types[number].selected && this.types[number].type == 3 && name != 'quickly') {
                    result = true
                }
            }
            return result
        },
        getCountry() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/countries`).then((response) => {
                this.country.value = response.data
                this.country.selected = this.user.country
            }).catch((e) => {
                console.log(e)
            })
        },
        checkCouponDelivery() {
            if (this.prices.couponType == 4) {
                this.types.laptop.priceDelivery = 0
            } else if (this.prices.couponType == 5) {
                this.types.server.priceDelivery = 0
            } else if (this.prices.couponType == 6) {
                this.extraPrice = 0
            }
        },
        setData() {
            this.dropShip.namens = this.user.customerName
            this.zoneEU.namens = this.user.customerName
            this.zoneEU.country = this.user.country
            this.zoneEU.region = this.user.region
            this.dropShip.country = this.user.country
            this.dropShip.region = this.user.region
            this.userLaptop = this.user

            this.types.server.priceDelivery = +this.prices.postUK
            this.types.laptop.priceDelivery = +this.prices.transport
            this.types.zoneEU.priceDelivery = +this.prices.postForeign
            this.types.dropShip.priceDelivery = +this.prices.dropShip
            this.extraPrice = +this.prices.quickly

            this.$refs.bottom.setStartCheckBox()
        },
        clearCart() {
            this.$root.$emit('deleteAllProductInCart')
        },
        nextStep(step = 1) {
            if (step === 4) {
                if (this.checkboxes.includes('quickly')) {
                    this.priceDelivery += this.extraPrice
                }

                this.$emit('priceDelivery', this.priceDelivery)

                axios.post('/cart/delivery', {
                    type: this.type,
                    orderId: this.orderId,
                    form: this.form,
                    checkboxes: this.checkboxes,
                }).then((response) => {
                    this.$emit('getDeliveryInfo')
                    this.$emit('setStep', step)
                }).catch((e) => {
                    this.$root.$emit('popupMessages', getMessageError(e))
                })
            } else {
                this.$emit('setStep', step)
            }
        },
        checkBoxQuickly() {

            for (let number in this.types) {
                if (this.types[number].selected && this.types[number].type == 3) {
                    let stack = []
                    for (let key in this.checkboxes) {
                        if (this.checkboxes[key] == 'quickly') {
                            stack.push(this.checkboxes[key])
                        }
                    }
                    this.checkboxes = stack
                }
            }

            this.getPriceDelivery()
        },
        getTypeDelivery(type) {
            let selected = false
            for (let key in this.types) {
                if (this.types[key].type === type) {
                    selected = this.types[key].selected
                }
            }
            return selected
        },
        checkDeliveryInfo() {
            this.viewError = false
            if (typeof this.deliveryInfo.delivery != undefined && this.deliveryInfo.delivery != null) {
                if (this.deliveryInfo.delivery === 4) {
                    this.dropShip = this.deliveryInfo
                }
                if (this.deliveryInfo.delivery === 5) {
                    this.zoneEU = this.deliveryInfo
                }

                this.checkTypeDelivery(this.deliveryInfo.delivery)
            } else {
                if (this.deliveryInfo.permission.includes(1)) {
                    this.checkTypeDelivery(1)
                } else {
                    this.checkTypeDelivery(2)
                }
            }
            this.viewError = true
        },
        checkTypeDelivery(type, form = {}) {
            if (this.deliveryInfo.permission.includes(type)) {
                this.form = form
                this.type = type

                this.setTypeDelivery(type)
                this.selectDelivery = true
            } else {
                if (this.viewError) {
                    this.$root.$emit('popupMessages', 'The selected delivery is not available')
                }
                this.checkTypeDelivery(3)
            }
        },
        getPriceDelivery() {
            this.titlePriceDelivery = this.priceDelivery
            if (this.checkboxes.includes('quickly')) {
                this.titlePriceDelivery += this.extraPrice
            }
        },
        setTypeDelivery(type) {
            this.viewError = true
            this.titleDelivery = ''
            this.priceDelivery = 0
            for (let key in this.types) {
                this.types[key].selected = this.types[key].type === type
                if (this.types[key].selected) {
                    this.titleDelivery = this.types[key].title
                    this.priceDelivery = this.titlePriceDelivery = this.types[key].priceDelivery
                }
            }
            this.$refs.bottom.setStartCheckBox()
            this.$refs.bottom.checkTypes()
            this.checkBoxQuickly()

        },
        setCheckboxes(checkboxes) {
            this.checkboxes = checkboxes
            this.checkBoxQuickly()
        }
    }
}
</script>
