<template>
    <div v-if="trays && trays.length">
        <div class="product-main__config-heading">Additionally</div>
        <div class="product-main__config-item mb-3 fs-16 fw-300 d-flex position-relative">
            <div class="product-main__config-setting">Caddies / Trays</div>
            <div class="product-main__config-select">
                <div class="w-100 d-flex">
                    <div class="product-main__config-select-item-col product-main__config-select-item-col--select">
                        <div class="d-flex w-100">
                            <div class="product-main__wrap-select-col product-main__wrap-select-col--select">
                                <product-tray-line
                                        :trays="trays"
                                        :tray="tray"
                                        @selectTray="selectTray"
                                />
                            </div>
                        </div>
                    </div>
                    <product-tray-url v-if="tray.urlSelect != '' && tray.urlSelect != undefined" :tray="tray" />
                    <div class="product-main__config-select-item-col product-main__config-select-item-col--input-count">
                        <input class="product-main__config-count" type="number"
                               v-if="tray"
                               v-bind:class="[
                                       tray && tray.counter == tray.quantity ? 'c-sc' : '',
                                       ]"
                               v-bind:max="tray.quantity"
                               v-bind:min="1"
                               v-model="tray.counter">
                        <input disabled v-else class="product-main__config-count"
                               v-bind:placeholder="getPlaceholder()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProductConfiguratorLine from "./productConfiguratorLine";
import ProductConfiguratorUrlSelect from "./productConfiguratorUrlSelect";
import ProductTrayLine from "./productTrayLine";
import ProductTrayUrl from "./productTrayUrl";

export default {
    name: "productTray",
    components: {
        ProductTrayUrl,
        ProductTrayLine,
        ProductConfiguratorUrlSelect,
        ProductConfiguratorLine
    },
    props: {
        product: {},
        trays: {},
        tray: {},
    },
    data() {
        return {
            time: null,
        }
    },
    methods: {
        getPlaceholder() {
            return this.tray ? this.tray.counter : 'nvt'
        },
        selectTray(tray) {
            this.$emit('selectTray', tray)
        },
    },
}

</script>

<style scoped>

</style>
