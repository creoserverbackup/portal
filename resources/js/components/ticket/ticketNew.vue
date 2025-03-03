<template>
    <div class="ticket-new">
        <div class="d-f-column mb-5">
            <span class="fs-35 c-primary">{{ $t('DashboardSupportCenterTitle') }}</span>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="mb-2">
                    <div class="fs-15 c-gray h-30px">{{ $t('DashboardSupportNameLabel1') }}:</div>
                    <select class="profile-form__select" v-model="departmentTicket">
                        <option v-for="option in department.options" v-bind:value="option.value"
                                :disabled="option.value == ''">{{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="mb-2">
                    <div class="fs-15 c-gray">{{ $t('DashboardSupportNameLabel2') }}:</div>
                    <select class="profile-form__select" v-model="causeTicket">
                        <option v-for="option in cause.options" v-bind:value="option.value"
                                :disabled="option.value == ''">{{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="mb-2">
                    <div class="fs-15 c-gray">{{ $t('DashboardSupportNameLabel3') }}?</div>
                    <select v-if="orders.length > 0" class="profile-form__select" v-model="orderId">
                        <option v-for="option in orders" v-bind:value="option.orderId"
                                :disabled="option.value == ''">{{ option.creoNum }}
                        </option>
                    </select>
                    <input v-else type="text" v-model.trim="orderId"
                           :placeholder="$t('DashboardSupportNamePlaceholder3')">
                </div>
            </div>
            <div class="col-md-7">
                <ticket-new-file/>
            </div>
        </div>
        <textarea class="h-450 mt-5 b-secondary" v-model.trim="description"></textarea>

        <button class="w-100 btn btn--secondary" type="button" v-on:click="submitTicket()">{{ $t('newTicketButton') }}
        </button>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import TicketNewFile from "./ticketNewFile";

export default {
    name: "ticketNew",
    components: {
        TicketNewFile,
    },
    data() {
        return {
            files: [],
            orders: [],
            departmentTicket: '',
            causeTicket: '',
            department: {
                label: this.$t('DashboardChatLiveLabel1'),
                options: [
                    {
                        text: 'Maak een keuze...',
                        value: ''
                    },
                    {
                        text: 'Sales',
                        value: 1
                    },
                    {
                        text: 'Inkoop',
                        value: 2
                    },
                    {
                        text: 'Technische dienst',
                        value: 3
                    },
                    {
                        text: 'Administratie',
                        value: 4
                    },
                    {
                        text: 'Logistiek',
                        value: 5
                    },
                ]
            },
            cause: {
                label: this.$t('DashboardChatLiveLabel1'),
                options: [
                    {
                        text: 'Maak een keuze...',
                        value: ''
                    },
                    {
                        text: 'Ik heb een vraag over een product',
                        value: 1
                    },
                    {
                        text: 'Ik heb een vraag over de voorraad',
                        value: 2
                    },
                    {
                        text: 'Ik heb heb een technische vraag',
                        value: 3
                    },
                    {
                        text: 'Ik heb een vraag over de garantie',
                        value: 4
                    },
                    {
                        text: 'Ik heb een vraag over de levering',
                        value: 5
                    },
                    {
                        text: 'Ik heb een administratieve vraag',
                        value: 6
                    },
                    {
                        text: 'Ik wil een product aan jullie aanbieden',
                        value: 7
                    },
                    {
                        text: 'Ik heb een vraag over dedicated hosten',
                        value: 8
                    },
                ]
            },
            orderId: '',
            description: '',
        }
    },
    mounted() {
        this.getOrders()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getOrders() {
            axios.get('/order/rma').then((response) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.orders = response.data.orders
                if (this.orders.length == 0) {
                    this.$root.$emit('popupMessages', 'Orders not found')
                }
            })
        },
        submitTicket() {
            if ((typeof this.department === 'undefined' || this.department === '')
                    || (typeof this.causeTicket === 'undefined' || this.causeTicket === '')) {
                this.$root.$emit('popupMessages', 'Fill the form')
            } else {
                let formData = new FormData()
                for (let i = 0; i < this.files.length; i++) {
                    let file = this.files[i]

                    formData.append('files[' + i + ']', file)
                }
                formData.append('department', this.departmentTicket)
                formData.append('cause', this.causeTicket)
                formData.append('order', this.orderId)
                formData.append('description', this.description)
                this.GET_LOADING_FROM_REQUEST(true)
                axios.post('/ticket/new',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                ).then((ticket) => {
                    this.GET_LOADING_FROM_REQUEST(false)
                    this.$root.$emit('NewLifeLineCustomer')
                    window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/ticket/" + ticket.data.id;
                }).catch((e) => {
                    this.GET_LOADING_FROM_REQUEST(false)
                    this.$root.$emit('popupMessages', e.response.data.message)
                    // this.error = e.response.data
                    console.log(e)
                })
            }
        },
    }
}
</script>
