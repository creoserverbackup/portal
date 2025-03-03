<template>
    <div class="pf-frame">
        <div class="row p-0 m-0">
            <div class="col-7 p-0">
                <payment-flow-step1-frame-products :orderId="orderId" :products="products"/>
            </div>
            <div class="col-5 p-0 m-0 payment-frame">

                <payment-flow-step1-frame-customer ref="formsCustomerInfo"
                                                   :customers="customers"
                                                   :customer="customer"
                                                   :prices="prices"
                                                   @startSearchCustomer="startSearchCustomer"
                                                   @startSearchCoupon="startSearchCoupon"
                                                   @setData="setData"
                                                   @clickButton="clickButton"
                />
            </div>
        </div>
    </div>
</template>

<script>

import {mapActions, mapGetters} from "vuex";
import {setTheme} from "../../plugins/dark-mode";
import PaymentFlowStep1FrameProducts from "./PaymentFlowStep1FrameProducts";
import PaymentFlowStep1FrameCustomer from "./PaymentFlowStep1FrameCustomer";
import {default as OrderType} from "../../data/orderType";

export default {
    name: "PaymentFlowStep1Frame",
    components: {
        PaymentFlowStep1FrameCustomer,
        PaymentFlowStep1FrameProducts,
    },
    data() {
        return {
            customers: {},
            customer: {},
            orderId: 0,
            products: [],
            prices: {},
            coupon: '',
            date: '',
            nds: '',
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
            'GET_LOADING_FROM_REQUEST',
            'REMOVE_PRODUCT_FROM_CART',
            'SET_CART'
        ]),
        getProductCount() {
            axios.get(`/cart/prices?orderId=${this.orderId}`).then((response) => {
                this.prices = response.data

                if (response.data.coupon != undefined && response.data.coupon != '') {
                    this.coupon = response.data.coupon
                }
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
            if (this.orderId > 0) {
                axios.get(`/cart/orders/${this.orderId}?frame=${this.$route.path.indexOf('frame') > -1}`).then((response) => {
                    if (response.data.length > 0) {
                        this.products = response.data
                    } else {
                        this.products = []
                    }
                    setTimeout(() => {
                        this.$root.$emit('getProductCount')
                    }, 2000);
                })
            }
        },
        startSearchCustomer(searchWord) {
            this.customers = ''
            this.customer = ''
            let search = '!' + searchWord + '!'
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/frame/customer/search/${search}`).then((response) => {
                if (response.data.length == 1) {
                    this.customer = response.data[0]
                }
                this.customers = response.data
            });
        },
        startSearchCoupon(coupon) {
            this.coupon = coupon
            this.getPrices()
        },
        setData(data) {
            this.date = data.date
            this.nds = data.nds
            this.getPrices()
        },
        getPrices() {
            axios.post(`/cart/prices`, {
                orderId: this.orderId,
                step: 1,
                coupon: this.coupon,
                date: this.date,
                nds: this.nds,
            }).then((response) => {
                this.prices = response.data
                if (response.data.coupon != undefined && response.data.coupon != '') {
                    this.coupon = response.data.coupon
                }
            }).catch((errors) => {
                console.log(errors)
            })
        },
        clickButton(type) {
            switch (type) {
                case 'cancel':
                    this.popupCancel()
                    break;
                case 'offerte_print':
                    this.downloadDocument()
                    break;
                case 'offerte':
                    this.popupSendOfferte()
                    break;
                case 'product':
                    this.popupSendProduct()
                    break;
                default :
                    return '';
            }
        },
        popupCancel() {
            this.$root.$emit('popupOkOrNo', {
                message: 'Weet u zeker dat u deze samenstelling wilt annuleren?',
                callback: this.cancelOrder
            })
        },
        popupSendOfferte() {
            this.$root.$emit('popupOkOrNo', {
                message: 'Deze order toevoegen als offerte in de customer portal van de klant?',
                callback: this.sendOfferte
            })
        },
        popupSendProduct() {
            this.$root.$emit('popupOkOrNo', {
                message: 'Deze order toevoegen in het winkelmandje in de customer portal van de klant?',
                callback: this.sendProduct
            })
        },
        cancelOrder() {
            this.$root.$emit('deleteAllProductInCart')
        },
        sendOfferte() {
            this.sendCart('offerte')
        },
        sendProduct() {
            this.sendCart('product')
        },
        sendCart(type) {
            if (this.customer.customerId) {
                this.GET_LOADING_FROM_REQUEST(true);
                axios.post('/cart/frame', {
                    customerId: this.customer.customerId,
                    type: type,
                    frame: this.$route.path.indexOf('frame') > -1,
                    customer: this.customer,
                }).then((response) => {
                    this.GET_LOADING_FROM_REQUEST(false);
                    this.$root.$emit('popupMessages', 'Success')
                    this.$refs.formsCustomerInfo.clearForms()
                    if (type == 'product') {
                        window.parent.postMessage("reload", "*");
                    }
                }).catch((e) => {
                    this.GET_LOADING_FROM_REQUEST(false);
                    console.log(e)
                })
            }
        },
        downloadDocument() {

            this.GET_LOADING_FROM_REQUEST(true);
            axios.post('/cart/download/document', {
                        orderId: this.orderId,
                        frame: this.$route.path.indexOf('frame') > -1,
                        customer: this.customer,
                    },
                    {
                        responseType: 'blob'
                    }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
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
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },
    }
}
</script>


<style lang="scss">

@import "resources/sass/abstracts/variables";


.pf-frame {
    &__products_list {
        overflow-x: hidden;
        overflow-y: auto;
        flex-grow: 1;
        height: 900px;
    }

    .cart-product__price-data {
        display: flex;
        flex-direction: column;

        .fs-24 {
            font-size: 20px !important;
        }

        .fs-14 {
            font-size: 12px !important;
        }
    }
}

.pf-frame-info {

    &-title {
        font-size: 16px;
        margin: 15px 0;
    }

    &-block {
        border: 1px solid $grayMain;
        padding: 15px;

        &-left {
            border-right: 1px solid $grayMain;
        }

        &-right {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        &-label {
            font-size: 13px;
            color: $primary-color;
        }

        &-value {
            font-size: 13px;
            color: $black;
        }
    }

    &-block-title {
        display: flex;
        flex-direction: row;
        justify-content: space-between;

        &-right {
            font-size: 11px;
            margin: 15px 0;
            color: $secondary-color;
            text-decoration: underline;
            cursor: pointer;
            &:hover {
                animation: none;
                text-decoration: none;
            }
        }
    }


    &-order {
        display: flex;
        flex-direction: row;
        justify-content: space-between;

        &-data {
            display: flex;
        }

        &-value {
            border: 1px solid $grayMain;
            width: 70px;
            height: 35px;

            &-input {
                height: 100%;
                width: 100%;
                border: none;
            }

            &-select {
                background-color: white;
                height: 100%;
                border: none;
                padding-top: 0px;
                background-size: 14px;
            }
        }
    }
}

.pf-frame-info-coupon-data {
    display: flex;
    flex-direction: row;
    justify-content: space-between;

    &-clear {
        color: $red;
        font-size: 13px;
        margin-left: 40px;
        margin-right: 5px;
        cursor: pointer;
    }
}

</style>