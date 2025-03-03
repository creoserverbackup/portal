<template>
    <div class="modal middle popup-cookie" v-if="isShow && isShowOnThisPage"
         :class="{ 'modal-open': (isShow && isShowOnThisPage)}">
        <div class="modal-inner popup-cookie__body">
            <div class="modal_h2 popup-cookie__body-title">Informatie over cookies</div>
            <div>
                <div class="popup-cookie__body-text mb-5">
                    Onze website,
                    <router-link to="/">{{ hostname }}</router-link>
                    , maakt gebruik van cookies en daarmee vergelijkbare technieken.
                    {{ hostname }} gebruikt functionele cookies om het gedrag van websitebezoekers na te gaan en de
                    website aan de
                    hand van deze gegevens te verbeteren.
                    Lees voor meer informatie onze
                    <router-link to="/page/privacy-policy">privacy- en cookieverklaring</router-link>
                    .
                </div>
                <div class="list-check mb-30 popup-cookie__body-option">
                    <div class="mb-4 d-flex justify-content-between">
                        <div class="h6 d-inline-flex align-items-baseline mb-0">
                            <div class="popup-cookie__body-option-checkbox mr-1">
                                <input class="popup-cookie__body-option-checkbox-input" type="checkbox" disabled>
                                <svg class="popup-cookie__body-option-checkbox-svg" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 448 512">
                                    <path
                                            d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z"
                                            style="fill: green;height: 16px; width: 16px; max-width: 16px;"/>
                                </svg>
                                <!--                                <i class="icon-rounded-check"></i>-->
                            </div>
                            <div class="popup-cookie__body-option-title">
                                Technische cookies
                            </div>
                        </div>
                        <div class="popup-cookie__body-question"
                             @mouseover="techSubTitle = true" @mouseleave="techSubTitle = false">
                            <img class="popup-cookie__body-question" src="/images/question.svg">
                            <div class="popup-cookie__body-question-text"
                                 v-if="techSubTitle">
                                Technische cookies zijn noodzakelijk om de website goed te laten werken. Deze
                                cookies zijn nodig om ervoor te zorgen dat jij een optimale gebruikerservaring hebt.
                                Het is 100% anonieme informatie en dus niet te herleiden naar jou. Volgens de
                                GDPR-wetgeving hoeft er geen toestemming gevraagd te worden om technische cookies te
                                gebruiken.
                                <!--                                <i class="icon-question-mark"></i>-->
                            </div>
                        </div>
                    </div>
                    <div class=" d-flex justify-content-between">
                        <div class="h6 d-inline-flex align-items-baseline mb-0">
                            <div class="popup-cookie__body-option-checkbox mr-1">
                                <input class="popup-cookie__body-option-checkbox-input" type="checkbox" disabled>
                                <svg class="popup-cookie__body-option-checkbox-svg" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 448 512">
                                    <path
                                            d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z"
                                            style="fill: green;height: 16px; width: 16px; max-width: 16px;"/>
                                </svg>
                                <!--                                <i class="icon-rounded-check"></i>-->
                            </div>
                            <div class="popup-cookie__body-option-title">
                                Analytische cookies
                            </div>
                        </div>
                        <div class="popup-cookie__body-question"
                             @mouseover="analyticSubTitle = true" @mouseleave="analyticSubTitle = false">
                            <img class="popup-cookie__body-question" src="/images/question.svg">
                            <div class="popup-cookie__body-question-text"
                                 v-if="analyticSubTitle">
                                Analytische cookies worden gebruikt om informatie te verzamelen over hoe
                                websitebezoekers onze website gebruiken en beleven. Deze informatie stelt ons in staat
                                om de website te optimaliseren, de werking ervan te controleren en gebruikerservaring te
                                verbeteren. Het is 100% anonieme informatie en dus niet te herleiden naar jou. Volgens
                                de GDPR-wetgeving hoeft er geen toestemming gevraagd te worden om analytische cookies te
                                gebruiken.
                                <!--                                <i class="icon-question-mark"></i>-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-form">
                <div class="two-columns button-modal">
                    <input class="popup-cookie__body-submit" type="submit" @click="handleSuccess"
                           value="Doorgaan naar de website">
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
</template>


<script>

export default {
    name: 'CookiePopup',
    data() {
        return {
            isShow: false,
            isShowOnThisPage: false,
            techSubTitle: false,
            analyticSubTitle: false,
        }
    },
    computed: {
        hostname() {
            let url = window.location.href;

            return (new URL(url)).hostname.replace('www.', '');
        }
    },

    watch: {
        '$route.path': function (route) {
            this.isShowOnThisPage = route !== '/page/privacy-policy'
                    && this.$route.path.indexOf('frame') == -1;
        }
    },

    mounted() {
        if (this.$route.path.indexOf('frame') == -1) {
            this.isShow = this.$cookies.get("isShowCookiePopup") !== 'false'
        }
    },

    methods: {
        handleSuccess() {
            this.isShow = false
            this.$cookies.set('isShowCookiePopup', 'false', 60 * 60 * 24 * 365);
        }
    },
}
</script>
