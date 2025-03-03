<template>
    <div class="support-center">
        <div class="support-center__create-ticket-wrap">
            <h1 class="support-center__heading">
                {{ $t('DashboardSupportCenterTitle') }}

            </h1>
            <div class="row">
                <div class="col-md-5">
                    <div class="create-ticket">
                        <div class="create-ticket__container">
                            <router-link class="btn btn--primary create-ticket__btn" to="/ticket-new">
                                {{ $t('DashboardSupportCenterButton') }}
                            </router-link>
                            <p class="create-ticket__description">{{ $t('communicationCenterTicketInfo') }}
                                <span class="create-ticket__select">{{
                                        $t('communicationCenterTicketInfoStatus')
                                    }}</span>
                            </p>
                        </div>

                    </div>
                </div>
                <div class="col-md-7">
                    <!--                    <article class="support-center__article" v-html="text">-->
                    <!--                    </article>-->
                    <div v-html="text">
                        <!--                        <p class="support-center__article-heading">{{-->
                        <!--                            $t('communicationCenterTicketArticleHeading')-->
                        <!--                            }}-->
                        <!--                        </p>-->
                        <!--                        <p>{{ $t('communicationCenterTicketArticleParagraph1') }}</p>-->
                        <!--                        <p>{{ $t('communicationCenterTicketArticleParagraph2') }}</p>-->
                        <!--                        <p>{{ $t('communicationCenterTicketArticleParagraph3') }}</p>-->
                        <!--                        <p class="support-center__article-company">- CreoServer -</p>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="main-table">
            <table class="main-table__table">
                <thead>
                <tr>
                    <th class="main-table__th main-table__th--id">{{ $t('communicationCenterMainTableTicketID') }}</th>
                    <th class="main-table__th main-table__th--topic">{{ $t('communicationCenterMainTableTopic') }}</th>
                    <th class="main-table__th main-table__th--number">{{
                            $t('communicationCenterMainTableOrderNumber')
                        }}
                    </th>
                    <th class="main-table__th main-table__th--status">{{
                            $t('communicationCenterMainTableOrderStatus')
                        }}
                    </th>
                    <th class="main-table__th main-table__th--department">
                        {{ $t('communicationCenterMainTableOrderDepartment') }}
                    </th>
                    <th class="main-table__th main-table__th--date">{{
                            $t('communicationCenterMainTableOrderDate')
                        }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="ticket in tickets">
                    <td :data-heading="$t('communicationCenterMainTableTicketID')">
                        <router-link :to="getLink(ticket.id)">
                            {{ ticket.id }}
                        </router-link>
                    </td>
                    <td :data-heading="$t('communicationCenterMainTableTopic')">{{ getCause(ticket.cause) }}</td>
                    <td :data-heading="$t('communicationCenterMainTableOrderNumber')">{{ ticket.creoNum }}</td>
                    <td :data-heading="$t('communicationCenterMainTableOrderStatus')">
                        <router-link :to="getLink(ticket.id)" v-html="getStatus(ticket.status)">
                        </router-link>
                    </td>
                    <td :data-heading="$t('communicationCenterMainTableOrderDepartment')">
                        {{ getDepartmentName(ticket.department) }}
                    </td>
                    <td :data-heading="$t('communicationCenterMainTableOrderDate')">{{ getDate(ticket.time) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

<!--        <div class="text-center" v-if="tickets.length >= 5">-->
<!--            <button class="support-center__more btn" type="button">{{ $t('communicationCenterLoadMore') }}</button>-->
<!--        </div>-->
        <div class="text-center py-5 px-5" v-if="tickets.length == 0">
            {{ $t('communicationCenterNull') }}
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'

import {default as TicketsStatus} from '../../data/tickets-status'
import {default as Departments} from '../../data/departmens'

import {DateTime} from 'luxon'

export default {
    data() {
        return {
            tickets: [],
            text: ''
        }
    },
    mounted() {
        this.getTickets()
        this.GET_LOADING_FROM_REQUEST(false)
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getTickets() {
            axios.get('/ticket/new').then((response) => {
                this.tickets = response.data.tickets
                this.text = response.data.text
            })
        },
        getCause($cause) {
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

            return arr.get($cause)
        },
        getStatus(id) {
            let statusHtml = '<span class="support-center__status '

            TicketsStatus.filter(status => {
                if (status.id === id) {
                    statusHtml += status.class
                    statusHtml += '">'
                    statusHtml += status.name
                    // statusHtml += this.$t(status.name)
                    statusHtml += '</span>'
                }
            })

            return statusHtml
        },
        getDepartmentName(id) {
            let departmentName

            Departments.filter(department => {
                if (department.id === id) {
                    // departmentName = this.$t(department.name)
                    departmentName = department.name
                }
            })

            return departmentName
        },
        getLink(id) {
            return {
                name: 'ticket',
                params: {
                    id: id
                }
            }
        },
        getDate(date) {
            return DateTime.fromSeconds(date).toFormat('dd-MM-yyyy ~hh:mm')
        }
    }
}
</script>
