<template>
    <header class="dashboard-roof">
        <div class="dashboard-roof__mob-header d-xl-none">
            <div class="inner-logo">
                <router-link to="/" class="inner-logo__img-wrap">
                    <img class="inner-logo__img img-responsive" alt="logotype"
                         v-bind:src="[logo != '' ? logo : 'images/logo.png']">
                </router-link>

            </div>
        </div>

        <div class="dashboard-roof__main">
            <!--            <button v-if="isMobile" class="filter-toggle d-none d-md-block" type="button" :class="{open: isFilterOpen}"-->
            <!--                    v-on:click="isFilterOpen = !isFilterOpen">-->
            <!--                <i class="icon-filter"></i>-->
            <!--            </button>-->

            <!--            <FiltersBlock :isOpen="isFilterOpen"/>-->
            <SearchBlock/>

            <div class="dashboard__mob-toggles d-xl-none">
                <!-- <button class="dashboard__mob-toggles-toggle js-dashboard-helpers-toggle" v-on:click="openMenu"><i
                        class="icon-filter"></i> Navigatie
                </button> -->


                <!--                <button v-if="isMobile" class="filter-toggle d-md-none" type="button" :class="{open: isFilterOpen}"-->
                <!--                        v-on:click="isFilterOpen = !isFilterOpen">-->
                <!--                    <i class="icon-filter"></i>-->
                <!--                </button>-->
            </div>
            <HtmlBlock hook="portal_mega_menu_new" v-if="!isMobile"/>
        </div>

        <dashboard-header-basket v-bind:key="reRenderKey" :popupDownloadOfferCart.sync="popupDownloadOfferCart"/>

        <message-popup/>
        <popup-ok-or-no/>
        <popup-pre-order/>
        <popup-chat-new/>

        <popup-download-offer-cart v-if="popupDownloadOfferCart"  @close="close"/>
        <popup-download-offer-delivery/>

        <chat-popup v-if="!$route.query.frame"/>
    </header>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import FiltersBlock from "./FiltersBlock";
import SearchBlock from "./SearchBlock";
import MessagePopup from "../popup/popupMessage";
import PopupOkOrNo from "../popup/popupOkOrNo";
import HtmlBlock from "../HtmlBlock";
import ChatPopup from "../chat/chatPopup";
import PopupPreOrder from "../popup/popupPreOrder";
import PopupChatNew from "../popup/popupChatNew";
import DashboardMenu from "./DashboardMenu.vue";
import PopupDownloadOfferCart from "../popup/popupDownloadOfferCart";
import PopupDownloadOfferDelivery from "../popup/popupDownloadOfferDelivery";
import DashboardHeaderBasket from "./DashboardHeaderBasket";

export default {
    components: {
        DashboardHeaderBasket,
        PopupDownloadOfferDelivery,
        PopupDownloadOfferCart,
        DashboardMenu,
        PopupChatNew,
        PopupPreOrder,
        ChatPopup,
        HtmlBlock,
        PopupOkOrNo,
        MessagePopup,
        FiltersBlock,
        SearchBlock
    },
    props: {
        menuData: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            isFilterOpen: false,
            chatsId: [],
            ticketId: [],
            popupDownloadOfferCart: false
        }
    },
    mounted() {
        setTimeout(() => {
            this.checkNavbarBreak();
        });
        window.addEventListener('resize', this.checkNavbarBreak);
    },
    methods: {
        openMenu() {
            this.menuData.isOverlayActive = true;
            this.menuData.isMenuActive = true;
        },
        checkNavbarBreak() {
            const catalogNavbar = document.querySelector('.breadcrumbs-wrap'),
                    body = document.body;

            if (catalogNavbar) {
                (catalogNavbar.offsetHeight > 50) ? body.classList.add('is-extra-fixed-space') : body.classList.remove('is-extra-fixed-space');
            }
        },
        close(name = '')
        {
            this.popupDownloadOfferCart = false
        },
    },
    watch: {
        $route() {
            this.isFilterOpen = false
        }
    },
    computed: {
        ...mapGetters({
            isMobile: 'isMobile',
            reRenderKey: 'GET_RE_RENDER_KEY',
        }),
        logo() {
            return this.GET_LOGO
        },
    },
}
</script>
