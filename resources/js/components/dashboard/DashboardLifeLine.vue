<template>
    <aside class="dashboard__lifeline" :class="{ active: menuData }" v-if="!lifeLine">
        <button class="close-widget d-xl-none" @click="$emit('closeWidget')">
            <i class="icon-close"></i>
        </button>
        <div class="lifeline">
            <div class="lifeline__dates">
                <time datetime="" class="lifeline__date">{{ now | dayjs('DD-MM-YYYY') }}</time>
                <time datetime="" class="lifeline__time">{{ now | dayjs('HH:mm:ss') }}</time>
            </div>
            <div class="lifeline__heading">{{ $t('DashboardLifeLineTitle') }}</div>
            <simplebar class="lifeline__orders" data-simplebar-auto-hide="false">
                <template v-for="(event, key) in events">
                    <div class="lifeline__order" v-on:click="modalShow(event)"

                         v-bind:class="[
                                       event.view ? 'has-notify' : '',
                                       event.view === 1 && event.type === 8 ? 'bg-secondary' : '',
                                       ]"
                         >
                        <div class="lifeline__order-dates">
                            <time class="lifeline__order-date" datetime="">{{ event.date | dayjs('DD-MM-YYYY') }}</time>
                            <div>
                                <time class="lifeline__order-time" datetime="">{{ event.date | dayjs('HH:mm') }}</time>
                                <i class="icon-close" v-on:click.stop="deleteLifeLine(event.id)"></i>
                            </div>
                        </div>
                        <div class="d-f-column">
<!--                        <div v-on:click="modalShow(event)" class="d-f-column">-->
                            <template v-if="event.type !== 10 && event.type !== 8">
                                <div class="fs-13">{{ getTitle(event.type) }} : {{ event.title }}</div>
                                <!--                            <p class="lifeline__order-desc" v-if="event.type == 9">{{ event.author }}</p>-->
                                <span class="lifeline__order-desc">{{ event.author }}</span>
                            </template>
                            <template v-else-if="event.type == 8">
                                <div class="fs-13">Live chat geopend {{ getCauseLiveChat(event.title) }}</div>
                                <span class="lifeline__order-desc">{{ event.author }}</span>

                            </template>
                            <template v-else>
                                <div class="fs-13">Offerte aanvragen</div>
                                <span class="lifeline__order-desc">Category: {{ event.author }}</span>
                                <span class="lifeline__order-desc">Title: {{ event.title }}</span>
                            </template>
                        </div>
                    </div>
                </template>

                <lifeline-order v-if="type === 0" ref="order" :eventsStatus="eventsStatus"/>
                <lifeline-r-m-a v-if="type == 1" ref="rma"/>
                <lifeline-chats v-if="type == 8" ref="chat"/>
                <lifeline-tickets v-if="type == 9" ref="ticket"/>
                <lifeline-proforma v-if="type == 10" ref="request"/>
            </simplebar>

            <div class="lifeline__ending">
                <a class="lifeline__ending-btn" href="#" @click.prevent="clearLifeline">Wis de Lifeline</a>
            </div>
        </div>
    </aside>
</template>

<script>

import {mapGetters} from "vuex";
import LifelineOrder from "../lifeline/lifelineOrder";
import LifelineRMA from "../lifeline/lifelineRMA";
import LifelineTickets from "../lifeline/lifelineTickets";
import LifelineChats from "../lifeline/lifelineChats";
import LifelineProforma from "../lifeline/lifelineProforma";
import {default as lifeline} from '../../data/lifeline';

export default {
    components: {LifelineProforma, LifelineChats, LifelineTickets, LifelineRMA, LifelineOrder},
    props: {
        menuData: {
            type: Boolean,
            required: true
        },
    },
    data() {
        return {
            type: '',
            now: new Date(),
            events: [],
            eventsStatus: [],
        }
    },
    computed: {
        ...mapGetters([
            'GET_LIFELINE'
        ]),
        lifeLine() {
            return this.GET_LIFELINE;
        }
    },
    created() {
        this.aClockTick()
    },
    mounted() {
        this.getLifeLineAll(true)

        this.$root.$on('NewLifeLineCustomer', (data) => {
            this.getLifeLineAll()
        })
        this.$root.$on('ChangeStatusOrderUser', (data) => {
            this.getLifeLineAll()
        })
    },
    methods: {
        getCauseLiveChat($cause) {
            let arr = new Map([
                [1, 'Product vraag'],
                [2, 'Prijs opvragen'],
                [3, 'Factuur vraag'],
                [4, 'Technische vraag'],
                [5, 'Voorraad vraag'],
                [6, 'Transport vraag'],
                [7, 'Anders'],
            ])

            return arr.get(+$cause)
        },
        getLifeLineAll(getStatus = false) {
            this.events = []
            axios.get('/lifeline').then((response) => {
                this.events = response.data

                if (getStatus) {
                    this.getOrderStatus()
                }
            }).catch((e) => {
                console.log(e)
            })
        },
        getOrderStatus() {
            axios.get('/lifeline/order/status').then((response) => {
                this.eventsStatus = response.data
            }).catch((e) => {
                console.log(e)
            })
        },
        aClockTick() {
            this.now = _dayjs()
            setTimeout(this.aClockTick, Math.random() * 500)
        },
        getCause(event) {
            let arr = new Map([
                [1, 'Ik heb een vraag over een product'],
                [2, 'Ik heb een vraag over de voorraad'],
                [3, 'Ik heb heb een technische vraag'],
                [4, 'Ik heb een vraag over de garantie'],
                [5, 'Ik heb een vraag over de levering'],
                [6, 'Ik heb een administratieve vraag'],
                [7, 'Ik wil een product aan jullie aanbieden'],
                [8, 'Ik heb een vraag over dedicated hosten'],
            ])
            return arr.get(+event)
        },
        modalClose(event) {
            console.log('Steps')
            // this.$vuedals.close();
        },
        clearLifeline() {
            this.deleteLifeLine('all')

        },
        deleteLifeLine(value) {
            axios.delete(`/lifeline/${value}`).then((response) => {
                this.getLifeLineAll()
            }).catch((e) => {
                console.log(e)
            })
        },
        getTitle(type) {
            let arr = new Map([
                [0, 'Order afgeleverd'],
                [1, 'RMA aangemaakt'],
                [8, 'Live chat geopend'],
                [9, 'Ticket'],
                [10, 'Proforma aanvraag'],
                [20, 'New Offerte'],
                [21, 'Update cart'],
            ])
            return arr.get(type)
        },
        modalShow(event) {
            if (event.type == lifeline.offerte_frame || event.type == lifeline.factuur_frame) {
                return ''
            }
            this.type = event.type
            axios.get(`lifeline/type/${event.type}/value/${event.value}`).then((response) => {
                if (response.data) {
                    switch (event.type) {
                        case lifeline.order:
                            this.$refs.order.modalShow(response.data)
                            break
                        case lifeline.rma:
                            this.$refs.rma.modalShowRMA(response.data)
                            break
                        case lifeline.chat:
                            this.$refs.chat.modalShowLiveChat(response.data)
                            break
                        case lifeline.ticket:
                            this.$refs.ticket.modalShowTicket(response.data)
                            break
                        case lifeline.request:
                            this.$refs.request.modalShowRequest(response.data)
                            break
                        default:
                            break
                    }
                }
            }).catch((e) => {
                console.log(e)
            })
        }
    },

}
</script>
