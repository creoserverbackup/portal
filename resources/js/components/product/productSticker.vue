<template>
    <div class="product-sticker-type" :class="`product-sticker-type--${typeStyle}`">

        <div class="product-sticker-type__sticker" v-if="checkTypeLine()">
            <div class="product-sticker-type__sticker-rectangle"></div>
            <img v-if="product.sale"
                 class="product-sticker-type__sticker-icon"
                 loading="lazy"
                 src="/images/components/products/discount.svg"
                 alt="discount">
            <img v-else-if="product.type == 1"
                 class="product-sticker-type__sticker-icon"
                 loading="lazy"
                 src="/images/components/products/configure.svg"
                 alt="configure">
            <img v-else-if="product.type == 2"
                 class="product-sticker-type__sticker-icon"
                 loading="lazy"
                 src="/images/components/products/ready-to-go.svg"
                 alt="ready to go">
            <img v-else-if="product.type == 4"
                 class="product-sticker-type__sticker-icon"
                 loading="lazy"
                 src="/images/components/products/boxes.svg"
                 alt="boxes">
        </div>

        <div class="product-sticker-type__stick-lines">
            <div v-if="checkTypeLine()" class="product-sticker-type__stick-line" :class="{'bg-secondary': product.sale}">
                {{ getProductType }}
            </div>
            <div v-if="getCondition"
                 v-bind:class="[
                     [1,2,4].includes(product.type) ? 'product-sticker-type__stick-line-two': 'product-sticker-type__stick-line-one p-1']"
                 class="product-sticker-type__stick-line bg-red">
                {{ getCondition }}
            </div>

            <div v-if="readyToGo" class="product-sticker-type__stick-line bg-success product-sticker-type__stick-line-two">
                {{ readyToGo }}
            </div>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper";
import {default as condition} from "../../data/condition";
import {default as productTypes} from "../../data/product-type";

export default {
    name: "productSticker",
    props: {
        product: {}
    },

    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        checkCondition(conditionProduct) {
            return conditionProduct == condition.new
        },
        checkTypeLine() {
            if (productTypes.zelf_samenstellen === this.product.type && this.product.masterId > 0 && !this.product.sale) {
                return false
            }

            return this.product.type !== productTypes.geen || this.product.sale
        },
    },
    computed: {
        typeStyle() {
            let type = 'primary'

            // product new
            // if (this.product.state == '1') {
            //     type = 'danger'
            // }

            if (this.product.sale) {
                type = 'secondary'
            } else if (this.product.type === 4) {
                type = 'dark'
            }

            return type
        },
        getProductType() {

            if (this.product.sale) {
                return this.$t('productOfferTypeSticker');
            }

            switch (this.product.type) {
                case 1:
                    return this.$t('productBuildTypeSticker');
                case 2:
                    return this.$t('productReadyToGoTypeSticker');
                case 3:
                    return this.$t('productOfferTypeSticker');
                case 4:
                    return this.$t('productMultibatchTypeSticker');
            }
        },

        getCondition() {

            if (this.product.state == '1') {
                return this.$t('productConditionNew');
            }
            switch (this.product.state) {
                case '1':
                    return this.$t('productConditionNew');
                    // case '2':
                    //     return this.$t('productConditionRefurbished');
                    // case '3':
                    //     return this.$t('productConditionRecertified');
                default :
                    return '';
            }
        },

        readyToGo()
        {
            if (this.product.type == 2) {
                return this.$t('productReadyToGoTypeSecondSticker');
            }

            return ''
        }

    }
}
</script>