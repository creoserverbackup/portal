<template>

    <div class="dashboard-roof__basket">
        <div class="header-utility">
            <div class="header-utility__main">
                <router-link to="/payment-flow" href="#" class="header-utility__link">
                        <span class="header-utility__heading"
                              v-if="this.$route.path.indexOf('frame') > -1">TOEGEVOEGD</span>
                    <span class="header-utility__heading" v-else>WINKELWAGEN</span>
                    <i class="icon-shopping-cart"></i>
                </router-link>
                <div class="header-utility__info">{{ countProduct }} product - {{ cartTotal }}</div>
                <div class="header-utility__dropdown">
                    <div class="header-utility__products scroll">
                        <div class="header-utility__product" v-for="(product, index) in orders">
                            <div class="header-utility__product-heading">
                                <router-link :to="getLink(product)">{{ product.quantity }}x {{
                                        product.name
                                    }}
                                </router-link>
                            </div>
                            <div class="header-utility__product-price">
                                {{ checkPrice(product.price * product.quantity) }} excl.
                            </div>

                            <div class="header-utility__product-price" v-if="product.isLeasing">{{
                                    checkPrice(product.price * product.quantity / 12)
                                }} p.m excl.
                            </div>
                            <div class="header-utility__product-price" v-if="product.isLeasing">12 maanden</div>
                            <span class="header-utility__product-rm" v-on:click="removeProduct(product)">
                                    <i class="icon-bin"></i>
                                </span>
                        </div>
                    </div>
                    <div class="header-utility__dropdown-summary">
                        <dl>
                            <div>
                                <dt>Totaal excl. btw:</dt>
                                <dd> {{ cartTotal }}</dd>
                            </div>
                            <div>
                                <dt>Totaal incl. btw:</dt>
                                <dd> {{ cartTotalWithTax }}</dd>
                            </div>
                        </dl>
                    </div>
                    <router-link to="/payment-flow" class="btn btn--primary">
                        <span>Verder naar betalen</span>
                    </router-link>

                    <span class="btn btn--s-secondary-bg-hover-dark-blue pl-0 fs-11 letter-spacing-0-5" v-if="countProduct"
                          @click="showPopup">
                            Offerte van uw winkelwagen
                        </span>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {checkPriceHelper} from "../../helper.js"

export default {
    name: "DashboardHeaderBasket",

    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
            'REMOVE_PRODUCT_FROM_CART',
        ]),

        showPopup()
        {
            this.$emit('update:popupDownloadOfferCart', true)
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        getLink(product) {
            return {
                name: 'product',
                params: {
                    productId: product.productId
                }
            };
        },
        async removeProduct(product) {
            await this.REMOVE_PRODUCT_FROM_CART({product: product, frame: this.$route.path.indexOf('frame') > -1});

            setTimeout(() => {
                this.$root.$emit('UpdateOrderUser', {})
            }, 2000);
        },
    },
    computed: {
        ...mapGetters({
            isMobile: 'isMobile',
            cart: 'GET_CART',
            needNds: 'GET_NEED_NDS',
            reRenderKey: 'GET_RE_RENDER_KEY',
        }),
        cartTotal() {
            return this.cart.prices != undefined ? this.checkPrice(this.cart.prices.priceFullPersonalDiscountAfter) : 0
        },
        cartTotalWithTax() {
            return this.cart.prices != undefined ? this.checkPrice(this.cart.prices.priceFullWithNDS) : 0
        },
        countProduct() {
            return this.cart.orders != undefined ? this.cart.orders.length : 0
        },
        orders() {
            return this.cart.orders
        },
        frameKey() {
            if (this.$route.query.frame) {
                return "&frame=" + this.$route.query.frame
            } else {
                return ""
            }
        },
        logo() {
            return this.GET_LOGO
        },
    },
}
</script>

<style scoped>

</style>