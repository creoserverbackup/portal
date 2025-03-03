<template>
    <div class="pf-method-pay" v-bind:class="checkActivePay()">
        <div class="pf-method-pay__body" @click="changeBodyHandler()">
            <input class="w-auto mb-auto mr-15" type="checkbox" :checked="bankCheckbox == nameBank" @click="changeBody($event)">
            <img class="pf-method-pay__logo" v-bind:src="'images/bank/' + icon" alt="bank logotype">
            <div class="pf-method-pay__title">{{ getTextBank() }}
                <div v-if="rateBank" class="pf-method-pay__price-title">
                    Admin. Kosten:
                    <span class="pf-method-pay__price-price">
                        {{ checkPrice(rateBank) }}
                    </span>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper";

export default {
    name: "PaymentFlowMethodPayBank",
    props: {
        bankCheckbox: '',
        nameBank: '',
        icon: '',
        rateBank: '',
    },
    methods: {
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        getTextBank() {
            let arr = new Map([
                ['creditcard', 'Betalen met uw Creditcard is veilig en verzekerd wereldwijd'],
                ['bancontact', 'Online betalen met Mister Cash vanuit België'],
                ['belfius', 'Online betalen met Belvius vanuit België'],
                ['kbc', 'Online betalen met KBC/CBC vanuit België'],
                ['giropay', 'Online betalen met Giropay vanuit Duitsland'],
                ['bancaires', 'Betalen met Cartes Bancaires vanuit Frankrijk'],
                ['postepay', 'Betalen met Postepay vanuit Italië'],
                ['sofort', 'Online betalen met Sofort beschikbaar in 8 EU landen'],
                ['eps', 'Betaalmethode via EPS'],
                ['paypal', 'Paypal voor iedereen een goede oplossing in betalen'],
                ['applepay', 'Betaal gemakkelijk en simpel door uw Apple Pay kaart'],
                ['cashpickup', 'Ik betaal bij het afhalen van mijn order bij creoserver'],
                ['banlogo', 'Bij deze keuze krijgt u een proforma. U kunt vervolgens het totaal bedrag naar ons over\n' +
                '                                maken met het proforma nr. als kenmerk'],
            ])
            return arr.get(this.nameBank)
        },
        checkActivePay() {
            if (this.bankCheckbox != this.nameBank && this.bankCheckbox != false) {
                return 'disabled'
            } else if (this.bankCheckbox == this.nameBank) {
                return 'checked'
            }
        },
        changeBody(event) {
            if (this.nameBank == 'paypal' || this.nameBank == 'creditcard' || this.nameBank == 'giropay') {
                event.preventDefault();
            }
        },
        changeBodyHandler() {
            this.$emit('changeMethodPay', this.nameBank)
        }
    }
}
</script>
