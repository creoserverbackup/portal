<template>
</template>

<script>
import {Bus as VuedalsBus} from "vuedals";

export default {
    name: "lifelineTickets",
    data() {
        return {
            eventsStatusTicket: [
                {
                    id: 1,
                    name: 'Antwoord',
                },
                {
                    id: 2,
                    name: 'Open',
                },
                {
                    id: 3,
                    name: 'Afwachting',
                },
                {
                    id: 4,
                    name: 'Gesloten',
                },
            ],
        }
    },
    methods: {
        modalShowTicket(event) {
            event.description = event.description ? event.description : ''
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
                        <div class="modal-info__heading">Ticket aangemaakt: <span>${event.id}</span></div>
                        <p class="modal-info__desc">${event.description}</p>
                        <p class="modal-info__desc">Ticket aangemaakt op:
                            <span>${_dayjs(event.created_at).format('DD-MM-YYYY')}</span> om
                            <span>${_dayjs(event.created_at).format('HH:mm:ss')}</span></p>

                        ${this.createStatusTicket(event.status)}
                        ${this.buildFooterControls(event.description)}
                        </div>
                    `
                },

                size: 'xl',
            })
        },
        createStatusTicket(statusId) {
            let html = '<ul class="modal-info__stages stages">'
            this.eventsStatusTicket.forEach(item => {
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
        buildFooterControls(statusId) {
            let html = '<div class="modal-info__footer">'

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
            html += '<button class="modal-info__btn btn btn--primary" @click="this.close">Ok</button>'
            html += '</div>'
            return html
        },
    }
}
</script>

<style scoped>

</style>
