<template>
    <div class="popup-pre-order" v-if="show" v-bind:class="{ 'popup-pre-order__open': show}">
        <div class="popup-pre-order__inner">
            <div class="popup-pre-order__close" @click="close">
                <img src="/images/close.svg" alt="close"/>
            </div>
            <h2>Product aanvraag</h2>

            <div class="el-input">
                <label>*Bedrijfs naam:</label>
                <ProfileInputGroup v-bind:rules="{ required : true }" :inputGroupData="form.customerName"/>
            </div>

            <div class="el-input">
                <label for="">*Uw naam:</label>
                <ProfileInputGroup v-bind:rules="{ required : true }" :inputGroupData="form.name"/>
            </div>

            <div class="el-input">
                <label for="">*Uw mail:</label>
                <ProfileInputGroup v-bind:rules="{ required : true }" :inputGroupData="form.email"/>
            </div>

            <div class="el-input">
                <label for="">*Uw telefoonnummer:</label>
                <ProfileInputGroup v-bind:rules="{ required : true }" :inputGroupData="form.phone"/>
            </div>

            <label class="popup-pre-order__product-label mt-5 mb-5">Product aanvraag:</label>
            <h2 class="c-sc">{{ product.name }}</h2>

            <div class="el-input">
                <label class="mt-3 mb-4" for="">*Wat is het gewenste aantal? </label>
                <ProfileInputGroup v-bind:rules="{ required : true }" :inputGroupData="form.quantity"/>
            </div>

            <div>
                <label class="c-sc text-bold">LETOP! </label>
                <label>
                    Het kan enkele dagen duren voordat wij met u contact opnemen. Ook kunnen
                    we niet garanderen dat dit product nog werkelijk te verkrijgen is bij onze
                    leveranciers.
                </label>
                <label class="c-red">* Zijn verplichte velden</label>
            </div>

            <div class="popup-pre-order__submit">
                <input type="submit" @click="send" value="Versturen">
            </div>
        </div>
        <div class="popup-pre-order__background" @click="close"></div>
    </div>
</template>

<script>

import ProfileInputGroup from "../../components/profile/ProfileInputGroup";
import {mapActions, mapGetters} from "vuex";
import {getMessageError} from "../../utils";

export default {
    name: "popupPreOrder",
    components: {
        ProfileInputGroup
    },
    data() {
        return {
            product: {},
            show: false,
            form: {
                customerId: '',
                customerName: {
                    placeholder: this.$t('SetupStepTwoCompanyNamePlaceholder'),
                    name: 'customerName',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        required: true,
                        minLength: 2
                    }
                },

                name: {
                    placeholder: this.$t('SetupStepTwoUsernamePlaceholder'),
                    name: 'name',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 2,
                    }
                },
                email: {
                    placeholder: this.$t('SetupStepTwoEmailPlaceholder'),
                    name: 'email',
                    value: '',
                    type: 'email',
                    required: true,
                    isValidate: false,
                },
                phone: {
                    placeholder: this.$t('SetupStepTwoPhonePlaceholder'),
                    name: 'phone',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        // regEx: '[^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$]'
                    }
                },

                quantity: {
                    placeholder: '',
                    name: 'quantity',
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        maxLength: 25,
                        regEx: '[0-9 -]+'
                    },
                },
            }
        }
    },
    mounted() {
        this.$root.$on('popupPreOrder', (data) => {
            this.checkSend(data)
        })
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        close() {
            this.show = false
        },
        checkSend(data) {
            this.GET_LOADING_FROM_REQUEST(true)

            this.product = data.product
            this.form.customerName.value = this.customer.customerName
            this.form.email.value = this.customer.email
            this.form.phone.value = this.customer.phone
            this.form.name.value = this.customer.username
            this.form.customerId = this.customer.customerId

            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/pre-order/form?uid=${this.customer.uid}&productId=${this.product.id}`).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.show = true
            }).catch((e) => {
                this.show = false
                this.GET_LOADING_FROM_REQUEST(false)
                this.$root.$emit('popupMessages', getMessageError(e))
                console.log(e)
            })
        },
        send() {
            this.GET_LOADING_FROM_REQUEST(true)

            axios.post(`${process.env.MIX_CREO_WORK_FLOW}/api/public/pre-order/form`, {
                customerName: this.form.customerName.value,
                email: this.form.email.value,
                phone: this.form.phone.value,
                name: this.form.name.value,
                quantity: this.form.quantity.value,
                customerId: this.form.customerId,
                productId: this.product.id,
                uid: this.customer.uid,
                type: 'pre-order',
                site: 'portal',
            }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.$root.$emit('popupMessages', 'Bedankt voor uw aanvraag. We nemen zo spoedig mogelijk contact met u op')
                this.close()
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.$root.$emit('popupMessages', getMessageError(e))
                console.log(e)
            })
        }
    },
    computed: {
        ...mapGetters([
            'GET_CUSTOMER',
        ]),
        customer: function () {
            return this.GET_CUSTOMER
        },
    },

    destroyed() {
        this.$root.$off('popupPreOrder')
    }
}
</script>

<style scoped>

</style>

<style lang="scss">

@import "resources/sass/abstracts/variables";

.popup-pre-order {
    text-align: center;
    overflow: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    /* z-index: 1050; */
    -webkit-overflow-scrolling: touch;
    outline: 0;
    opacity: 0;
    -webkit-transition: opacity 0.15s linear, z-index 0.15;
    -o-transition: opacity 0.15s linear, z-index 0.15;
    transition: opacity 0.15s linear, z-index 0.15;
    z-index: -1;
    overflow-y: auto;


    &__open {
        z-index: 999;
        opacity: 1;
        overflow: hidden;
    }

    &__inner {
        padding: 3% 6%;
        margin-top: 25vh;
        min-width: 1000px;
        color: $black;
        width: auto;
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
        position: relative;
        z-index: 999;
        -webkit-transition: -webkit-transform 0.3s ease-out;
        -o-transition: -o-transform 0.3s ease-out;
        transition: -webkit-transform 0.3s ease-out;
        -o-transition: transform 0.3s ease-out;
        transition: transform 0.3s ease-out;
        transition: transform 0.3s ease-out, -webkit-transform 0.3s ease-out;
        display: inline-block;
        vertical-align: middle;
        background: #FFFFFF;
        box-shadow: 0px 4px 32px rgba(0, 0, 0, 0.1);
        -webkit-transform: translate(0, -25%);
        -ms-transform: translate(0, -25%);
        transform: translate(0, -25%);
        -webkit-transition: -webkit-transform 0.3s ease-out;
        -o-transition: -o-transform 0.3s ease-out;
        transition: -webkit-transform 0.3s ease-out;
        -o-transition: transform 0.3s ease-out;
        max-width: 440px;
        font-size: 22px;
        line-height: 1.2;
        text-align: left;


        .el-input {
            position: relative;
            font-size: 14px;
            display: inline-block;
            width: 100%;

            input {
                padding: 0 10px;
                font-size: 16px;
                line-height: 40px;
                height: 40px !important;
                -webkit-appearance: none;
                background-color: #FFFFFF;
                background-image: none;
                border-radius: 0;
                border: 1px solid #878484;
                box-sizing: border-box;
                color: #333333;
                display: inline-block;
                font-size: inherit;
                height: 40px;
                line-height: 40px;
                outline: none;
                padding: 0 15px;
                transition: border-color 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
                width: 100%;
            }
        }

        label {
            font-size: 15px;
        }

    }

    h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
        color: $black;
    }

    &__body {
        max-height: 300px;
        overflow: auto;
    }

    &__body-message {
        font-size: 20px;
        text-align: center;
        color: $black;
        padding: 10px;
    }

    &__submit {
        margin-top: 25px;
    }

    input[type="submit"] {
        outline: 0;
        border: 0;
        border-radius: 0;
        font-size: 15px;
        font-style: normal;
        font-weight: 700;
        line-height: 23px;
        letter-spacing: 0em;
        text-align: center;
        width: 100%;
        cursor: pointer;
        transition: 0.3s;
        padding: 15px 32px 14px;
        background: $secondary-color;
        opacity: 1;
        color: $white;
    }

    &__close {
        position: absolute;
        right: 10px;
        top: 10px;
        cursor: pointer;
        z-index: 99;
        font-size: 30px;
        color: #fff;
        display: flex;
        width: 24px;
        height: 24px;
        justify-content: center;
        align-items: center;
    }

    &__close svg {
        width: 14px;
        height: 14px;
        fill: rgba(16, 24, 32, 0.3);
    }

    &__close:hover svg {
        fill: $secondary-color;
    }

    &__background {
        background: rgba(51, 51, 51, 0.9);
        height: 100vh;
        width: 100%;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        -webkit-transition: background 0.15s linear;
        -o-transition: background 0.15s linear;
        transition: background 0.15s linear;
    }


    h2 {
        font-weight: bold;
        font-size: 23px;
    }

    .text-bold {
        font-weight: bold;
    }

    h4 {
        font-size: 15px;
        color: black;
    }

    .el-form-item__label {
        font-size: 15px;
        color: black;
    }

    .el-form-item {
        margin-bottom: 2px;
    }

    .elc-form-item--fw-bold .el-form-item__label {
        font-weight: normal;
    }

    h5 {
        font-size: 12px;
    }
}
</style>