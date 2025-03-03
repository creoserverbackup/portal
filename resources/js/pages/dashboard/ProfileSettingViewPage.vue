<template>
<!--    <layout-center>-->
        <profile-setting-view-main/>
<!--    </layout-center>-->
</template>

<script>
import LayoutCenter from "./LayoutCenter";
import ProfileSettingViewMain from "../../components/profile/ProfileSettingViewMain";

export default {
    name: "ProfileSettingViewPage",
    components: {
        ProfileSettingViewMain,
        LayoutCenter,
    },
}
</script>

<style scoped>

</style>