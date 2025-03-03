<template>
    <div class="form-select__country">
        <select class="profile-form__select form-select required select-country-customer"
                :name="objectSelect.name"
                @change="changeSelect"
                v-model="objectSelect.selected"
                :class="{
                        'first-input' : classAdd === 'first-input',
                        'last-input' : classAdd === 'last-input',
                }">
            <option v-for="item in objectSelect.value" :value="item.name" :selected="item.name">{{ item.name }}</option>
        </select>
    </div>
</template>

<script>
export default {
    name: "ProfileSelectCountry",
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
    data() {
        return {}
    },
    mounted() {
    },
    methods: {
        changeSelect() {
            this.$emit('changeSelect', this.objectSelect)
        }
    },
    computed: {},
    watch: {}
}
</script>

<style scoped>

</style>
