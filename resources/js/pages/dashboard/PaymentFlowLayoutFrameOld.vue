<template>
    <div class="row p-0 m-0">
        <div class="col-4 p-0 payment-frame">
            <div class="dashboard-roof__search-form-frame">
                <payment-flow-step1-frame-customer @setUser="setUser"/>
            </div>
            <div class="payment-frame__user-info">
                <div class="payment-frame__user-info-block">
                    <div class="payment-frame__name-field">Klantnummer:</div>
                    <div class="payment-frame__value-field">{{ user.customerId }}</div>
                </div>
                <div class="payment-frame__user-info-block">
                    <div class="payment-frame__name-field">Bedrijfsnaam:</div>
                    <div class="payment-frame__value-field">{{ user.customerName }}</div>
                </div>
                <div class="payment-frame__user-info-block">
                    <div class="payment-frame__name-field">Adres:</div>
                    <div class="payment-frame__value-field">{{ user.address }}</div>
                </div>
                <div class="payment-frame__user-info-block">
                    <div class="payment-frame__name-field">nr:</div>
                    <div class="payment-frame__value-field">{{ user.house }}</div>
                </div>
                <div class="row p-0 mr-0 ml-0 payment-frame__user-info-block">
                    <div class="col-5 m-0 p-0 payment-frame__double-field">
                        <div class="payment-frame__name-field">{{ $t('SetupStepTwoMailboxPlaceholder') }}:</div>
                        <div class="payment-frame__value-field">{{ user.postcode }}</div>
                    </div>
                    <div class="col-5 m-0 p-0 payment-frame__double-field">
                        <div class="payment-frame__name-field">Woonplaats:</div>
                        <div class="payment-frame__value-field">{{ user.region }}</div>
                    </div>
                </div>
                <div class="row p-0 mr-0 ml-0 payment-frame__user-info-block">
                    <div class="col-5 m-0 p-0 payment-frame__double-field">
                        <div class="payment-frame__name-field">Postbus:</div>
                        <div class="payment-frame__value-field">{{ user.mailbox }}</div>
                    </div>
                    <div class="col-5 m-0 p-0 payment-frame__double-field">
                        <div class="payment-frame__name-field">Land:</div>
                        <div class="payment-frame__value-field">{{ user.country }}</div>
                    </div>
                </div>
                <div class="payment-frame__user-contact">
                    <div class="payment-frame__user-info-block">
                        <div class="payment-frame__name-field">Contact naam:</div>
                        <div class="payment-frame__value-field">{{ user.username }}</div>
                    </div>
                    <div class="payment-frame__user-info-block">
                        <div class="payment-frame__name-field">Email:</div>
                        <div class="payment-frame__value-field">{{ user.email }}</div>
                    </div>
                    <div class="payment-frame__user-info-block">
                        <div class="payment-frame__name-field">Tel:</div>
                        <div class="payment-frame__value-field">{{ user.phone }}</div>
                    </div>
                </div>
                <div class="payment-frame__user-contact">
                    <div class="payment-frame__user-info-block">
                        <div class="payment-frame__name-field">Bedrijfsvorm:</div>
                        <div class="payment-frame__value-field">{{ user.categoryName }}</div>
                    </div>
                    <div class="payment-frame__user-info-block">
                        <div class="payment-frame__name-field">Kvk nr:</div>
                        <div class="payment-frame__value-field">{{ user.kvk }}</div>
                    </div>
                    <div class="payment-frame__user-info-block">
                        <div class="payment-frame__name-field">BTW nr:</div>
                        <div class="payment-frame__value-field">{{ user.btw }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-7">
            <payment-flow-step1-frame
                    :orderId="orderId"
                    :customerId="user.customerId"
                    :uid="user.id"
                    :products="products"/>
        </div>
    </div>
</template>


<script>
import {setTheme} from '../../plugins/dark-mode'
import {mapGetters, mapActions} from 'vuex'
import PaymentFlowStep1Frame from "../../components/payment-flow/PaymentFlowStep1FrameOld";
import PaymentFlowStep1FrameCustomer from "../../components/payment-flow/PaymentFlowStep1FrameCustomer";

export default {
    components: {
        PaymentFlowStep1FrameCustomer,
        PaymentFlowStep1Frame
    },
    data() {
        return {
            customers: {},
            user: {},
            orderId: 0,
            products: [],
            prices: {},
        }
    },
    computed: {
        ...mapGetters([
            'GET_CART',
        ]),
        cart: function () {
            return this.GET_CART;
        },
    },
    watch: {
        cart: {
            deep: true,
            handler() {
                this.getProductsInCart()
            }
        },
    },
    mounted() {
        this.getCartId()

        this.getProductCount()
        this.$root.$on('getProductCount', () => {
            this.getProductCount()
        })

        setTheme(this.GET_THEME)
        this.GET_LOADING_FROM_REQUEST(false)
        this.$root.$on('deleteProductWithCart', (product) => {
            this.deleteProduct(product)
        });
        this.$root.$on('deleteAllProductInCart', () => {
            this.deleteProducts()
        });
    },
    methods: {
        ...mapActions([
            'GET_THEME_FROM_SWITCHER',
            'GET_LIFELINE_FROM_SWITCHER',
            'GET_LOADING_FROM_REQUEST',
            'REMOVE_PRODUCT_FROM_CART',
            'SET_CART'
        ]),
        getProductCount() {
            axios.get(`/cart/prices?orderId=${this.orderId}`).then((response) => {
                this.prices = response.data
            }).catch(function (e) {
                console.log(e)
            })
        },
        async deleteProduct(product) {
            await this.REMOVE_PRODUCT_FROM_CART({product: product, frame: this.$route.path.indexOf('frame') > -1});
            await this.getProductsInCart()
        },
        async deleteProducts() {
            await axios.delete(`/cart/products/${this.orderId}`)
            await this.getProductsInCart()
            await this.setActiveStep()
        },
        getCartId() {
            if (this.orderId === 0) {

                axios.get(`/cart/id?frame=${this.$route.path.indexOf('frame') > -1}`).then((response) => {
                    console.log(response.data)
                    this.orderId = response.data
                    this.getProductsInCart()
                })
            }
        },
        getProductsInCart() {
            this.isDefault = true
            if (this.orderId > 0) {
                axios.get(`/cart/orders/${this.orderId}?frame=${this.$route.path.indexOf('frame') > -1}`).then((response) => {
                    if (response.data.length > 0) {
                        this.products = response.data
                        this.isDefault = false;
                    } else {
                        this.products = []
                    }
                    setTimeout(() => {
                        this.$root.$emit('getProductCount')
                    }, 2000);
                })
            }
        },
        setUser(user) {
            this.user = user
        }
    }
}
</script>
