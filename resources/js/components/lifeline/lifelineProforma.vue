<template>
<!--    <div class="lifeline__order" v-on:click="modalShowProforma(event)">-->
<!--        <div class="lifeline__order-dates">-->
<!--            <time class="lifeline__order-date" datetime="">{{ event.date | dayjs('DD-MM-YYYY') }}</time>-->
<!--            <time class="lifeline__order-time" datetime="">{{ event.date | dayjs('HH:mm') }}</time>-->
<!--        </div>-->
<!--        <div class="fs-13">Proforma aanvraag</div>-->
<!--        <p class="lifeline__order-desc">Category: {{ event.categoryName }}</p>-->
<!--        <p class="lifeline__order-desc">Title: {{ event.title }}</p>-->
<!--    </div>-->
</template>

<script>
import {Bus as VuedalsBus} from "vuedals";

export default {
    name: "lifelineProforma",
    data() {
        return {
            eventsStatusProforma: [
                {
                    id: 1,
                    name: 'Proforma created and sent',
                },
                {
                    id: 2,
                    name: 'Received a proposal for a proforma',
                },
                {
                    id: 3,
                    name: 'Proforma accepted',
                }
            ],
        }
    },
    methods: {
        modalShowRequest(event) {
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
                                <div class="modal-info__heading">Proforma:<span>` + event.id + `</span></div>
                                <p class="modal-info__desc">Category : ` + event.categoryName + `</p>
                                <p class="modal-info__desc">Title : ` + event.title + `</p>
                                <p class="modal-info__desc">Description:` + event.description + `</p>
                                <p class="modal-info__desc">Proforma op: <span>${_dayjs(event.created_at).format('DD-MM-YYYY')}</span> om <span>${_dayjs(event.created_at).format('HH:mm:ss')}</span></p>
                                ${this.createStatusProforma(event.status)}
                                ${this.buildFooterControls(event.description)}
                            </div>`
                },
                size: 'xl',
            })
        },
        createStatusProforma(statusId) {
            let html = '<ul class="modal-info__stages stages">'
            this.eventsStatusProforma.forEach(item => {
                html += '<li class="stages__stage'
                html += (statusId >= item.id ? ' active' : '')
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
