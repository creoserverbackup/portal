<template>
    <div v-if="metric && metric.attribute_attributes.length" class="product-main__metrics">
        <div class="product-main__metrics-heading">
            {{ $t("components.product.titleMetrics") }}
        </div>
        <div class="product-main__metrics-table">
            <div class="product-main__metrics-item" v-for="(item, key) in metric.attribute_attributes"
                 :key="key">
                <div>{{ item.name }}</div>
                <div>
                    <template v-for="(itemValue,key) in item.values">
                        <template v-if="key !== 0 && key < item.values.length">/</template>
                        <template v-if="itemValue.value === true">
                            <img src="images/components/products/yes.svg"
                                 alt="Yes"
                                 class="product-metric__metrics-item-yes"/>
                        </template>
                        <template v-else-if="itemValue.value === false">
                            <img src="images/components/products/no.svg"
                                 alt="No"
                                 class="product-metric__metrics-item-no"/>
                        </template>
                        <template v-else>
                            {{ $t(`common.valueOfTypeList.${itemValue.type}`, {value: itemValue.value}) }}
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "productMetric",
    props: {
        product: {},

    },
    computed: {
        metric() {
            return this.product?.attributes.find(item => item.hook === 'metric') ?? null
        }
    }
}
</script>

<style scoped>

</style>
