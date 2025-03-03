<template>

    <div class="product-main-associated">
        <div class="product-main-associated__title f-s-2rem c-primary mb-2 position-relative d-flex align-items-center"
             @click="triggerAssociated()">{{ $t('DashboardProductAssociated') }}
            <div class="product-main-associated__arrow ml-2"
                 :class="{ 'product-main-associated__expanded' : show }">
            </div>
        </div>
        <div class="product-main-associated__products" v-if="show"
             v-bind:class="[show ? 'product-main-associated__products-show' : 'product-main-associated__products-hide']">
            <div class="product-main-associated__product" v-for="product in associated">
                <div class="checkbox-label mb-4">
                    <input class="checkbox-label__input" type="checkbox"
                           :name="product.name"
                           :id="product.name"
                           v-model="product.checked">
                    <label class="checkbox-label__main"
                           :for="product.name">
                        {{ product.name + ' + ' + checkPrice(product.price) }}
                        => <a v-bind:href="getUrl(product.productId)" target="_blank">View product</a>
                    </label>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {checkPriceHelper} from "../../helper";

export default {
    name: "productAssociated",
    props: {
        associated: '',
    },
    data() {
        return {
            show: false,
        }
    },
    mounted() {
    },
    methods: {
        getUrl(productId)
        {
            return process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + productId
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        triggerAssociated() {
            this.show = !this.show
        }
    }
}
</script>

<style scoped>

</style>