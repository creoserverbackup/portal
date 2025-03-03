<template>
    <div class="dashboard-roof row m-0 p-0">
        <div class="dashboard-roof__main m-0 p-0"  :class="{'container': isMobile}">
            <slot></slot>
        </div>
        <div class="dashboard-roof__basket"></div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "LayoutCenter",
    computed: {
        ...mapGetters(['isMobile'])
    }
}
</script>

<style scoped>

</style>
