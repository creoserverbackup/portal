<template>
    <div class="dedicated-hosting">
        <div class="dedicated-hosting__header col-md-12">
            <div class="dedicated-hosting__text col-md-6" v-html="settingsText"></div>
            <div class="col-md-6 dedicated-hosting__iphone-wrap">
                <div class="dedicated-hosting__iphone">
                    <p class="dedicated-hosting__iphone-desc">Een server configureren en laten hosten volgens uw
                        wensen?</p>
                    <p class="dedicated-hosting__iphone-desc">Bij CreoDC is dat de Normaalste zaak van De wereld?</p>
                    <div class="row">
                        <button class="helper-btn btn btn--primary">Bezoek CreoDC.com</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="dedicated-hosting__steps col-md-12">
            <div class="dedicated-hosting__step">
                <img src="images/snipet1.png"/>
                <span>Configureer een server naar uw wens</span>
            </div>
            <div class="dedicated-hosting__separator">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="dedicated-hosting__step">
                <img src="images/snipet2.png"/>
                <span>Wij plaatsen de server in ons elgen datacenter</span>
            </div>
            <div class="dedicated-hosting__separator">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="dedicated-hosting__step">
                <img src="images/snipet3.png"/>
                <span>En u kunt er meteen mee aan het werk</span>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    data() {
        return {
            isScrollVisible: false,
            settingsText: ''
        }
    },
    mounted() {
        this.getText()

        window.addEventListener('scroll', () => {
            this.isScrollTopBtnVisible();
        });
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getText()
        {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/settings/page/content/server_huren`
            ).then((response) => {
                this.settingsText = response.data
                this.GET_LOADING_FROM_REQUEST(false);
            }).catch(function (e) {
                console.log(e)
                this.GET_LOADING_FROM_REQUEST(false);
            })
        },
        scrollUp() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        },
        isScrollTopBtnVisible() {
            if (window.pageYOffset > Math.max(document.documentElement.clientHeight, window.innerHeight || 0)) {
                this.isScrollVisible = true;
            } else {
                this.isScrollVisible = false;
            }
        },
    }
}
</script>
