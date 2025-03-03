<template>
    <div>
        <h1 class="support-center__heading">
            {{ $t('DashboardSupportCenterTitle') }}
        </h1>

        <dl class="support-center__info" v-if="ticket">
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockTicketID') }}</div>
                <div class="c-primary">{{ ticket.id }}</div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockNumber') }}</div>
                <div class="c-primary">{{ ticket.creoNum }}</div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockStatus') }}</div>
                <div class="pl-2 pr-2 bg-secondary c-white"
                     v-html="getStatus(ticket.status)"></div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockDepartment') }}</div>
                <div class="c-primary" v-text="getDepartmentName(ticket.department)"></div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockTopic') }}</div>
                <div class="c-primary">{{ getCause(ticket.cause) }}</div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockDate') }}</div>
                <div class="c-primary" v-text="getDate(ticket.time)"></div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">{{ $t('supportCenterInfoBlockLastUpdate') }}</div>
                <div class="c-primary">{{ timeLastMessage }}</div>
                <!--                v-text="getDate(getLastComment(messages))"-->
            </div>
        </dl>
    </div>
</template>

<script>
import {default as TicketsStatus} from "../../data/tickets-status";
import {default as Departments} from "../../data/departmens";
import {DateTime} from "luxon";

export default {
    name: "ticketPageInfo",
    props: {
        ticket: '',
        timeLastMessage: '',
    },
    methods: {
        getDate(date) {
            if (date != undefined) {
                return DateTime.fromSeconds(date).toFormat('dd-MM-yyyy ~hh:mm')
            }
        },
        getDepartmentName(id) {
            let departmentName = ''
            Departments.filter(department => {
                if (department.id === id) {
                    // departmentName = this.$t(department.name)
                    departmentName = department.name
                }
            })

            return departmentName
        },
        getStatus(id) {
            let statusHtml = '<span class="'

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
    }
}
</script>

<style scoped>

</style>
