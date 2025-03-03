<template>
    <div class="product-summary__socials">
        <ShareNetwork
            v-for="network in socialSharingData.networks"
            :network="network.network"
            :key="network.network"
            class="product-summary__socials-btn"
            :class="network.class"
            :style="{backgroundColor: network.color}"
            :url="socialSharingData.url"
            :title="socialSharingData.title"
            :description="socialSharingData.description"
        >
            <img :src="network.image" class="product-summary__socials-icon"/>
        </ShareNetwork>
<!--        <a href="https://nl.hardware.info/" target="_blank"
           class="product-summary__socials-btn">
            <img src="images/components/products/socials/hardware-info.svg"
                 class="product-main__side-summary__socials-icon"/>
        </a>-->
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: {
        product: {},

    },
    name: "socialSharingData",
    computed: {
        socialSharingData: function () {
            return {
                url: window.location.href,
                title: `Bekijk de ${this.product.name} nu hier `,
                networks: [
                    {
                        network: 'email',
                        name: 'Email',
                        image: 'images/components/products/socials/email.svg',
                        class: 'product-main__side-summary__socials-email'
                    },
                    {
                        network: 'facebook',
                        name: 'Facebook',
                        image: 'images/components/products/socials/facebook.svg',
                        class: 'product-main__side-summary__socials-facebook'
                    },
                    {
                        network: 'twitter',
                        name: 'Twitter',
                        image: 'images/components/products/socials/twitter.svg',
                        class: 'product-main__side-summary__socials-twitter'
                    },
                    {
                        network: 'whatsapp',
                        name: 'Whatsapp',
                        image: 'images/components/products/socials/whatsapp.svg',
                        class: 'product-main__side-summary__socials-whatsapp'
                    },
                ],
            }
        },
    }
}
</script>

<style scoped>

</style>
