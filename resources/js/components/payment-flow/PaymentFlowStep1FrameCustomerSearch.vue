<template>
    <div class="search-form__field">
        <input class="search-form__input" v-bind:list="customer.name"
               v-model="searchWord"
               :placeholder="customer.placeholder"
               @input="searchCustomer()">
        <button class="search-form__submit" ref="sendWord" v-on:click="searchCustomer()">
            <i class="icon-search"></i>
        </button>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep1FrameCustomerSearch",
    data() {
        return {
            searchWord: '',
            customer: {
                name: 'customer',
                selected: '',
                placeholder: 'Zoek op klantnaam of klantnummer ...',
                value: [],
            },
        }
    },
    mounted() {
        this.searchCustomer()
    },
    methods: {
        clearSearch()
        {
            this.searchWord = ''
            this.searchCustomer()
        },
        searchCustomer() {
            this.$emit('startSearchCustomer', this.searchWord)
        },
    },
}
</script>

<style scoped>

</style>