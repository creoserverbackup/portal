<template>
</template>

<script>
import {Bus as VuedalsBus} from "vuedals";

export default {
    name: "lifelineChats",
    data() {
        return {
            chat: '',
            eventsStatusLiveChat: [
                {
                    id: 1,
                    name: 'Live chat made',
                },
                {
                    id: 2,
                    name: 'Live chat closed',
                },
            ],
        }
    },
    methods: {
        createStatusLiveChat(statusId) {
            let html = '<ul class="modal-info__stages stages">'
            this.eventsStatusLiveChat.forEach(item => {
                html += '<li class="stages__stage'
                html += (statusId > item.id ? ' active' : '')
                html += (statusId == item.id ? ' last' : '')
                html += `">
                            <div class="stages__stage-step">${item.id}</div>
                            <span class="stages__stage-desc">${item.name}</span>
                        </li>`
            })
            html += '</ul>'
            return html
        },
        getDepartmentForLiveChat(department) {
            let arr = new Map([
                [1, 'Sales'],
                [2, 'Inkoop'],
                [3, 'Technische dienst'],
                [4, 'Financiële administratie'],
                [5, 'RMA & Ticket support'],
                [6, 'Logistiek'],
            ])

            return arr.get(department)

        },
        getCauseLiveChat(cause) {
            let arr = new Map([
                [1, 'Product vraag'],
                [2, 'Prijs opvragen'],
                [3, 'Factuur vraag'],
                [4, 'Technische vraag'],
                [5, 'Voorraad vraag'],
                [6, 'Transport vraag'],
                [7, 'Anders'],
            ])

            return arr.get(cause)
        },
        modalShowLiveChat(event) {
            this.chat = event
            event.description = event.description ? event.description : ''
            event.nameDepartment = this.getDepartmentForLiveChat(event.department)
            event.nameDepartment = event.nameDepartment != undefined ? event.nameDepartment : ''
            event.nameCause = this.getCauseLiveChat(event.cause)
            event.nameCause = event.nameCause != undefined ? event.nameCause : ''

            VuedalsBus.$emit('new', {
                name: 'showing-the-money',
                component: {
                    name: 'the-money',
                    methods: {
                        close() {
                            VuedalsBus.$emit('close')
                        }
                    },

                    template: `
                        <div class="modal-info">
                        <div class="modal-info__heading">Live chat met: <span>` + event.nameDepartment + `</span></div>

                        <p class="modal-info__desc">` + event.nameCause + `</p>

                        <p class="modal-info__desc">Live chat aangemaakt op: <span>${_dayjs(event.created_at).format('DD-MM-YYYY')}</span> om <span>${_dayjs(event.created_at).format('HH:mm:ss')}</span></p>

                        ${this.createStatusLiveChat(event.status)}
                        ${this.buildFooterControls(event.description)}
                        </div>`
                },
                size: 'xl',
            })
        },
        buildFooterControls(statusId) {
            let html = '<div class="modal-info__footer justify-content-center">'

            switch (statusId) {
                case 3:
                    html += '<div class="modal-info__footer-controls">' +
                        '<button class="modal-info__btn btn btn--secondary">{{ $t("lifeLineViewInvoice") }}</button>' +
                        '<button class="modal-info__btn btn btn--secondary">{{ $t("lifeLineViewDeliveryDate") }}</button>' +
                        '</div>'
                    break
                case 5:
                    html += '<div class="modal-info__footer-controls">' +
                        '<button class="modal-info__btn btn btn--secondary">Bekijk aflever datum</button>' +
                        '</div>'
                    break
                default:
                    break
            }
            html += '<div><button class="modal-info__btn btn btn--primary" @click="this.close">Ok</button></div>'
            html += '<div class="ml-5"><a href="' + this.url + '" ><button class="modal-info__btn btn btn--primary">View chat</button></a></div>'
            html += '</div>'
            return html
        },
    },
    computed: {
        webshopUrl: function () {
            return process.env.MIX_WEBSHOP_URL;
        },
        url() {
            if (this.chat != '') {
                return this.webshopUrl + '/accounts/#/live-support-chat?chat=' + this.chat.id + '&uid=' + this.chat.uid
            } else {
                return ''
            }
        },
    }
}
</script>

<style scoped>

</style>
