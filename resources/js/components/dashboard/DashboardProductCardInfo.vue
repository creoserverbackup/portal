<template>
    <div class="dashboard-product-info">
        <div class="d-flex c-black p-1-0">
            <div>{{ $t('productArticle') }}:</div>
            <div class="dashboard-product-info__value">{{ product.article }}</div>
        </div>
        <div class="d-flex c-black p-1-0">
            <div>{{ $t('productNumber') }}:</div>
            <div class="dashboard-product-info__value">{{ product.sku }}</div>
        </div>
        <div class="d-flex c-black p-1-0">
            <div>{{ $t('productPopularity') }}:</div>
            <div class="dashboard-product-info__value">
                <div class="d-inline-block">
                    <div data-v-54390a62="" class="rating-stars rating-stars--xs">
                        <span class="product__rate-stars-active" :style="{ width: product.rating + '%' }">
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                        </span>
                        <span class="product__rate-stars-inactive">
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                            <i class="product__rate-icon icon-star"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex c-black p-1-0">
            <div>{{ $t('DashboardProductStock') }}:</div>
            <div class="dashboard-product-info__value">
                <span :style="{ color: getInStockColor }">{{ getInStock }}</span>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: "DashboardProductCardInfo",
    props: {
        product: {
            type: Object,
            required: true
        },
    },
    computed: {
        getInStock() {
            const inStock = this.product.quantity

            if (this.product.pause || this.product.pauseConfigurator) {
                return 'Op aanvraag'
                // return 1;
            }
            if (inStock >= 25) {
                return '25+'
            }

            if (inStock >= 1) {
                return inStock
            } else {
                return 'Op aanvraag'
                // return 1;
            }
            // return 'In bestelling'
        },
        getInStockColor() {
            const inStock = this.product.quantity
            if (inStock > 5) {
                return 'green'
            }
            if (inStock >= 3) {
                return '#daa60b'
            }
            if (inStock >= 1) {
                return '#ff8337'
            }
            return 'green'
        },
    }
}
</script>

<style lang="scss">

.dashboard-product-info {
    &__value {
        flex: 1 0 0;
        text-align: right;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
}

</style>