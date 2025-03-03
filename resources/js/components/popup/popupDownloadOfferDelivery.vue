<template>
    <div class="popup-message z-100" v-if="product || cart" v-bind:class="{ 'popup-message__open': product || cart}">
        <div class="popup-message__inner min-w-95 fs-16 mt-15-percent">
            <div class="popup-message__close top-0 right-0" @click="close">
                <img src="/images/close.svg" alt="X">
            </div>

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

                <div class="row max-h-160 m-0 p-0 w-100">

                    <p class="fw-500">
                        <span class="c-red">Letop!:</span> In deze stap kunt u een <span
                            class="td-underline">ander aflever adres</span>
                        invoeren. U kunt dit doen door de gewenste invul velden te
                        selecteren, en de overgenomen standaard adresgegevens van de factuur te vervangen naar het
                        gewenste aflever
                        adres.
                    </p>

                    <div class="col-4 p-0 m-0 b-primary fs-13">
                        <ul class="d-grid h-100">
                            <li class="p-4-10" v-for="item in paymentEdit"
                                :class="[
                                {'c-white': checkboxes.includes(item.id)},
                                {'bg-success': checkboxes.includes(item.id)},
                                {'bg-secondary': item.id === 'quickly'},
                                {'c-white': item.id === 'quickly'},
                        ]">
                                <label class="d-flex m-0" :for="item.id">
                                    <input class="w-auto mr-2 mt-1"
                                           v-bind:disabled="checkSelectType(item.id)"
                                           type="checkbox" v-model="checkboxes" :id="item.id" :value="item.id">
                                    {{ getTextType(item.id) }}</label>
                            </li>
                        </ul>
                    </div>
                    <div class="col-4 pl-2 pr-2 payment-flow__steps_item__step-3__bottom__disclaimer__container fs-13">
                        <div class="p-2-10 b-primary max-h-160 o-auto scroll payment-flow__steps_item__step-3__bottom__disclaimer h-100">
                            <div class="c-primary mb-1">Shipment disclaimer</div>
                            <div>Bij CreoServer doen wij onze uiterste best om uw order zo snel
                                mogelijk bij u te krijgen. Echter kunnen wij nooit garantie geven op lever datum of
                                lever tijd
                                van uw order. Dit kan te maken hebben met het transport bedrijf waar we mee werken, Of
                                door
                                andere onvoorziende omstandigheden buiten onze macht. Wanneer u de order komt afhalen
                                neemt u
                                dan alstublieft legetimatie mee. wij kunnen hier om vragen. Als u de order niet zelf
                                afhaalt,
                                laat dit ons dan weten. Zonder deze kennis geven wij GEEN producten mee aan derden.
                            </div>
                        </div>
                    </div>
                    <div class="col-4 p-0 m-0 d-flex-column-between ">
                        <div class="bg-primary p-2 c-white fs-14">
                            <div class="d-flex pt-1">
                                <div class="min-w-45">Transport keuze:</div>
                                <div v-html="titleDelivery"></div>
                            </div>
                            <div class="d-flex pt-1 mt-50">
                                <div class="min-w-45">Extra transport kosten:</div>
                                <div>{{ checkPrice(titlePriceDelivery) }}</div>
                            </div>
                        </div>
                        <div class="mt-1px">
                            <div class="d-flex-row-between w-100 payment-flow__steps_item__step-3__bottom__inputs">
                                <input class="c-white bg-primary b-none" type="button"
                                       v-on:click="close()" value="Annuleren">
                                <input class="c-white bg-primary b-none ml-3"
                                       v-if="product === ''"
                                       v-bind:disabled="!selectDelivery"
                                       type="button" v-on:click="downloadOfferCart()" value="Offerte nu aanmaken">

                                <input class="c-white bg-primary b-none ml-3"
                                       v-else
                                       v-bind:disabled="!selectDelivery"
                                       type="button" v-on:click="downloadOffer()" value="Offerte nu aanmaken">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup-message__background" @click="close"></div>
    </div>
</template>

<script>
import {mapActions} from "vuex";
import PaymentFlowStep3Bottom from "../payment-flow/PaymentFlowStep3Bottom";
import TypeDeliveryPickup from "../payment-flow/TypeDeliveryPickup";
import TypeDelivery from "../payment-flow/TypeDelivery";
import {getMessageError} from "../../utils";
import {checkPriceHelper} from "../../helper";

export default {
    name: "popupDownloadOfferDelivery",
    components: {
        PaymentFlowStep3Bottom,
        TypeDeliveryPickup,
        TypeDelivery

    },
    data() {
        return {
            deliveryInfo: '',
            product: '',
            cart: '',

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
            viewError: true,
            paymentEdit: [
                {
                    id: 'quickly',
                    checked: false,
                },
                {
                    id: 'weekday',
                    checked: false,
                },
                {
                    id: 'weekend',
                    checked: false,
                },
                {
                    id: 'neighbour',
                    checked: false,
                },
            ],
        }
    },
    mounted() {
        this.$root.$on('popupDownloadOfferDelivery', (data) => {
            this.product = '';

            let cart = ''

            if (data.cart != undefined) {
                cart = data.cart;
            }

            this.checkDelivery(data.product, cart)
        })
    },

    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
        ]),

        async checkDelivery(product = '', cart = '') {
            let products = ''

            if (product) {
                products = [product]
            }

            this.GET_LOADING_FROM_REQUEST(true);
            axios.post(`/cart/delivery/offer`, {
                product: products,
            }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.permission = response.data.permission
                this.deliveryInfo = response.data.deliveryInfo
                this.settings = response.data.settings
                this.product = product
                this.cart = cart

                this.getCountry()
                this.setData()
                this.checkDeliveryInfo()

            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },

        setData() {
            // this.dropShip.namens = this.user.customerName
            // this.zoneEU.namens = this.user.customerName
            // this.zoneEU.country = this.user.country
            // this.zoneEU.region = this.user.region
            // this.dropShip.country = this.user.country
            // this.dropShip.region = this.user.region
            // this.userLaptop = this.user

            this.types.server.priceDelivery = +this.settings.postUK
            this.types.laptop.priceDelivery = +this.settings.transport
            this.types.zoneEU.priceDelivery = +this.settings.postForeign
            this.types.dropShip.priceDelivery = +this.settings.dropShip
            this.extraPrice = +this.settings.quickly

        },

        downloadOffer() {
            this.GET_LOADING_FROM_REQUEST(true);
            axios.post('/document/offer', {
                        product: this.product,
                        checkboxes: this.checkboxes,
                        formDelivery: this.form,
                        deliveryType: this.type,
                    },
                    {
                        responseType: 'blob'
                    }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');

                const fileName = 'Offerte.pdf'

                link.href = url;
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                this.GET_LOADING_FROM_REQUEST(false);

                this.close()
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },

        async downloadOfferCart() {
            this.GET_LOADING_FROM_REQUEST(true);
            await axios.post('/cart/download/document', {
                        orderId: 0,
                        checkboxes: this.checkboxes,
                        formDelivery: this.form,
                        deliveryType: this.type,
                    },
                    {
                        responseType: 'blob'
                    })
                    .then((response) => {
                        this.GET_LOADING_FROM_REQUEST(false);

                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        // const fileName = response.headers.name + '.pdf';
                        let fileName = 'Offerte.pdf';

                        link.href = url;
                        link.setAttribute('download', fileName);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        this.close()

                    }).catch((e) => {
                        this.GET_LOADING_FROM_REQUEST(false);
                        console.log(e)
                    })
        },
        close() {
            this.product = ''
            this.cart = ''
        },

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
                this.country.selected = this.deliveryInfo.country
            }).catch((e) => {
                console.log(e)
            })
        },
        setStartCheckBox() {
            this.checkboxes = []
            for (let key in this.deliveryInfo) {
                this.paymentEdit.forEach(edit => {
                    if (edit.id == key && this.deliveryInfo[key] == 1) {
                        this.checkboxes.push(edit.id)
                    }
                })
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

                if (this.deliveryInfo.permission.includes(this.deliveryInfo.delivery)) {
                    this.checkTypeDelivery(this.deliveryInfo.delivery)
                } else if (this.deliveryInfo.permission.includes(1)) {
                    this.checkTypeDelivery(1)
                } else {
                    this.checkTypeDelivery(2)
                }
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

            if (this.checkboxes != undefined) {
                if (this.checkboxes.includes('quickly')) {
                    this.titlePriceDelivery += this.extraPrice
                }
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
            this.setStartCheckBox()
            this.checkTypes()
            this.checkBoxQuickly()
        },
        checkTypes() {
            // for (let number in this.types) {
            //     if (this.types[number].selected && this.types[number].type == 3) {
            //         this.checkboxes = []
            //     }
            // }
        },

        setCheckboxes(checkboxes) {
            // this.checkboxes = checkboxes
            this.getPriceDelivery()
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        getTextType(type) {
            let arr = new Map([
                ['weekday', 'Ik ben tijdens alle werkdagen beschikbaar'],
                ['weekend', 'Ik ben ook in de weekenden bereikbaar'],
                ['neighbour', 'Mijn leveringen mogen niet bij de buren worden afgeleverd.'],
                ['quickly', 'Dit is een spoedopdracht in schriftelijk overleg met Creoserver. Extra kosten bedragen €' + this.extraPrice],
            ])
            return arr.get(type)
        },

    },
    watch: {
        checkboxes: {
            deep: true,
            handler() {
                this.setCheckboxes()
            }
        },
        // types: {
        //     deep: true,
        //     handler() {
        //         this.checkTypes()
        //     }
        // },
    }
}
</script>

<style scoped>

</style>