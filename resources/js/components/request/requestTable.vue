<template>
    <div class="request__table-container col-md-12">
        <table class="request__table">
            <thead>
            <tr>
                <th>{{ $t('requestMainTableID') }}</th>
                <th>{{ $t('requestMainTableName') }}</th>
                <th>{{ $t('requestMainTableTitle') }}</th>
                <th>{{ $t('requestMainTableCategory') }}</th>
                <th class="w-10">{{ $t('requestMainTableLastSend') }}</th>
                <th class="Frequest__table-message">{{ $t('requestMainTableLastText') }}</th>
                <th class="w-10">{{ $t('requestMainTableDate') }}</th>
                <th class="w-10">{{ $t('requestMainTableAction') }}</th>
            </tr>
            </thead>
            <tr v-for="request in requests" class="request__table-tr"
                :key="request.id"
                v-bind:class="[request.read ? 'read' : 'no_read']" @click="goToUserMessage(request.id)">
                <td>{{ request.id }}</td>
                <td>{{ request.username }}</td>
                <td>{{ request.title }}</td>
                <td>{{ request.categoryName }}</td>
                <td class="w-50">{{ request.last ? request.last.username : '' }}</td>
                <td class="request__table-message" v-html="request.last ? request.last.message : ''"></td>
                <td>{{ date(request.time) }} - {{ time(request.time) }}</td>
                <td>
                    <button class="p-2 pl-3 pr-3">
                        Actieve aanvraag chat
                    </button>
                </td>
            </tr>
        </table>
    </div>

</template>

<script>
import {DateTime} from "luxon";

export default {
    name: "requestTable",
    props: {
        requests: {},

    },
    methods: {
        date(data) {
            return DateTime.fromSeconds(data).toFormat('dd-MM-yyyy')
        },
        time(data) {
            return DateTime.fromSeconds(data).toFormat('HH:mm')
        },
        getLink(id) {
            return {
                name: 'request',
                params: {
                    id: id
                }
            }
        },
        goToUserMessage(id) {
            return this.$router.push(this.getLink(id))
        },
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/abstracts/variables";
.request__table-container {
    overflow-x: auto;
}
.request__table {
    @media (max-width: $xlg) {
        font-size: 12px;
    }
}
</style>
