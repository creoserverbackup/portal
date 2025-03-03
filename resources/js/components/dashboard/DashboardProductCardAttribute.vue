<template>
    <ul>
        <li class="dashboard-product-attribute c-black p-3-0" v-for="(attribute) in getAttributes">
            <template v-if="attribute && attribute.name">
                <span>• {{ attribute.name }}:</span>
                <template v-if="attribute.objectType ==='attribute'">
                <span>
                    <template v-for="(item,key) in attribute.values">
                        <template v-if="key !== 0 && key < attribute.values.length">/</template>
                        <template v-if="item.value === true">
                            <i class="text-success icon-check"/>
                        </template>
                        <template v-else-if="item.value === false">
                            <i class="text-red icon-close"/>
                        </template>
                        <template v-else>
                            {{ $t(`common.valueOfTypeList.${item.type}`, {value: item.value}) }}
                        </template>
                    </template>
                </span>
                </template>
                <template v-else>
                    <span>{{ attribute.value }}</span>
                </template>
            </template>
            <template v-else>
                <span>&nbsp;</span>
            </template>
        </li>
    </ul>
</template>

<script>
export default {
    name: "DashboardProductCardAttribute",
    props: {
        product: {
            type: Object,
            required: true
        },
    },

    computed: {
        getAttributes() {
            const attributesLength = 9;
            let attributes = this.product.attributes.slice(0, attributesLength);
            for (let i = attributes.length; i < attributesLength; i++) {

                attributes.push('');
            }
            return attributes;
        },
    }
}
</script>

<style lang="scss">

.dashboard-product-attribute {
    display: flex;
    font-size: 1.3rem;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;

    span {
        display: block;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;

        &:nth-child(1) {
            min-width: 50%;
            max-width: 50%;
        }
    }
}

</style>