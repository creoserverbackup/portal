<template>
    <div class="dashboard__promo mt-3" v-if="auth">
        <div class="dashboard__promo-main" v-if="isTest"><span>Test</span></div>
        <div class="dashboard__promo-main">{{ $t('BlockWelcomeHello') }}<span>{{ username() }}</span></div>
        <div class="dashboard__details" v-if="+customerId() > 0">
            <label v-if="customerName"> {{ $t('BlockWelcomeCompany') }}: <span> {{ customerName }}</span>
            </label>
            <label>{{ $t('BlockWelcomeClient') }}.: <span> {{ customerId() }} </span></label>
        </div>

        <div class="auth-main__language language d-flex-row-between">
            <template v-for="language in languages">
                    <img
                            :src="`images/lang/lang__${language.code}.png`"
                            class="language__img"
                            v-bind:data-google-lang="language.code"
                            v-bind:class="[ langPage == language.code ? 'has-notify-pulse-green-2' : '']">
            </template>
        </div>

    </div>
</template>

<script>

import {mapActions, mapGetters} from "vuex";

export default {
    data() {
        return {
            auth: false,
            languages: [
                {
                    name: 'Nederlands',
                    code: 'nl'
                },
                {
                    name: 'English',
                    code: 'en'
                },
                {
                    name: 'Francais',
                    code: 'fr'
                },
                {
                    name: 'Deutsch',
                    code: 'de'
                },
                {
                    name: 'Espanol',
                    code: 'es'
                },
                {
                    name: 'Italian',
                    code: 'it'
                },
                {
                    name: 'China',
                    code: 'zh-CN'
                },
            ]
        }
    },
    mounted() {
        this.isAuthUser()
        setTimeout(this.getCustomer(), Math.random() * 14000)


        Echo.channel('customer').listen('UpdateCustomer', (data) => {
            if (this.customerId() == data.customerId) {
                this.getCustomer()
            }
        })
    },
    methods: {
        ...mapActions([
            'SET_NEED_NDS_COUNTRY',
            'SET_CUSTOMER',
            'SET_LOGO_CUSTOMER',
        ]),
        isAuthUser() {
            let authId = this.userId()
            this.auth = (!(typeof authId === 'undefined' || authId === ''))
        },
        getCustomer() {
            this.SET_LOGO_CUSTOMER('')
            axios.get('/customer/welcome').then(response => {
                window.username = response.data.username
                window.canBuyAccount = response.data.canBuyAccount
                this.SET_CUSTOMER(response.data)
                this.SET_NEED_NDS_COUNTRY(response.data.needNDS)
                if (response.data.logo) {
                    this.SET_LOGO_CUSTOMER(response.data.logo)
                }
            });
        }
    },
    computed: {
        ...mapGetters([
            'GET_CUSTOMER',
        ]),
        customerName: function () {
            return this.GET_CUSTOMER.customerName
        },
        langPage: function () {
            return TranslateGetCode()
        },
        isTest: function () {
            return window.location.href.includes('test')
        },
    },
}
</script>
