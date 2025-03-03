<template>
    <div class="mt-3">
        <div class="select-form">
            <div class="select-form__field">
                <label class="select-form__label f-s-17rem">{{ $t('DashboardChatLiveLabel1') }}</label>
                <div class="select-form__select-arrow">
                    <v-select
                            placeholder="Maak een keuze..."
                            required
                            v-model="cause"
                            :reduce="(cause) => cause.value"
                            :options="causeOptions">
                    </v-select>
                </div>
            </div>
        </div>

        <div class="select-form__field">
            <label class="select-form__label f-s-17rem">{{ $t('DashboardChatLiveLabel2') }}</label>
            <div class="select-form__select-arrow">
                <v-select
                        placeholder="Maak een keuze..."
                        required
                        v-model="department"
                        :reduce="(department) => department.value"
                        :options="departmentOptions">
                </v-select>
            </div>
        </div>

        <div class="select-form__field">
            <label class="select-form__label f-s-17rem">{{ $t('DashboardChatLiveLabel3') }}</label>
            <div class="select-form__select-arrow">
                <v-select
                        placeholder="Orders ..."
                        required
                        v-model="order"
                        :reduce="(order) => order.orderId"
                        :options="orders">
                </v-select>
            </div>
        </div>

        <button type="submit" class="w-100 btn btn--secondary" v-on:click="getChatConsult"
                :disabled="cause === '' || department === ''">{{
                $t('DashboardChatLiveButton')
            }}
        </button>

        <p class="mt-3">{{ $t('DashboardChatLiveHelper') }}</p>
    </div>
</template>

<script>

import Vue from 'vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css';

export default {
    name: "chatPageForm",
    data() {
        return {
            cause: '',
            causeOptions: [
                {label: 'Product vraag', value: 1},
                {label: 'Prijs opvragen', value: 2},
                {label: 'Factuur vraag', value: 3},
                {label: 'Technische vraag', value: 4},
                {label: 'Voorraad vraag', value: 5},
                {label: 'Transport vraag', value: 6},
                {label: 'Anders', value: 7},
            ],

            department: '',
            departmentOptions: [
                {label: 'Sales', value: 1},
                {label: 'Inkoop', value: 2},
                {label: 'Technische dienst', value: 3},
                {label: 'Financiële administratie', value: 4},
                {label: 'RMA & Ticket support', value: 5},
                {label: 'Logistiek', value: 6},
            ],

            order: '',
            orders: [],
        }
    },
    mounted() {
        this.getOrders()
    },
    methods: {
        getOrders() {
            axios.get('/chat/orders').then((response) => {
                this.orders = response.data
            })
        },
        getChatConsult() {
            if ((this.department === 'undefined' || this.department === '') ||
                    (this.cause === 'undefined' || this.cause === '')) {
                this.$root.$emit('popupMessages', 'Fill the form')
            } else {
                axios.post('/chat/live', {
                    cause: this.cause,
                    department: this.department,
                    orderId: this.order,
                    employeeUid: ''
                }).then((response) => {
                    this.$emit('setChat', response.data)
                })
            }
        },
        setChat(data) {
            this.$emit('setChat', data)
        }
    }
}
</script>

<style scoped>

</style>