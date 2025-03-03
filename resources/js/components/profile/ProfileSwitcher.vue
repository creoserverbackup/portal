<template>
    <div class="checkbox-switcher">
        <input type="checkbox" class="checkbox-switcher__checkbox"
                    :name="inputGroupData.name"
                    :id="inputGroupData.name"
                    :disabled="inputGroupData.isDisabled"
                    v-model="inputGroupData.checked">
        <label class="checkbox-switcher__label"
                    :for="inputGroupData.name">
            <div v-html="inputGroupData.text"></div>
            <div class="checkbox-switcher__switch">
                <div class="checkbox-switcher__switch-on">On</div>
                <div class="checkbox-switcher__switch-off">Off</div>
            </div>
        </label>
    </div>
</template>

<script>
    export default {
        props: {
            inputGroupData: {
                type: Object,
                required: true
            }
        }
    }
</script>

