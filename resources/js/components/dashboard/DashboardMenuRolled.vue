<template>
    <simplebar class="dashboard__sidebar-main dashboard-menu-rolled" data-simplebar-auto-hide="false">
        <nav class="dashboard-nav--thumbs-rolled mt-1 ml-3 pb-4">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item">
                    <router-link class="dashboard-nav__link" to="/catalog" :data-title="$t('navbarCatalog')">
                        <i class="dashboard-nav__icon icon-speed"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <div class="dashboard-nav__link" @click="logout()" :data-title="$t('navbarLogout')">
                        <i class="dashboard-nav__icon icon-logout"></i>
                    </div>
                </li>
            </ul>
        </nav>

        <nav class="dashboard-nav--thumbs-rolled mt-4 ml-3 pb-4">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item">
                    <router-link to="/interactive-center" class="dashboard-nav__link"
                                 :class="{'has-notify': actionsNotifications}">
                        <i class="dashboard-nav__icon icon-start-button"></i>
                    </router-link>
                    <small class="dashboard-nav__notification" v-if="actionsNotifications">{{
                            actionsNotifications
                        }}</small>
                </li>
            </ul>
        </nav>

        <nav class="dashboard-nav--thumbs-rolled mt-4 ml-3 pb-4">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item">
                    <router-link to="/page/our-data" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-star"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/request" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-scroll"></i>
                    </router-link>
                    <small class="dashboard-nav__notification" v-if="requestUnread">{{ requestUnread }}</small>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/dedicated-hosting" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-setup"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/faq" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-question-mark"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/return-service" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-wrench"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/rma" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-rotation"></i>
                    </router-link>
                </li>
            </ul>
        </nav>

        <nav class="dashboard-nav--thumbs-rolled mt-4 ml-3 pb-4">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item">
                    <router-link to="/page/warranty-policy" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-shield"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/page/return-policy" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-return-box"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/page/right-withdrawal" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-rounded-check"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/page/algemene_voorwaarden" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-file"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/page/shipping-conditions" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-truck"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link to="/page/payment-terms" class="dashboard-nav__link p-0 pt-2 pb-2">
                        <!--                        <img class="icon dashboard-nav__item-icon-payment-terms"-->
                        <!--                             v-bind:src="'/images/icon/payment-terms.svg'" alt="">-->
                        <div class="icon-payment-terms">
                            <svg

                                    version="1.0"
                                    width="18px"
                                    height="18px"
                                    viewBox="0 0 151.97525 101.97739"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:svg="http://www.w3.org/2000/svg">
                                <g
                                        transform="matrix(0.1,0,0,-0.1,-24,151)"
                                        fill="slategrey"
                                        stroke="none">
                                    <path
                                            d="m 260,1488 c -19,-21 -20,-33 -20,-429 0,-386 1,-408 19,-430 l 19,-24 492,-3 493,-3 44,-39 c 89,-78 200,-91 304,-37 161,83 198,284 78,420 l -39,45 v 239 c 0,237 0,240 -23,261 l -23,22 H 942 281 Z m 1348,-20 c 7,-7 12,-34 12,-60 v -48 H 945 270 v 48 c 0,26 5,53 12,60 17,17 1309,17 1326,0 z m 7,-208 v -65 H 945 275 l -3,54 c -2,29 -1,60 2,67 5,12 114,14 673,12 l 668,-3 z m 3,-174 3,-79 -28,7 c -15,4 -58,10 -94,13 -53,4 -75,1 -117,-17 -58,-25 -115,-82 -143,-144 -21,-46 -25,-136 -8,-185 6,-18 9,-34 6,-37 -2,-3 -217,-5 -478,-4 -363,0 -475,3 -481,13 -9,14 -11,501 -2,510 4,3 306,5 673,4 l 666,-2 z m -23,-109 c 97,-49 153,-173 126,-274 -6,-23 -26,-61 -44,-85 -138,-181 -426,-86 -427,142 0,100 54,181 150,225 43,19 152,15 195,-8 z"/>
                                    <path
                                            d="m 443,991 c -74,-46 -84,-147 -21,-202 37,-33 76,-43 119,-30 30,9 49,9 74,1 47,-16 106,2 139,41 20,24 26,41 26,80 0,42 -5,55 -34,87 -36,39 -34,38 -156,32 -14,-1 -45,1 -70,5 -35,5 -52,2 -77,-14 z m 98,-41 c -6,-45 -4,-115 3,-137 19,-52 -98,-31 -123,23 -32,71 19,147 94,141 26,-2 30,-6 26,-27 z m 187,-5 c 26,-31 30,-90 7,-122 -24,-35 -77,-48 -118,-28 -75,35 -76,132 -2,174 30,17 87,5 113,-24 z"/>
                                    <path d="m 1520,785 -80,-79 -28,27 c -28,27 -52,35 -52,18 0,-13 72,-81 85,-81 6,0 48,38 95,85 64,65 82,87 73,96 -9,9 -31,-6 -93,-66 z"/>
                                </g>
                            </svg>
                        </div>
                    </router-link>
                </li>

                <li class="dashboard-nav__item">
                    <router-link to="/page/privacy-policy" class="dashboard-nav__link">
                        <i class="dashboard-nav__icon icon-lock"></i>
                    </router-link>
                </li>
            </ul>
        </nav>

        <nav class="dashboard-nav--thumbs-rolled mt-4 ml-3 pb-4">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item shake shake--delay-1" v-on:click="showChatModal">
                    <a class="dashboard-nav__link p-2 bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" id="i-phone-3" height="20" width="20"
                             class="fill-white" viewBox="0 0 24 24">
                            <path d="M20.478 3.516A11.913 11.913 0 0011.994 0 11.913 11.913 0 003.51 3.516C-.874 7.9-1.178 14.918 2.754 19.652c-.385.776-1.004 1.68-1.923 2.13a1.125 1.125 0 00.325 2.12c.228.037.553.073.949.073 1.06 0 2.623-.249 4.221-1.4a11.998 11.998 0 0014.157-2.09 11.913 11.913 0 003.516-8.485c0-3.207-1.253-6.216-3.521-8.484zm-.97 15.999A10.627 10.627 0 016.61 21.169a.682.682 0 00-.832.106.222.222 0 00-.055.04c-1.376 1.066-2.725 1.29-3.618 1.29H2.1c1.03-.751 1.68-1.868 2.06-2.735.06-.147.07-.3.035-.442a.712.712 0 00-.167-.385C.314 14.847.506 8.453 4.474 4.485 8.62.34 15.364.34 19.504 4.485c4.15 4.146 4.15 10.884.005 15.03z"></path>
                            <path d="M11.994 12.842a.842.842 0 100-1.684.842.842 0 000 1.684zM16.337 12.842a.842.842 0 100-1.684.842.842 0 000 1.684zM7.65 12.842a.842.842 0 100-1.684.842.842 0 000 1.684z"></path>
                        </svg>
                    </a>
                </li>
                <li class="dashboard-nav__item shake shake--delay-5">
                    <a href="tel:+310858640777" class="dashboard-nav__link p-2 bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" id="i-phone-3" height="20" width="20"
                             class="fill-white" viewBox="0 0 24 24">
                            <path d="M6.492 2.25c-.392 0-.78.14-1.101.398l-.047.024-.024.023L2.977 5.11 3 5.133a2.35 2.35 0 00-.633 2.531c.003.006-.003.018 0 .024.636 1.819 2.262 5.332 5.438 8.507 3.187 3.188 6.747 4.75 8.508 5.438h.023a2.692 2.692 0 002.602-.516l2.367-2.367c.62-.621.62-1.7 0-2.32l-3.047-3.047-.024-.047c-.62-.621-1.722-.621-2.343 0l-1.5 1.5a12.131 12.131 0 01-3.07-2.11c-1.228-1.171-1.855-2.519-2.087-3.046l1.5-1.5c.63-.63.642-1.679-.023-2.297l.023-.024-.07-.07-3-3.094-.023-.023-.047-.024a1.767 1.767 0 00-1.102-.398zm0 1.5c.056 0 .112.026.164.07l3 3.07.07.07c-.005-.005.044.074-.046.165L7.805 9l-.352.328.164.469s.862 2.305 2.672 4.031l.164.14c1.743 1.592 3.797 2.462 3.797 2.462l.469.21 2.226-2.226c.13-.129.106-.129.235 0l3.07 3.07c.129.13.129.082 0 .211l-2.297 2.297c-.346.296-.712.358-1.148.211-1.7-.668-5.001-2.118-7.946-5.062-2.967-2.968-4.517-6.334-5.086-7.97-.114-.304-.032-.755.235-.984l.047-.046 2.273-2.32a.263.263 0 01.164-.071z"></path>
                        </svg>
                    </a>
                </li>
                <li class="dashboard-nav__item shake shake--delay-9">
                    <a href="mailto:info@creoserver.com" class="dashboard-nav__link p-2 bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" id="i-mail-3" height="20" width="20"
                             class="fill-white" viewBox="0 0 24 24">
                            <path d="M23.49 3.83H.5a.51.51 0 00-.5.51v15.32c0 .282.229.51.51.51h22.98a.51.51 0 00.51-.51V4.34a.51.51 0 00-.51-.51zM22.256 4.85l-6.788 6.788L12 15.108l-3.469-3.47-6.788-6.787h20.514zM1.02 5.573L7.448 12l-6.427 6.427V5.573zm.722 13.576l6.427-6.427 3.469 3.469c.178.178.456.266.722 0l3.469-3.469 6.427 6.427H1.743zm21.236-.722L16.552 12l6.427-6.427v12.854z"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </simplebar>
</template>

<script>
import {LOGOUT_PATH} from "../../data/config";
import {mapActions, mapGetters} from "vuex";

export default {
    name: "DashboardMenuRolled",
    methods: {
        ...mapActions([
            'SET_SHOW_CHAT_MODAL',
        ]),
        logout() {
            window.location.href = LOGOUT_PATH
        },
        showChatModal() {
            this.SET_SHOW_CHAT_MODAL(true)
        }
    },
    computed: {
        ...mapGetters([
            'GET_CHAT_UNREAD_MESSAGE',
            'GET_TICKET_UNREAD_MESSAGE',
            'GET_REQUEST_UNREAD',
            'GET_COUNT_PROFORMA',
            'GET_MENU_LEFT_SHOW',
            'GET_LOGO',
        ]),
        actionsNotifications: function () {
            return this.chatUnreadMessage + this.ticketUnreadMessage + this.countProforma;
        },
        chatUnreadMessage: function () {
            return this.GET_CHAT_UNREAD_MESSAGE;
        },
        ticketUnreadMessage: function () {
            return this.GET_TICKET_UNREAD_MESSAGE;
        },
        requestUnread: function () {
            return this.GET_REQUEST_UNREAD;
        },
        logo: function () {
            return this.GET_LOGO
        },
        countProforma: function () {
            return this.GET_COUNT_PROFORMA;
        },
        menuShow: function () {
            return this.GET_MENU_LEFT_SHOW;
        },
    },
}
</script>

<style lang="scss">

.dashboard-nav__item-icon-payment-terms {
    height: 22px;
    width: 22px;
    margin: 3px;
}

</style>
