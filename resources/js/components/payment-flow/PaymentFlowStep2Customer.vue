<template>
    <div class="position-relative p-10 b-secondary h-100">
        <div class="fs-24 c-secondary mb-40">Bedrijfsgegevens</div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">Bedrijfsnaam:</div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="text" placeholder="Bedrijfsnaam" v-model="user.customerName">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">Kvk nr.:</div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="text" placeholder="Kvk nr." v-model="user.kvk">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">Btw-nr.:
                <span class="c-gray fs-12">(zonder landcode)</span>
            </div>
            <div class="w-50 d-flex-column-between">
                <div>
                    <input class="w-100 fs-12 p-2" type="text" placeholder="Btw-nr." v-model="user.btw" @input="startCheck()">
                </div>
                <div>
                    <span class="fs-12 c-red" v-if="user.btw === ''">Please do <span class='td-underline'>NOT</span> enter your country code in this field!</span>
                    <span class="c-gray" v-else-if="verification">(verification ...)</span>
                    <span class="c-green" v-else-if="btw.valid">(valid)</span>
                    <span class="fs-12 c-red" v-else-if="btw.error" v-html="btw.error"></span>
                    <span class="c-red" v-else-if="user.btw && user.btw.length > 0">(not valid)</span>
                    <span v-else>&nbsp;</span>
                </div>
            </div>
        </div>

        <p class="fs-16 c-black mt-25">
            <span class="c-red">Letop!:</span> De NAW gegevens die u rechts heeft ingevuld, worden gebruikt als factuur
            en standaard aflever adres.
            U kunt het aflever adres in de volgende stap nog wijzigen als dit anders is dan het factuur adres.
        </p>
        <p class="fs-16 c-black">
            Buitenlandse bedrijven die besteld hebben, maar geen geldig EU btw nummer hebben ingevuld, betalen gewoon de
            Nederlandse btw.
        </p>
    </div>
</template>

<script>

import {getMessageError} from "../../utils";
import {mapActions, mapGetters} from "vuex";

export default {
    name: "PaymentFlowStep2Customer",
    props: {
        user: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            validBtw: false,
            time: null,
            verification: true,
        }
    },
    mounted() {
        this.startCheck()
    },
    methods: {
        ...mapActions([
            'SET_BTW',
        ]),
        startCheck()
        {
            // this.user.btw = this.user.btw.replace(/\D/g,'')
            clearTimeout(this.time);
            this.time = setTimeout(() => this.checkBtw(), 2000);
        },
        checkBtw() {
            this.verification = true
            axios.post(`/customer/btw`, {
                customer: this.user,
            }).then((response) => {
                this.verification = false
                this.SET_BTW(response.data)
            }).catch((e) => {
                this.$root.$emit('popupMessages', getMessageError(e))
            })
        },
    },
    computed: {
        ...mapGetters({
            btw: 'GET_BTW',
        }),
    }
}
</script>

<style scoped>

</style>