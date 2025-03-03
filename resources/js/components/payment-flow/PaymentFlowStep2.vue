<template>
    <div>
        <div class="row payment-flow__steps_item__step-2__wrapper">
            <div class="col-5">
                <payment-flow-step2-user :user="user"/>
            </div>
            <div class="col-4">
                <payment-flow-step2-customer :user="user"/>
            </div>
            <div class="col-3">
                <payment-flow-step2-urls/>
            </div>
        </div>
        <div class="row mt-3 payment-flow__steps_item__step-2__wrapper">
            <div class="col-7"></div>
            <div class="col-5 d-flex">
                <button class="cart-s2__btn mr-1" v-on:click="clearCart()" v-if="!offerte">Annuleren</button>
                <button class="cart-s2__btn mr-1" v-on:click="nextStep(1)">Vorige</button>
                <button class="cart-s2__btn" v-on:click="nextStep(3)">Volgende</button>
            </div>
        </div>
    </div>
</template>

<script>

import PaymentFlowStep2Urls from "./PaymentFlowStep2Urls";
import {getMessageError} from "../../utils";
import PaymentFlowStep2UserData from "./PaymentFlowStep2UserData";
import PaymentFlowStep2UserDataEdit from "./PaymentFlowStep2UserDataEdit";
import PaymentFlowStep2User from "./PaymentFlowStep2User";
import PaymentFlowStep2Customer from "./PaymentFlowStep2Customer";
import {mapActions} from "vuex";

export default {
    name: 'PaymentFlowStep2',
    components: {
        PaymentFlowStep2Customer,
        PaymentFlowStep2User,
        PaymentFlowStep2UserDataEdit,
        PaymentFlowStep2UserData,
        PaymentFlowStep2Urls,
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        orderId: {},
        offerte: '',
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        clearCart() {
            this.$root.$emit('deleteAllProductInCart')
        },
        saveUser() {
            this.GET_LOADING_FROM_REQUEST(true);
            axios.put(`/customer/info/cart`, {
                customer: this.user,
            }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.savePayData()
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.$root.$emit('popupMessages', getMessageError(e))
            })
        },
        savePayData() {
            axios.post('/cart/pay/data', {
                orderId: this.orderId,
                customer: this.user,
            }).then((response) => {
                this.$emit('setStep', 3)
            }).catch((e) => {
                this.$root.$emit('popupMessages', getMessageError(e))
                console.log(e)
            })
        },
        nextStep(step = 1) {
            if (step === 3) {
                this.saveUser()
            } else {
                this.$emit('setStep', step)
            }
        },
    },
}
</script>
