<template>
    <div>
        <PickupReturnService v-if="otherWarranty.values.length > 0"
                             @addWarranty="addWarranty"
                             :otherWarranty="otherWarranty.values"
                             ref="pickupReturnService"
                             :textService="textService"
        />

        <div>
            <div class="product-main__config-description">{{ $t('DashboardProductText') }}</div>
            <div class="fs-16 mb-3 fw-300" v-html="product.longDescription"></div>
        </div>
        <product-metric :product="product"/>

        <div class="product-main__warranty">
            <div class="fs-20 c-primary">Bezorging / Garantie</div>
            <div class="product-main__warranty-text" v-html="textGaranty">
            </div>
        </div>
    </div>
</template>

<script>
import PickupReturnService from "../dashboard/PickupReturnService";
import ProductMetric from "./productMetric";

export default {
    name: "productFooterInfo",
    components: {
        ProductMetric,
        PickupReturnService
    },
    props: {
        product: {},
        otherWarranty: {},
        textGaranty: {},
        textService: {},
    },
    methods: {
        addWarranty(status) {
            this.$emit('addWarranty', status)
        },
        deleteExtraWarranty()
        {
            this.$refs.pickupReturnService.deleteExtraWarranty()
        }
    },
}
</script>

<style scoped>

</style>
