<template>
    <div class="col cart-s3__type"
         v-bind:class="[
                 active ? 'cart-s3__type-active has-notify-pulse-green-3' : '',
                 available ? 'cart-s3__type-available' : '',
                 ]"
         v-on:click="checkTypeDelivery()">

        <div class="cart-s3__type-title">
            <span class="c-white" v-html="types.title"></span>
        </div>
        <div class="p-10 h-100 bg-white">
            <div class="mb-10" v-bind:class="checkDisabledClass('customerName')">
                <span class="cart-s3__type-option-title">Bedrijfsnaam:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.customerName"
                           placeholder="Bedrijfsnaam"
                           v-bind:disabled="checkDisabled('customerName')">
                </span>
            </div>
            <div class="mb-10">
                <span class="cart-s3__type-option-title">Ter attentie van:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.username"
                           placeholder="Ter attentie van"
                           v-bind:disabled="checkDisabled()">
                </span>
            </div>
            <div class="mb-10" v-bind:class="checkDisabledClass('namens')">
                <span class="cart-s3__type-option-title">Namens:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.namens"
                           placeholder="Namens"
                           v-bind:disabled="checkDisabled('namens')">
                </span>
            </div>

            <div class="d-flex">
                <div class="mb-10 w-80 mr-1">
                    <span class="cart-s3__type-option-title">Adres:</span>
                    <span class="fs-11 d-block">
                        <input class="cart-s3__type-option-text" type="text" v-model="form.address"
                               placeholder="Adres"
                               v-bind:disabled="checkDisabled()">
                </span>
                </div>

                <div class="mb-10 w-20">
                    <span class="cart-s3__type-option-title">House:</span>
                    <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.house"
                           placeholder="nr."
                           v-bind:disabled="checkDisabled()">
                </span>
                </div>
            </div>

            <div class="mb-10">
                <span class="cart-s3__type-option-title">Postcode:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.postcode"
                           placeholder="Postcode"
                           v-bind:disabled="checkDisabled()">
                </span>
            </div>

            <div class="mb-10">
                <span class="cart-s3__type-option-title">Woonplaats:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-region" type="text" v-model="form.region"
                           placeholder="Woonplaats"
                           v-bind:disabled="checkDisabled()">
                </span>
            </div>

            <div class="mb-10">
                <span class="cart-s3__type-option-title">Land:</span>
                <span class="fs-11 d-block">

                    <payment-flow-step3-select-country :objectSelect="country"
                                                       :classAdd="'last-input'"
                                                       @changeSelect="changeCountry"
                                                       :disabled="checkDisabled()"
                    />
                </span>
            </div>
            <div class="mb-7">
                <span class="cart-s3__type-option-title">Email:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.email"
                           placeholder="Email"
                           v-bind:disabled="checkDisabled()">
                </span>
            </div>
            <div class="mb-7">
                <span class="cart-s3__type-option-title">Telefoon nummer:</span>
                <span class="fs-11 d-block">
                    <input class="cart-s3__type-option-text" type="text" v-model="form.phone"
                           placeholder="Telefoon nummer"
                           v-bind:disabled="checkDisabled()">
                </span>
            </div>
        </div>
        <div class="d-flex-center min-h-30 bg-white cart-s3__type-border-line">
            <span class="mr-auto ml-auto cart-s3__type-option-title">
                Verzendkosten: {{ checkPrice(types.priceDelivery) }}</span>
        </div>
        <div class="cart-s3__type-border">
            <div v-bind:class="icon"></div>
        </div>
    </div>
</template>

<script>

import PaymentFlowStep3SelectCountry from "./PaymentFlowStep3SelectCountry";
import {checkPriceHelper} from "../../helper";

export default {
    components: {
        PaymentFlowStep3SelectCountry
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        types: {
            type: Object,
            required: true
        },
        country: '',
        icon: '',
        active: false,
        available: false,
    },
    data() {
        return {
            form: {},
        }
    },
    mounted() {
        this.form = this.user
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        changeCountry($object) {
            this.user.country = $object.selected
        },
        checkDisabled(nameField) {
            if (nameField == 'namens' && (this.types.type == 5 || this.types.type == 4)) {
                return true
            }

            if (nameField == 'customerName' && (this.types.type == 1 || this.types.type == 2)) {
                return true
            }
            return this.active ? false : true
        },
        checkDisabledClass(nameField) {
            if (nameField == 'namens' && (this.types.type == 5 || this.types.type == 4)) {
                return 'cart-s3__type-disabled'
            }

            if (nameField == 'customerName' && (this.types.type == 1 || this.types.type == 2)) {
                return 'cart-s3__type-disabled'
            }
            return ''
        },
        setForm() {
            this.form = this.user
        },
        checkTypeDelivery() {
            this.$emit('checkTypeDelivery', this.types.type, this.form)
        },
    },
    watch: {
        deep: true,
        form: {
            deep: true,
            handler(value) {
                if (this.active) {
                    this.checkTypeDelivery()
                }
            }
        },
        user() {
            this.setForm()
        }
    },
}
</script>
