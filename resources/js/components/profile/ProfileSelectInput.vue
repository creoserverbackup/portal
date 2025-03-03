<template>
    <div class="form-select__country">
        <input class="profile-form__select form-select" v-bind:list="objectSelect.name"
               v-model="objectSelect.selected"
               :disabled="objectSelect.disabled"
               :placeholder="objectSelect.placeholder"
               @change="changeSelect"
               :class="{
                                'first-input' : classAdd === 'first-input',
                                'last-input' : classAdd === 'last-input',
                                'required': required }" >
        <datalist v-bind:id="objectSelect.name">
            <option v-for="item in objectSelect.value" :value="item.name" :selected="false"></option>
        </datalist>
    </div>
</template>

<script>
export default {
    props: {
        objectSelect: {
            type: Object,
        },
        classAdd: {
            type: String,
        },
        required: {
            default: true,
            type: Boolean,
        },
    },
    methods: {
        changeSelect() {
            this.$emit('changeSelect', this.objectSelect)
        }
    },
};
</script>
