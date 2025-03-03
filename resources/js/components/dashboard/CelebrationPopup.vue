<template>

</template>

<script>
import {DateTime} from 'luxon'
import {Bus as VuedalsBus} from "vuedals";

export default {
    data() {
        return {
            url: '',
            id: '',
        }
    },
    mounted() {
        this.checkStep()
    },
    methods: {
        async checkStep() {
            await this.getImages()
        },
        getImages() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/setting/image/welcome`).then((response) => {
                if (response.data.id != undefined) {
                    this.id = response.data.id
                    if (response.data.url != undefined) {
                        this.url = response.data.url
                        this.createModal()
                    }
                }
            })
        },
        createModal() {
            setTimeout(() => {
                let keyCelebration = 'close-celebration-' + this.id,
                    keyLocalStorage = localStorage.getItem(keyCelebration)
                if (keyLocalStorage === null) {
                    this.$vuedals.open({
                        name: 'showing-celebration',
                        component: {
                            name: 'modal-celebration',
                            methods: {
                                close() {
                                    localStorage.setItem(keyCelebration, true)
                                    VuedalsBus.$emit('close')
                                }
                            },
                            template: `
                                <div class="modal-celebration modal-celebration--new-year" style="background: url(` + this.url + `) no-repeat center / 90% auto">
                                    <p>Ook dit jaar was hel weer fijn samenwerken met u. We hopen dat we dit het komende jaar met u mogen vortzetten. Voor nu wensen wij u</p>
                                    <p><strong>Prettige kerstdagen en een Voorspoedig</strong></p>
                                    <p class="lead"><strong>2021</strong></p>
                                    <p>Het CreoServer Team</p>
                                    <div class="text-center">
                                    ${this.addButtonClose()}
                                    </div>
                                </div>
                            `
                        },
                        onDismiss(data) {
                            localStorage.setItem(keyCelebration, true)
                        },
                        size: 'lg',
                    })
                }
            }, 1000)
        },
        addButtonClose() {
            return '<button  class="btn btn--primary" @click="this.close">Bedankt</button>'
        },
    }
}
</script>
