<template>
    <div class="dashboard__sidebar-main scroll">
        <div class="inner-logo">
            <router-link to="/" class="inner-logo__img-wrap">
                <img class="inner-logo__img img-responsive" alt="logotype"
                     v-bind:src="[logo != '' ? logo : 'images/logo.png']">
            </router-link>
        </div>
        <CustomerWelcome/>
        <nav class="dashboard__nav dashboard-nav dashboard-nav--thumbs">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item">
                    <router-link class="dashboard-nav__link" to="/catalog" :data-title="$t('navbarCatalog')">
                        <i class="dashboard-nav__icon icon-speed"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <!--                        has-notify-->
                    <router-link class="dashboard-nav__link" to="/contact-center"
                                 :data-title="$t('navbarContactCenter')">
                        <i class="dashboard-nav__icon icon-chat"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link class="dashboard-nav__link" to="/profile-settings"
                                 :data-title="$t('navbarProfile')">
                        <i class="dashboard-nav__icon icon-man"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">
                    <router-link class="dashboard-nav__link" to="/profile" :data-title="$t('navbarProfilePortal')">
                        <i class="dashboard-nav__icon icon-settings"></i>
                    </router-link>
                </li>

                <li class="dashboard-nav__item">
                    <router-link class="dashboard-nav__link" to="/order-centrum"
                                 :data-title="$t('navbarSetSettings')">
                        <!--                        <router-link class="dashboard-nav__link" to="/order-center" :data-title="$t('navbarSetSettings')">-->
                        <i class="dashboard-nav__icon icon-file"></i>
                    </router-link>
                </li>
                <li class="dashboard-nav__item">

                    <div class="dashboard-nav__link" @click="logout()" :data-title="$t('navbarLogout')">
                        <i class="dashboard-nav__icon icon-logout"></i>
                    </div>

                    <!--                        <a href="/logout" class="dashboard-nav__link" :data-title="$t('navbarLogout')">-->
                    <!--                            <i class="dashboard-nav__icon icon-logout"></i>-->
                    <!--                        </a>-->
                </li>
            </ul>
        </nav>

        <nav class="dashboard__nav dashboard-nav">
            <ul class="dashboard-nav__list">
                <li class="dashboard-nav__item">
                    <router-link to="/interactive-center" class="dashboard-nav__link"
                                 :class="{'has-notify': actionsNotifications}">
                        <i class="dashboard-nav__icon icon-start-button"></i>
                        <span class="dashboard-nav__text">{{ $t('navbarActionCenter') }}</span>
                    </router-link>
                    <small class="dashboard-nav__notification" v-if="actionsNotifications">{{
                            actionsNotifications
                        }}</small>
                </li>
            </ul>
        </nav>
        <dashboard-menu-page-button-top :requestUnread="requestUnread"/>
        <dashboard-menu-page-button-center/>
        <dashboard-menu-page-button-bottom/>

        <TimeTable/>
        <div class="dashboard__sidebar-copy">
                <span class="dashboard__sidebar-copy td-n">Customer Portal {{
                        version()
                    }} </span>
            <span>Alle rechten voorbehouden CreoServer {{ Date.now() | dayjs('YYYY') }}</span>
            <span>Powerd by <a href="https://kodansoft.com/" target="_blank">Kodansoft</a></span>
        </div>

    </div>
</template>

<script>
import DashboardMenuPageButtonTop from "./DashboardMenuPageButtonTop";
import DashboardMenuPageButtonCenter from "./DashboardMenuPageButtonCenter";
import DashboardMenuPageButtonBottom from "./DashboardMenuPageButtonBottom";
import {mapGetters} from "vuex";
import TimeTable from "./TimeTable";
import CustomerWelcome from "./CustomerWelcome";
import {LOGOUT_PATH} from "../../data/config";
export default {
    name: "DashboardMenuOpen",
    components: {
        DashboardMenuPageButtonBottom,
        DashboardMenuPageButtonCenter,
        DashboardMenuPageButtonTop,
        TimeTable,
        CustomerWelcome
    },
    methods: {
        logout() {
            window.location.href = LOGOUT_PATH
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
        actionsNotifications() {
            return this.chatUnreadMessage + this.ticketUnreadMessage + this.countProforma;
        },
        chatUnreadMessage() {
            return this.GET_CHAT_UNREAD_MESSAGE;
        },
        ticketUnreadMessage() {
            return this.GET_TICKET_UNREAD_MESSAGE;
        },
        requestUnread() {
            return this.GET_REQUEST_UNREAD;
        },
        logo() {
            return this.GET_LOGO
        },
        countProforma() {
            return this.GET_COUNT_PROFORMA;
        },
        menuShow() {
            return this.GET_MENU_LEFT_SHOW;
        },
    },
}
</script>

<style scoped>

</style>