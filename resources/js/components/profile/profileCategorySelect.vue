<template>
    <div class="profile-form__field"
         :class="{error: inputGroupData.hasError, success: inputGroupData.isValidate}">

        <select class="profile-form__select"
                @change="changeSelect"
                :class="{required: inputGroupData.required, error: inputGroupData.hasError, success: inputGroupData.isValidate}"
                :name="inputGroupData.categoryName"
                v-model="inputGroupData.selected"
                :placeholder="inputGroupData.placeholder">
            <option v-for="item in inputGroupData.value" :value="item.categoryId"
                    :selected="item.selected">{{ item.categoryName }}
            </option>
        </select>

        <p class="text-danger"
           v-if="inputGroupData.hasError">{{ inputGroupData.errorText }}</p>
    </div>
</template>

<script>
export default {
    name: "profileCategorySelect",
    props: {
        inputGroupData: {
            type: Object,
            required: true
        },
    },
    methods: {
        changeSelect() {
            this.$emit('changeSelect', this.inputGroupData.name, this.inputGroupData)
        }
    },
}
</script>
