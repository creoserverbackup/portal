<template>
    <div class="dashboard__menu-show-hide">
        <div class="dashboard__menu-show-hide-area">
            <div class="dashboard__menu-show-hide-arrow" @click="changeShowMenu()">
                <span class="dashboard__menu-show-hide-arrow-toggle" v-if="menuShow">
                    <svg enable-background="new 0 0 30.727 30.727" version="1.1" viewBox="0 0 30.727 30.727"
                     xml:space="preserve"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="m29.994 10.183-14.631 14.629-14.63-14.628c-0.977-0.978-0.977-2.561 0-3.536 0.977-0.977 2.559-0.976 3.536 0l11.095 11.093 11.097-11.094c0.977-0.976 2.559-0.976 3.535 0 0.975 0.977 0.975 2.559-2e-3 3.536z"></path>
                    </svg>
                </span>
                <span class="dashboard__menu-show-hide-arrow-toggle dashboard__menu-show-hide-arrow-toggle-close" v-else>
                    <svg enable-background="new 0 0 30.727 30.727" version="1.1" viewBox="0 0 30.727 30.727"
                         xml:space="preserve"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="m29.994 10.183-14.631 14.629-14.63-14.628c-0.977-0.978-0.977-2.561 0-3.536 0.977-0.977 2.559-0.976 3.536 0l11.095 11.093 11.097-11.094c0.977-0.976 2.559-0.976 3.535 0 0.975 0.977 0.975 2.559-2e-3 3.536z"></path>
                    </svg>
                </span>
            </div>
        </div>
    </div>

</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "DashboardMenuShowHide",
    data() {
        return {
            widthWindow: 0
        }
    },
    created() {
        this.widthWindow = window.innerWidth
        if (this.widthWindow <= 1440) {
            this.SET_MENU_LEFT_SHOW(false)
        }
    },
    methods: {
        ...mapActions([
            'SET_MENU_LEFT_SHOW',
        ]),
        changeShowMenu() {
            this.SET_MENU_LEFT_SHOW(!this.menuShow)
        }
    },
    computed: {
        ...mapGetters([
            'GET_MENU_LEFT_SHOW',
        ]),
        menuShow() {
            return this.GET_MENU_LEFT_SHOW;
        },
    },
}
</script>

<style scoped>

</style>