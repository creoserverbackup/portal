<template>
    <div class="row">
        <div class="col-11 return-page">
            <div class="row pickup-service-row">
                <div class="col-xs-12 col-3 pickup-service__item" v-for="(product, index) in productsWarranty">
                    <img v-bind:src="product.image">
                    <div class="pickup-service__item-price">
                        {{ checkPrice(product.price) }} excl.
                        <div class="pickup-service__item-subprice">{{ getDiscountText(index) }}</div>
                    </div>
                    <div class="pickup-service__item-footer">
                        <button class="pickup-service__item-btn" @click="addProduct(product)" >Toevoegen aan winkelwagen</button>
                        <div class="pickup-service__item-footer-txt">{{ product.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="return-page-txt" v-html="settingsText"></div>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import {checkPriceHelper} from "../../helper";
import categories from "../../data/categories";

export default {
    data() {
        return {
            selectIdType: 1,
            settingsText: '',
            productsWarranty: '',
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false)
        axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/settings/page/content/pickup_return_pagina`
        ).then((response) => {
            this.settingsText = response.data
        })
        this.getProductsWarranty()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
            'ADD_PRODUCT_TO_CART'
        ]),
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        addProduct(product) {
            this.ADD_PRODUCT_TO_CART({product: {
                    id: product.id,
                    name: product.name,
                    quantity: 1,
                    option: [],
                    isLeasing: false,
                }, frame: this.$route.path.indexOf('frame') > -1})

        },
        getProductsWarranty() {
            axios.post(`/catalog-products-warranty`, {
                page: 1,
                sort: 'sortByPriceLowToHigh',
                search: '',
                category_id: categories.warranty,
                frame: '',
            }).then((response) => {
                this.productsWarranty = response.data.data
            }).catch((errors) => {
                console.log(errors?.response?.data)
            })
        },
        getDiscountText(number) {
            let arr = new Map([
                [0, ''],
                [1, 'U bespaart €50,-'],
                [2, 'U bespaart €100,-'],
            ])

            return arr.get(+number)
        },
    }
}
</script>
