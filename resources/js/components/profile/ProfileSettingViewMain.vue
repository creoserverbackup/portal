<template>
    <div>
        <p class="fs-14">{{ $t('SetupStepTwoText') }}</p>
        <profile-setting-view-data/>
    </div>
</template>

<script>
import ProfileSettingViewData from "./ProfileSettingViewData";

export default {
    name: "ProfileSettingViewMain",
    components: {
        ProfileSettingViewData,
    }
}
</script>