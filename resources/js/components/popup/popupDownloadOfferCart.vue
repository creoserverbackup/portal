<template>
<!--     popup-download-offer-cart  == >  pdoc-->
    <div class="pdoc pdoc__open">
        <div class="pdoc__inner">
            <div class="pdoc__close" @click="close">
                <img src="/images/close.svg" alt="close"/>
            </div>
            <h2 class="c-secondary ta-c">Offerte winkelwagen</h2>

            <div>
                Wilt u uw winkelwagen nu omzetten naar een offerte, of wilt u nog meer producten toevoegen aan uw
                winkelmandje voordat u deze in een offerte aanvraagt?
            </div>

            <div class="d-flex-row-between mt-25">
                <button class="btn btn--t-30 btn--s-secondary-bg-hover-dark-blue w-100 d-block mb-2 mr-5"
                        @click="close">
                    Verder winkelen
                </button>
                <button class="btn btn--t-30 btn--s-secondary-bg-hover-dark-blue w-100 d-block mb-2"
                        @click="downloadOffer">
                    Offerte nu aanmaken
                </button>
            </div>
        </div>
        <div class="pdoc__background" @click="close"></div>
    </div>
</template>

<script>

import ProfileInputGroup from "../../components/profile/ProfileInputGroup";
import {mapActions} from "vuex";

export default {
    name: "popupDownloadOfferCart",
    components: {
        ProfileInputGroup
    },
    data() {
        return {
            show: false,
        }
    },
    mounted() {
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        close() {
            this.$emit('close');
        },
        async downloadOffer() {

            this.$root.$emit('popupDownloadOfferDelivery', {
                product: '',
                cart: true,
            })
            this.$emit('close');
        }
    },
}
</script>

<style lang="scss">

@import "resources/sass/abstracts/variables";

.pdoc {
    text-align: center;
    overflow: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    /* z-index: 1050; */
    -webkit-overflow-scrolling: touch;
    outline: 0;
    opacity: 0;
    -webkit-transition: opacity 0.15s linear, z-index 0.15;
    -o-transition: opacity 0.15s linear, z-index 0.15;
    transition: opacity 0.15s linear, z-index 0.15;
    z-index: -1;
    overflow-y: auto;


    &__open {
        z-index: 999;
        opacity: 1;
        overflow: hidden;
    }

    &__inner {
        padding: 3% 6%;
        margin-top: 25vh;
        min-width: 1000px;
        color: $black;
        width: auto;
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
        position: relative;
        z-index: 999;
        -webkit-transition: -webkit-transform 0.3s ease-out;
        -o-transition: -o-transform 0.3s ease-out;
        transition: -webkit-transform 0.3s ease-out;
        -o-transition: transform 0.3s ease-out;
        transition: transform 0.3s ease-out;
        transition: transform 0.3s ease-out, -webkit-transform 0.3s ease-out;
        display: inline-block;
        vertical-align: middle;
        background: #FFFFFF;
        box-shadow: 0px 4px 32px rgba(0, 0, 0, 0.1);
        -webkit-transform: translate(0, -25%);
        -ms-transform: translate(0, -25%);
        transform: translate(0, -25%);
        -webkit-transition: -webkit-transform 0.3s ease-out;
        -o-transition: -o-transform 0.3s ease-out;
        transition: -webkit-transform 0.3s ease-out;
        -o-transition: transform 0.3s ease-out;
        max-width: 440px;
        font-size: 22px;
        line-height: 1.2;
        text-align: left;

        label {
            font-size: 15px;
        }

    }

    &__body {
        max-height: 300px;
        overflow: auto;
    }

    &__body-message {
        font-size: 20px;
        text-align: center;
        color: $black;
        padding: 10px;
    }

    &__submit {
        margin-top: 25px;
    }

    input[type="submit"] {
        outline: 0;
        border: 0;
        border-radius: 0;
        font-size: 15px;
        font-style: normal;
        font-weight: 700;
        line-height: 23px;
        letter-spacing: 0em;
        text-align: center;
        width: 100%;
        cursor: pointer;
        transition: 0.3s;
        padding: 15px 32px 14px;
        background: $secondary-color;
        opacity: 1;
        color: $white;
    }

    &__close {
        position: absolute;
        right: 10px;
        top: 10px;
        cursor: pointer;
        z-index: 99;
        font-size: 30px;
        color: #fff;
        display: flex;
        width: 24px;
        height: 24px;
        justify-content: center;
        align-items: center;
    }

    &__close svg {
        width: 14px;
        height: 14px;
        fill: rgba(16, 24, 32, 0.3);
    }

    &__close:hover svg {
        fill: $secondary-color;
    }

    &__background {
        background: rgba(51, 51, 51, 0.9);
        height: 100vh;
        width: 100%;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        -webkit-transition: background 0.15s linear;
        -o-transition: background 0.15s linear;
        transition: background 0.15s linear;
    }


    h2 {
        font-weight: bold;
        font-size: 23px;
    }

    .text-bold {
        font-weight: bold;
    }
}
</style>