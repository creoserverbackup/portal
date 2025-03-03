<template>
    <div class="pf-method-pay__qr">
        <div class="pf-method-pay__qr-title">Overschrijven via uw mobiele bank-app</div>
        <div class="pf-method-pay__qr-text">Scan deze SEPA QR-code met je bank-app op uw mobiel om een directe
            overschrijving te maken en uw order te betalen
        </div>
        <div class="pf-method-pay__qr-body">
            <div class="row mx-n2">
                <div class="col-6 px-2 " v-if="qrCode === ''">
                    <button class="btn btn--primary w-80 p-2 ta-c" v-on:click="getIdealBankAndQR()">Genereer SEPA code</button>
                </div>
                <div class="col-6 px-2" v-else>
                    <img v-if="qrCode" class="img-fluid pf-method-pay__qr-img pf-method-pay__qr-img--qr-code"
                         :src="qrCode">
                </div>
                <div class="col-6 px-2">
                    <img class="img-fluid"
                         src="images/bank/sepa-betalen.jpg"
                         alt="sepa">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "PaymentFlowMethodPayQrCode",
    props: {
        orderId: '',
    },
    data() {
        return {
            qrCode: '',
        }
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getIdealBankAndQR() {
            this.GET_LOADING_FROM_REQUEST(true);
            axios.post(`/cart/pay/ideal-qrcode`, {
                orderId: this.orderId,
                qrcode: true,
            }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.IdealBanks = response.data.ideal
                this.qrCode = response.data.src
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },
    }

}
</script>
