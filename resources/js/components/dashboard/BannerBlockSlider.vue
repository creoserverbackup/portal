<template>
    <swiper ref="swiper" :options="swiperOptions">
        <template v-for="(item, key) in banners">
            <swiper-slide>
                <img class="img-fluid"
                     loading="lazy"
                     :src="item.src"
                     :alt="`Banner server ${key}`">
            </swiper-slide>
        </template>
    </swiper>

</template>

<script>
import {Swiper, SwiperSlide, directive} from 'vue-awesome-swiper'
import 'swiper/css/swiper.min.css'

export default {
    name: "BannerBlockSlider",
    components: {
        Swiper,
        SwiperSlide
    },

    directives: {
        swiper: directive
    },

    data() {
        return {
            banners: [
                {src: `/images/media/banner-1.png`},
                {src: `/images/media/banner-2.png`},
                {src: `/images/media/banner-3.png`},
                {src: `/images/media/banner-4.png`},
                {src: `/images/media/banner_workstations.jpeg`},
                {src: `/images/media/banner_transport.jpeg`},
                {src: `/images/media/banner-self-delivery.jpg`},
                {src: `/images/media/banner-parts.jpg`},
                {src: `/images/media/banner-laptop.jpg`},
                {src: `/images/media/banner-klanten.jpg`},
                {src: `/images/media/banner-hp-packerd-Enterprise.jpg`},
                {src: `/images/media/banner-duurzaamheid.jpg`},
                {src: `/images/media/banner-Dell-EMC.jpg`},
                {src: `/images/media/banner-customer-portal.jpg`},
                {src: `/images/media/banner_products_on_stock.jpeg`},
                {src: `/images/media/banner_self_delivery.jpeg`},
                {src: `/images/media/Gen11_banner.png`},
            ],
            swiperOptions: {
                slidesOffsetAfter: 0,
                slidesPerView: 1,
                slidesPerGroup: 1,
                spaceBetween: 0,
                notNextTick: true,
                loop: true,
                initialSlide: 0,
                effect: 'fade',
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: true
                },
                speed: 800,
                grabCursor: true,
            },
        }
    },
}
</script>
