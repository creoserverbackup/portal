<template>
    <div class="col cart-s3__type cart-s3__type-available"
         v-bind:class="[
                 active ? 'cart-s3__type-active has-notify-pulse-green-3' : '',
                 ]"
         v-on:click="checkTypeDelivery()">
        <div class="cart-s3__type-title">
            <span class="c-white">Zelf afhalen</span></div>
        <div class="p-10 h-100 bg-white">
            <span class="cart-s3__type-option-title">
                <div class="mb-15">CreoServer afhaal adres:</div>
                <div class="mb-15">
                    <div>Paxtonstraat 23</div>
                    <div>8013 RP Zwolle</div>
                    <div>Nederland</div>
                </div>
                <div class="mb-15">
                    <div>Open op werkdagen</div>
                    <div>van 09:00 tot 17:00</div>
                </div>
            </span>
            <a class="fs-11 d-block" target="_blank"
               href="https://www.google.com/maps/place/CreoServer.com/@52.4891169,6.1415539,17z/data=!3m1!4b1!4m5!3m4!1s0x47c87e7774ff02a7:0x7a37a6861fe9bc99!8m2!3d52.4891169!4d6.1415539">
                <img src="images/map.png" class="cart-s3__type-map">
            </a>
        </div>
        <div class="d-flex-center min-h-30 bg-white cart-s3__type-border-line">
            <span class="mr-auto ml-auto cart-s3__type-option-title">Gratis</span>
        </div>
        <div class="cart-s3__type-border">
            <div class="icon-delivery-warehouse"></div>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
        deliveryInfo: {
            type: Object,
            required: true
        },
        types: {
            type: Object,
            required: true
        },
        active: false,
        type: '',
    },
    data() {
        return {
            myHours: new Date().getHours(),
            timeStart: '',
            timeFinish: '',
            form: {
                myDate: new Date(),
                timeStart: '',
                timeFinish: '',
            },
        }
    },
    mounted() {
        this.setTime()
        this.checkTime()
    },
    methods: {
        checkTypeDelivery() {
            this.$emit('checkTypeDelivery', this.types.type, this.form)
        },
        dateToYYYYMMDD(date) {
            date = new Date(date)
            return date && new Date(date.getTime() - (date.getTimezoneOffset() * 60 * 1000)).toISOString().split('T')[0]
        },
        checkDate() {
            if (this.form.myDate.getTime() < Date.now()) {
                this.form.myDate = new Date()
            }
        },
        setTime() {
            if (this.deliveryInfo.timeFinish != null) {
                this.form.timeFinish = this.deliveryInfo.timeFinish
            }
            if (this.deliveryInfo.timeStart != null) {
                this.form.timeStart = this.deliveryInfo.timeStart
            }

            if (this.deliveryInfo.date != null && this.deliveryInfo.date != '') {
                this.form.myDate = this.deliveryInfo.date
            }
        },
        checkTime() {
            let time = this.myHours,
                    timeFinish = this.myHours

            if (this.form.timeStart != '') {
                time = this.form.timeStart.split(':')[0]
            }

            if (this.form.timeFinish !== '') {
                timeFinish = this.form.timeFinish.split(':')[0]
            }

            if (time > 16 || time < 10) {
                time = 10
            }

            if (timeFinish > 17 || timeFinish < 11) {
                timeFinish = 11
            }

            if (timeFinish <= time) {
                timeFinish = +time + +1
            }
            this.form.timeStart = time + ':' + '00'
            this.form.timeFinish = (timeFinish) + ':' + '00'
        },
    },
    watch: {
        type: {
            deep: true,
            handler() {
                if (this.type == 3)
                    this.checkTypeDelivery()
            }
        },
    },
}
</script>
