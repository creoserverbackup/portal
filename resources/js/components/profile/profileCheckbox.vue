<template>
    <div class="checkbox-label profile-form__checkbox">
        <input class="checkbox-label__input" type="checkbox"
               :name="inputGroupData.name"
               :id="inputGroupData.name"
               v-model="inputGroupData.status">
        <label class="checkbox-label__main"
               :for="inputGroupData.name"
               v-html="inputGroupData.text"></label>
    </div>
</template>

<script>
export default {
    name: "profileCheckbox",
    props: {
        inputGroupData: {
            type: Object,
            required: true
        }
    }
}
</script>
