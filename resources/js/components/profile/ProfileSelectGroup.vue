<template>
    <div class="profile-form__field"
         :class="{error: inputGroupData.hasError, success: inputGroupData.isValidate}">

        <select class="profile-form__select"
                @change="changeSelect"
                :class="{required: inputGroupData.required, error: inputGroupData.hasError, success: inputGroupData.isValidate}"
                :name="inputGroupData.name"
                v-model="inputGroupData.selected"
                :placeholder="inputGroupData.placeholder">
            <option v-for="item in inputGroupData.value" :value="item.value"
                    :selected="item.selected">{{ item.text }}
            </option>
        </select>

        <p class="text-danger"
           v-if="inputGroupData.hasError">{{ inputGroupData.errorText }}</p>
    </div>
</template>

<script>
export default {
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
