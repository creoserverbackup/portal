<template>
    <div class="el1full" v-bar @mousemove="mouseDown" :class="{'mobile-container': isMobile}">
        <div class="el1full__main" v-on:scroll="scrollHandler" ref="scrolledContainer">
            <div class="container-fluid full" :class="{scrolled:isScroll}">
                <div class="overlay" v-if="menu.isOverlayActive" v-on:click="closeMenu"></div>
                <selectBackground :secondBackground="true" :hasSorting="hasSorting"/>

                <div class="dashboard"
                     v-bind:class="[
                     menuShow ? '':'dashboard-menu-rolled',
                     menu.disabledLifeLine ? 'is-lifeline-hidden': ''
                     ]">
                    <ScreenLoader/>

                    <DashboardMenu :menuData="menu.isMenuActive" @closeWidget="closeMenu"
                                   v-if="!($route.path.indexOf('frame') > -1) && !isMobile"/>

                    <dashboard-menu-rolled v-else-if="isMobile" class="mobile_rolled_menu" />

                    <main class="dashboard__main" :class="dashboardMainClass">
                        <div class="dashboard-header">
                            <DashboardHeader :menuData="menu"/>
                        </div>

                        <router-view class="products-list-catalog"/>
                    </main>
                    <DashboardLifeLine v-if="!menu.disabledLifeLine && !($route.path.indexOf('frame') > -1)"
                                       :menuData="menu.isLifeLineActive" @closeWidget="closeMenu"/>
                </div>
                <vuedal/>
                <a href="#" class="scroll-top" @click.prevent="scrollUp"
                   :class="{
                        visible: isScroll,
                        'scroll-top__is-lifeline-hidden': menu.disabledLifeLine,
                        }"
                   >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="up">
                        <path
                            d="M263.542 173.792c-4.167-4.167-10.917-4.167-15.083 0L131.125 291.125c-4.167 4.167-4.167 10.917 0 15.083 4.167 4.167 10.917 4.167 15.083 0L256 196.417l109.792 109.792a10.634 10.634 0 0 0 7.542 3.125c2.729 0 5.458-1.042 7.542-3.125 4.167-4.167 4.167-10.917 0-15.083L263.542 173.792z"></path>
                    </svg>
                </a>
            </div>
        </div>
        <CelebrationPopup/>
        <div ref="infiniteScrollTrigger"></div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import {setTheme} from '../../plugins/dark-mode';

import Vue from 'vue';
import Vuebar from 'vuebar';
import CelebrationPopup from "../../components/dashboard/CelebrationPopup";
import DashboardHeader from "../../components/dashboard/DashboardHeader";
import DashboardMenu from "../../components/dashboard/DashboardMenu";
import DashboardLifeLine from "../../components/dashboard/DashboardLifeLine";
import DashboardMenuRolled from "../../components/dashboard/DashboardMenuRolled.vue";

Vue.use(Vuebar);

export default {
    name: 'DashboardLayout',
    components: {
        DashboardMenuRolled,
        DashboardMenu,
        DashboardHeader,
        CelebrationPopup,
        DashboardLifeLine

    },

    data() {
        return {
            menu: {
                isOverlayActive: false,
                isMenuActive: false,
                isLifeLineActive: false,
                disabledLifeLine: false,
            },
            isScroll: false,
            sendStatistic: true,
            timer: null
        }
    },
    mounted() {
        setTheme(this.GET_THEME);
        this.menu.disabledLifeLine = this.GET_LIFELINE;
        document.body.classList.add('vuebar');
        this.$root.$on('paginate', () => {
            this.scrollUp();
        })

        if (this.$route.path.indexOf('frame') > -1) {
            this.menu.disabledLifeLine = true
        }

        this.timer = setInterval(() => {
            this.updateStatistic()
        }, 30000);

    },

    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        scrollHandler(e) {
            if (e.target.scrollTop == 0) {
                this.isScroll = false;
            } else {
                this.isScroll = true;
            }
            this.mouseDown()
        },
        closeMenu() {
            this.menu.isOverlayActive = false;
            this.menu.isMenuActive = false;
            this.menu.isLifeLineActive = false;
        },
        scrollUp() {
            let scroll_element = this.$refs.scrolledContainer;
            let scroll_speed = 200;
            let screen_Y = Math.floor(scroll_element.scrollTop);
            if (screen_Y > 0) {
                let myWindowScroll = setInterval(function () {
                    screen_Y = screen_Y - scroll_speed;
                    if (screen_Y <= 0) {
                        clearInterval(myWindowScroll);
                        scroll_element.scrollTo(0, 0);
                        return;
                    }
                    scroll_element.scrollTo(0, screen_Y);
                }, 3);
            }
        },
        mouseDown() {
            // if (this.sendStatistic) {
            //     this.sendStatistic = false
            //     setTimeout(this.allowStatistic, 10000)
            //     this.updateStatistic()
            // }
        },
        allowStatistic() {
            this.sendStatistic = true
        },
        updateStatistic() {
            if (!this.$route.fullPath.includes('/frame')) {
                axios.post('/statistic/visits', {
                    to: this.$route.fullPath,
                    path: this.$route.fullPath,
                }).then((response) => {
                    if (response.data.redirect === true) {
                        // window.location.href = "login"
                    }
                }).catch((errors) => {
                    console.log(errors)
                })
            }
        }
    },
    computed: {
        ...mapGetters([
            'GET_THEME',
            'GET_LIFELINE',
            'GET_MENU_LEFT_SHOW',
            'isMobile'
        ]),
        isFreezeBody: function () {
            return this.menu.isOverlayActive;
        },
        hasSorting: function () {
            return (this.$route.path === '/dashboard');
        },

        dashboardMainClass() {
            let sidebar = false, lifeline = false, classList = [];

            if (!(this.$route.path.indexOf('frame') > -1)) {
                sidebar = true;
            }

            if (!this.menu.disabledLifeLine && !(this.$route.path.indexOf('frame') > -1) && !this.GET_LIFELINE) {
                lifeline = true;
            }

            if (sidebar && lifeline) {
                classList.push('dashboard__main--two-column')
            } else if (sidebar || lifeline) {
                classList.push('dashboard__main--one-column')
            }

            return classList
        },
        menuShow: function () {
            return this.GET_MENU_LEFT_SHOW;
        },
    },
    watch: {
        menu: {
            deep: true,
            handler(value) {
                console.log(value)
            }
        },
        isFreezeBody: function (status) {
            let bodyClass = 'is-freeze';

            if (status) {
                document.body.classList.add(bodyClass);
            } else {
                document.body.classList.remove(bodyClass);
            }
        },
        $route(to, from) {
            this.GET_LOADING_FROM_REQUEST(true);
            if (from.path === to.path) {
                this.$router.go()
            }
        }
    },
    destroyed() {
        clearInterval(this.timer);
    }
}
</script>
