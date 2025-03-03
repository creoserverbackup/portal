<template>
    <div>
        <div class="product__stick-lines">
            <div v-if="checkTypeLine()" :class="{'bg-secondary': product.sale}"
                 class="product__stick-line">
                {{ getProductType }}
            </div>
            <div v-if="product.targetId !== 0" :class="{'bg-secondary': product.sale}"
                 class="product__stick-line">
                {{ product.targetName }}
            </div>
            <div v-if="checkCondition(product.state)" class="product__stick-line bg-red">
                {{ getCondition }}
            </div>

            <div v-if="readyToGo" class="product__stick-line bg-success">
                {{ readyToGo }}
            </div>

            <dashboard-product-card-timer v-if="product.sale && product.indefinitePeriod == 0" :product="product"/>
        </div>
        <div v-if="checkTypeLine()" class="product__sticker" :class="setProductStickerClass">
            <div class="product__sticker-body">
            </div>
            <img v-if="product.sale" class="product__sticker-icon"
                 src="/images/components/products/discount.svg" alt="discount">
            <img v-else-if="product.type === 2" class="product__sticker-icon"
                 src="/images/components/products/ready-to-go.svg" alt="ready">
            <img v-else-if="product.type === 1" class="product__sticker-icon"
                 src="/images/components/products/configure.svg" alt="configure">
            <img v-else-if="product.type === 4" class="product__sticker-icon"
                 src="/images/components/products/boxes.svg" alt="boxes">
        </div>
    </div>
</template>

<script>

import DashboardProductCardTimer from "./DashboardProductCardTimer";
import {default as condition} from "../../data/condition";
import {default as productTypes} from "../../data/product-type";

export default {
    name: "DashboardProductCardSticker",
    components: {
        DashboardProductCardTimer
    },
    props: {
        product: {
            type: Object,
            required: true
        },
    },
    methods: {
        checkCondition(conditionProduct) {
            return conditionProduct == condition.new
        },
        checkTypeLine() {

            if (productTypes.zelf_samenstellen == this.product.type && this.product.masterId > 0 && !this.product.sale) {
                return false
            }

            return this.product.type !== productTypes.geen || this.product.sale
        }
    },
    computed: {
        setProductStickerClass() {
            let className;

            switch (this.product.type) {
                case 0:
                    className = 'product__sticker--disabled';
                    break;
                case 1:
                    className = 'product__sticker--build';
                    break;
                case 2:
                    className = 'product__sticker--ready-to-go';
                    break;
                case 3:
                    className = 'product__sticker--offer';
                    break;
                case 4:
                    className = 'product__sticker--multibatch';
                    break;
            }

            if (this.product.sale) {
                className = 'product__sticker--offer';
            }

            return className;
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

<style scoped>

</style>