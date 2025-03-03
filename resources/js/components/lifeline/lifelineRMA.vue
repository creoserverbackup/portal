<template>
</template>

<script>
import {Bus as VuedalsBus} from "vuedals";

export default {
    data() {
        return {
            eventsStatusRMA: [
                {
                    id: 1,
                    name: 'RMA aangemaakt (RMA made)',
                },
                {
                    id: 2,
                    name: 'RMA ontvangen (RMA recieved)',
                },
                {
                    id: 3,
                    name: 'RMA in behandeling (RMA being worked on)',
                },
                {
                    id: 4,
                    name: 'RMA in afwachting (RMA pending)',
                },
                {
                    id: 5,
                    name: 'RMA terug naar u (rma being shipped back)',
                },
            ],
        }
    },
    name: "lifelineRMA",
    methods: {
        modalShowRMA(event) {
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
                                <div class="modal-info__heading"><span>` + event.orderNumber + `</span></div>
                                <p class="modal-info__desc">` + event.description + `</p>
                                <p class="modal-info__desc">RMA aangemaakt op: <span>${_dayjs(event.created_at).format('DD-MM-YYYY')}</span> om <span>${_dayjs(event.created_at).format('HH:mm:ss')}</span></p>
                                ${this.createStatusRMA(event.status)}
                                ${this.buildFooterControls(event.description)}
                            </div>
                        `
                },

                size: 'xl',
            })
        },
        createStatusRMA(status) {
            let html = '<ul class="modal-info__stages stages">'
            this.eventsStatusRMA.forEach(item => {
                html += '<li class="stages__stage'
                html += (status > item.id ? ' active' : '')
                html += (status == item.id ? ' last' : '')
                html += `">
                            <div class="stages__stage-step">${item.id}</div>
                            <span class="stages__stage-desc">${item.name}</span>
                        </li>`
            })
            html += '</ul>'
            return html
        },
        buildFooterControls(status) {
            let html = '<div class="modal-info__footer">'

            switch (status) {
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
