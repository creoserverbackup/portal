<template>
    <div class="row mx-3xl-n4"
         v-bind:class="{ 'product-main__frame-details-image-text': this.$route.path.indexOf('frame') > -1 }">
        <div class="col-sm-12 col-xl-7 mb-60 px-3xl-4">
            <div class="d-flex flex-column h-100">
                <div>
                    <div class="h5 text-gray">
                        Artikel nummer: {{ product.article }}
                    </div>
                </div>
                <div class="flex-grow-1">
                    <product-image-slider :product="product"
                                          :images="product.images"
                                          :defImages="product.defImages"/>
                </div>
            </div>
        </div>
        <product-specification :product="product"/>
    </div>

</template>

<script>
import ProductSpecification from "./productSpecification";
import ProductImageSlider from "./productImageSlider";

export default {
    name: "productHeaderInfo",
    components: {
        ProductImageSlider,
        ProductSpecification,
    },
    props: {
        product: {},

    },
}
</script>

<style scoped>

</style>
