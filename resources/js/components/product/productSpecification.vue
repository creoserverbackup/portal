<template>
    <div class="product-specifications__container col-sm-12 col-xl-5 mb-60 px-3xl-4">
        <div class="d-flex flex-column h-100">
            <div v-if="product.sku" class="h5 text-gray">Product nummer: {{ product.sku }}</div>
            <div v-else class="h5">&nbsp;</div>

            <div class="flex-grow-1 product-main__details-text border border-1 border-dark px-4 py-5 text-center scroll"
                 v-bind:class="{ 'product-main__frame-details-text': this.$route.path.indexOf('frame') > -1 }">
                <div class="product-main__details-text__specs d-inline-block text-start"><h2 class="h4 c-primary">Basis product</h2>
                    <div class="table-specification">
                        <table class="table-specification__table">
                            <tbody>
                            <tr v-if="getCondition">
                                <td class="table-specification__name">
                                    Conditie
                                </td>
                                <td class="table-specification__value">
                                    {{ getCondition }}
                                </td>
                            </tr>
                            <tr v-for="(item, key) in product.configurator_attributes" :key="key">
                                <td class="table-specification__name">
                                    {{ item.name }}
                                </td>
                                <td class="table-specification__value">
                                    {{ item.value }}
                                </td>
                            </tr>


                            <template v-for="(item) in attributes">
                                <tr>
                                    <td class="table-specification__group-name" colspan="2">
                                        {{ item.name }}
                                    </td>
                                </tr>
                                <tr v-for="(item) in item.attribute_attributes" :key="item.id">
                                    <td class="table-specification__name">
                                        {{ item.name }}
                                    </td>
                                    <td class="table-specification__value">
                                        <template v-for="(itemValue,key) in item.values">
                                            <template v-if="key !== 0 && key < item.values.length">/</template>
                                            <template v-if="itemValue.value === true">
                                                <i class="text-success icon-check"/>
                                            </template>
                                            <template v-else-if="itemValue.value === false">
                                                <i class="text-red icon-close"/>
                                            </template>
                                            <template v-else>
                                            {{ $t(`common.valueOfTypeList.${itemValue.type}`, {value: itemValue.value}) }}
                                            </template>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {default as condition} from '../../data/condition';

export default {
    name: "productSpecification",
    props: {
        product: {},
    },
    computed: {
        attributes() {
            return this.product?.attributes.filter(item => item.hook === 'base') ?? []
        },
        getCondition: function () {
            switch (this.product.state) {
                case condition.new:
                    return this.$t('productConditionNewSmall');
                case condition.recertified:
                    return this.$t('productConditionRecertified');
                case condition.refurbished:
                    return this.$t('productConditionRefurbished');

                default :
                    return '';
            }
        },
    }
}
</script>

<style scoped>

</style>
