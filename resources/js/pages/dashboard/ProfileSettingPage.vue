<template>
    <layout-center>
        <profile-main/>
    </layout-center>
</template>

<script>

import LayoutCenter from "./LayoutCenter";
import ProfileMain from "../../components/profile/ProfileMain";

export default {
    name: "ProfileSettingPage",
    components: {
        ProfileMain,
        LayoutCenter,
    },
}
</script>
<style scoped>
</style>
