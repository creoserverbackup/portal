<template>
    <div class="dashboard-product-price__current-price c-primary" :class="{'c-secondary': product.sale}">
        <div class="dashboard-product-price__before-price">
            <span class="dashboard-product-price__before-price-old"
                  v-if="product.sale">{{ checkPrice(product.priceOld) }}</span>
            <span v-if="product.type === 1">{{ $t('productPriceFrom') }}:</span>
        </div>
        <div class="d-flex-column-between-end c-primary">
            <div>{{ checkPrice(product.price) }}<span v-if="needNds">excl.</span></div>
            <small class="fs-11 c-black" v-if="needNds">
                {{ checkPrice(product.priceNds) }} incl.
            </small>
            <small class="fs-11 c-black" v-else>No VAT needed</small>
        </div>

    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper";
import {mapGetters} from "vuex";

export default {
    name: "DashboardProductCardPrice",
    props: {
        product: {
            type: Object,
            required: true
        },
        isLoadingProductsInfo: ''
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
    },
    computed: {
        ...mapGetters([
            'GET_NEED_NDS'
        ]),
        needNds() {
            return this.GET_NEED_NDS;
        },
    }
}
</script>

<style lang="scss">
@import "resources/sass/abstracts/variables";

.dashboard-product-price {
    &__current-price {
        font-size: 2rem;
        color: $black;
        white-space: nowrap;
        text-overflow: ellipsis;
        position: relative;

        @media (min-width: $xlg) and (max-width: 1300px) {
            font-size: 1.8rem;
        }
    }

    &__before-price {
        color: $primary;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1.2rem;

        &-old {
            text-decoration: line-through;
            color: $black;
            font-weight: bold;
            font-size: 16px;
        }
    }
}

</style>