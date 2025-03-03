<template>
    <div class="container-fluid" v-if="show">
        <selectBackground/>
        <div class="promo-area">
            <div class="promo-area__img-wrap">
                <!-- <img class="promo-area__img img-responsive" src="/images/promo-2.png" alt=""> -->
                <img class="promo-area__img img-responsive" src="/images/setupimage.svg" alt="" width="180" height="180">
            </div>
            <a class="promo-area__email" href="mailto:gerard@xindao.com">gerard@xindao.com</a>
            <h1 class="promo-area__heading" v-html="$t('SetupStepOneHeading')"></h1>
            <p class="promo-area__desc" v-html="$t('SetupStepOneText1')"></p>
            <p class="promo-area__desc" v-html="$t('SetupStepOneText2')"></p>
            <p class="promo-area__desc" v-html="$t('SetupStepOneText3')"></p>
            <router-link :to="key" class="promo-area__btn btn btn--primary">
                <span v-html="$t('SetupStepOneLink')"></span>
            </router-link>
        </div>
    </div>
    <div style="margin-top: 70px" v-else>
        <p>{{ $t('SetupStepAccessDenied') }}</p>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import { setTheme } from '../../plugins/dark-mode'

    export default {
        data () {
            return {
                show: false,
                key: '/setup/process-two?key=' + this.$route.query.key
            }
        },
        methods: {
            checkPermission: function () {
                if (this.$route.query.key !== '' && this.$route.query.key != undefined) {
                    axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/register/permission/${this.$route.query.key}`).then((response) => {
                        if (response.data) {
                            this.show = true
                        }
                    })
                }
            }
        },
        computed: {
            ...mapGetters([
                'GET_THEME'
            ])
        },
        mounted() {
            this.checkPermission()
            setTheme(this.GET_THEME)
        }
    }
</script>
