<template>
    <article class="product dashboard-product-card"
             :class="[
                     {'product-sale-item': product.sale},
                     {'product-multibatch-item': product.type === 4}
                     ]">

        <a class="dashboard-product-card__wrap" v-bind:href="getLink">
            <div>
            <span class="dashboard-product-card__wrap-image">
                <img :src="product.image" :alt="product.name" class="dashboard-product-card__wrap-image-image">
            </span>

                <div class="dashboard-product-card__heading">
                    <span class="dashboard-product-card__heading-link">{{ product.name }}</span>
                </div>
            </div>

            <div class="l-h-12">
                <div class="h-50px mb-5" v-if="isLoadingProductsInfo">
                    <loader/>
                </div>
                <dashboard-product-card-price :product="product" :isLoadingProductsInfo="isLoadingProductsInfo" v-else/>

                <div class="dashboard-product-card__separator"></div>
                <dashboard-product-card-info :product="product"/>
                <div class="dashboard-product-card__separator"></div>
                <dashboard-product-card-attribute :product="product"/>

                <dashboard-product-card-button :product="product"/>

                <ul class="product__advantages">
                    <li class="product__advantage" v-for="advantage in advantages">
                        <img class="product__advantage-icon" src="/images/components/products/ok.svg"
                             alt="advantage icon"/>
                        <span>{{ advantage }}</span>
                    </li>
                </ul>

                <dashboard-product-card-sticker :product="product"/>
            </div>
        </a>
    </article>
</template>

<script>

import DashboardProductCardTimer from "./DashboardProductCardTimer";
import DashboardProductCardAttribute from "./DashboardProductCardAttribute";
import DashboardProductCardInfo from "./DashboardProductCardInfo";
import DashboardProductCardSticker from "./DashboardProductCardSticker";
import DashboardProductCardPrice from "./DashboardProductCardPrice";
import DashboardProductCardButton from "./DashboardProductCardButton";
import Loader from "../loader/loader";

export default {
    name: 'DashboardProductCard',
    components: {
        Loader,
        DashboardProductCardButton,
        DashboardProductCardPrice,
        DashboardProductCardSticker,
        DashboardProductCardInfo,
        DashboardProductCardAttribute,
        DashboardProductCardTimer
    },
    props: {
        product: {
            type: Object,
            required: true
        },
        isLoadingProductsInfo: ''
    },
    data() {
        return {
            advantages: [
                'Op voorraad',
                'Met garantie',
                'Getest & Updated',
                'Kwaliteit product',
            ],
        }
    },
    computed: {
        getLink() {
            return process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + this.product.id
            // return {
            //     name: 'product',
            //     params: {
            //         productId: this.product.id
            //     }
            // };
        },
    }
}
</script>

<style lang="scss">
@import "resources/sass/abstracts/variables";
@import "resources/sass/abstracts/mixins";

.dashboard-product-card {

    &__wrap {
        text-decoration: none;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    &__heading {
        color: $black;
        text-align: left;
        font-weight: 300;
        padding: 0 .5rem;
        font-size: 1.8rem;
        font-weight: bold;
        padding-left: 1rem;
        margin-bottom: 1rem;
        // white-space: nowrap;
        // overflow: hidden;
        // text-overflow: ellipsis;

        @media (max-width: $xlg) {
            font-size: 1.3rem;
        }

        &-link {
            transition: all .3s;
            text-decoration: none;
            color: $black;
            font-weight: normal;

            &:hover {
                color: $primary;
            }
        }
    }

    &__wrap-image {
        height: 18rem;
        position: relative;
        display: block;
        margin-bottom: 1.5rem;

        &-image {
            max-width: 100%;
            max-height: 100%;
            height: 100%;

            @include absolute-center;
        }
    }


    .atom-spinner {
        height: 40px !important;
        width: 40px !important;
    }

    .spinner-circle {
        width: calc(40px * 0.18) !important;
        height: calc(40px * 0.18) !important;
    }

    &__separator {
        border-bottom: 1px solid $gray;
        margin-top: 0.4rem;
    }

}

</style>
