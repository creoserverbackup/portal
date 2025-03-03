<template>
</template>

<script>
import {Bus as VuedalsBus} from "vuedals";

export default {
    props: {
        eventsStatus: {
            type: Array,
            required: true
        },
    },
    name: "lifelineOrder",
    methods: {
        modalShow(event) {
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
                        <div class="modal-info__heading">Order: <span>${event.orderNumber}</span></div>
                        ${this.createDetailsOrder(event.description)}
                        <p class="modal-info__desc">Order aangemaakt op:
                            <span>${_dayjs(event.date).format('DD-MM-YYYY')}</span> om
                            <span>${_dayjs(event.date).format('HH:mm:ss')}</span></p>
                        ${this.createStatus(event.status)}
                        ${this.buildFooterControls(event.status)}
                        </div>
                    `
                },

                size: 'xl',
            })
        },
        createDetailsOrder($details)
        {
            let html = ''
            $details.forEach((item, key) => {
                html += `<p class="modal-info__desc">${item}</p>`
            })
            return html
        },
        createStatus(status) {
            let html = '<ul class="modal-info__stages stages">'
            let searchStatus = false
            status = this.getValidStatus(status)
            this.eventsStatus.forEach((item, key) => {
                ++key
                if (item.value != 7 && item.value != 6) {
                    html += '<li class="stages__stage'
                    html += (status > key ? ' active' : '')
                    html += (status == key ? ' last' : '')

                    if (status == item.value) {
                        searchStatus = true
                    }

                    html += `">
                            <div class="stages__stage-step">${key}</div>
                            <span class="stages__stage-desc">${item.name}</span>
                        </li>`
                }
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
        getValidStatus(status) {

            switch(status) {
                case 3:
                    return 2;
                case 4:
                    return 5;
                case 5:
                    return 4;
                case 6:
                    return 4;
                case 7:
                    return 4;
                case 8:
                    return 6;
                case 9:
                    return 6;
                case 10:
                    return 6;
                case 11:
                    return 3;
                default:
                    return status
            }
        }
    }
}
</script>

<style scoped>

</style>
