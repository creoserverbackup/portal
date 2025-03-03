<template>
    <div v-if="images.length" class="image-slider" @mouseover="hoverOver" @mouseout="hoverOutTimeout">

        <product-sticker v-if="product.type || product.sale || product.state == '1'" :product="product"/>

        <div class="image-slider__full" v-bind:class="{ 'image-slider__full-show': isFullScreen }">
            <button v-on:click="setFullScreen()" class="image-slider__full-btn fs-17">X</button>
            <img class="image-slider__full-zoom" :src="images[imageIndex]"/>
            <div class="image-slider__full-thumbs" v-if="!defImages">
                <VueSlickCarousel :slidesToShow="4" :focusOnSelect="true">
                    <div class="image-slider__thumbs-slide" v-for="(image, index) in images" :key="index"
                         @click="setImageIndex(index)">
                        <img class="image-slider__thumbs-image" :src="image" :alt="product.name + ' ' + index"/>
                    </div>
                </VueSlickCarousel>
            </div>
        </div>

        <div class="image-slider__zoom">
            <i class="arrow arrow-left"></i>
            <div v-on:click="arrowLeft()" class="arrow-info arrow-info__left" v-if="!defImages"></div>
            <div v-on:click="arrowRight()" class="arrow-info arrow-info__right" v-if="!defImages"></div>

            <div class="image-slider__zoom--click" v-on:click="setFullScreen()">
                <zoom-on-hover
                        :img-normal="images[imageIndex]"
                        :img-zoom="images[imageIndex]"
                        :scale="1.5"/>
            </div>
        </div>

        <div class="image-slider__thumbs" v-if="!defImages">
            <VueSlickCarousel @afterChange="setImageIndex" :slidesToShow="5" :focusOnSelect="true">
                <div class="image-slider__thumbs-slide" v-for="(image, index) in images" :key="index">
                    <img class="image-slider__thumbs-image" :src="image" :alt="product.name + ' ' + index"/>
                </div>
            </VueSlickCarousel>
        </div>
    </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import ZoomOnHover from "vue-zoom-on-hover";

import Vue from 'vue';
import ProductSticker from "./productSticker";

Vue.use(ZoomOnHover);
Vue.use(VueSlickCarousel);

export default {
    name: "productImageSlider",
    components: {
        ProductSticker,
        VueSlickCarousel
    },
    props: {
        images: {},
        defImages: false,
        product: {}
    },
    data() {
        return {
            imageIndex: 0,
            isFullScreen: false,
            toRunScroll: true,
        }
    },
    mounted() {
        setInterval(() => this.runScroll(), 10000)
    },
    methods: {
        hoverOver: function () {
            this.toRunScroll = false
        },
        hoverOutTimeout: function () {
            this.toRunScroll = true
        },
        setImageIndex(index) {
            this.imageIndex = index;
        },
        setFullScreen() {
            if (!this.defImages) {
                this.isFullScreen = !this.isFullScreen;
            }
        },
        runScroll() {
            if (this.toRunScroll) {
                this.arrowRight()
            }
        },
        arrowLeft() {
            if (this.imageIndex === 0) {
                this.imageIndex = this.images.length - 1
            } else {
                --this.imageIndex
            }
        },
        arrowRight() {
            if (this.imageIndex === this.images.length - 1) {
                this.imageIndex = 0
            } else {
                ++this.imageIndex
            }
        }
    }
}
</script>
