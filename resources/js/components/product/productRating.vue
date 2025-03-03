<template>
    <div>
        <div class="mb-2">
            <span class="fs-14">{{ $t('DashboardProductPopularity') }}: </span>
            <div class="product__rate-wrap">
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
        <span class="fs-14">{{ $t('DashboardProductStock') }}:</span>
        <span v-if="isSoldOut" class="c-g">Op&nbsp;aanvraag</span>
        <span v-else :style="{ color: getInStockColor }">{{ getInStock }}</span>
    </div>
</template>

<script>
export default {
    name: "productRating",
    props: {
        product: {},
        isSoldOut: '',

    },
    computed: {
        getInStockColor: function () {
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
        getInStock: function () {
            const inStock = this.product.quantity
            if (inStock >= 101) {
                return '100+'
            }
            if (inStock >= 51) {
                return '50+'
            }
            if (inStock >= 21) {
                return '20+'
            }
            if (inStock >= 16) {
                return '15+'
            }
            if (inStock >= 11) {
                return '10+'
            }
            if (inStock >= 6) {
                return '5+'
            }
            if (inStock >= 1) {
                return inStock
            }
            return 'Op aanvraag'
        },
    }
}
</script>

<style scoped>

</style>
