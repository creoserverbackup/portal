<template>
<!--&lt;!&ndash;    active: menuData&ndash;&gt;  old class  -->
    <aside v-bind:class="[menuShow == true ? 'dashboard__sidebar' : 'dashboard__sidebar-rolled pr-2']">
<!--        <button class="close-widget d-xl-none" @click="$emit('closeWidget')">-->
<!--            <i class="icon-close"></i>-->
<!--        </button>-->
        <dashboard-menu-show-hide />
        <dashboard-menu-open v-if="menuShow"/>
        <dashboard-menu-rolled v-else />


    </aside>
</template>

<script>
import {mapGetters} from "vuex";
import DashboardMenuShowHide from "./DashboardMenuShowHide";
import DashboardMenuOpen from "./DashboardMenuOpen";
import DashboardMenuRolled from "./DashboardMenuRolled";

export default {
    props: {
        menuData: {
            type: Boolean,
            required: true
        },
    },
    components: {
        DashboardMenuShowHide,
        DashboardMenuOpen,
        DashboardMenuRolled,
    },
    computed: {
        ...mapGetters([
            'GET_MENU_LEFT_SHOW'
        ]),
        menuShow() {
            return this.GET_MENU_LEFT_SHOW;
        },
    },
}
</script>
