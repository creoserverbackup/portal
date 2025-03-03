<template>
    <div class="container-fluid" v-if="show">
        <selectBackground/>
        <form class="settings-form" name="settings-form">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <div class="helper-section-wrap">
                        <ProfileStages :inputStatus="true"/>

                        <div class="helper-section">
                            <div class="h2">{{ $t('SetupStepThreeHeading1') }}</div>

                            <ProfileSwitcher :inputGroupData="form.sound"/>
                            <ProfileSwitcher :inputGroupData="form.sendEmail"/>
                            <ProfileSwitcher :inputGroupData="form.googlePushMessages"/>
                            <ProfileSwitcher :inputGroupData="form.importantThings"/>
                            <ProfileSwitcher :inputGroupData="form.deliveryServiceTracking"/>
                        </div>

                        <div class="helper-section">
                            <div class="h2">{{ $t('SetupStepThreeHeading2') }}</div>

                            <ProfileSwitcher :inputGroupData="form.darkMode"/>
                            <ProfileSwitcher :inputGroupData="form.classicMode"/>
                            <ProfileSwitcher :inputGroupData="form.lifeLine"/>
                            <unsplash :inputGroupData="form.background"/>
                        </div>

                        <div class="helper-section">
                            <div class="h2">{{ $t('SetupStepThreeHeading3') }}</div>

                            <ProfileLangSwitcher/>
                        </div>
                    </div>
                </div>
                <!--                <div class="col-md-3">-->
                <!--                    <router-link v-on:click="submitFormSettings()" class="helper-btn btn btn&#45;&#45;primary">-->
                <!--                        <span v-html="$t('SetupStepThreeNextButton')"></span>-->
                <!--                    </router-link>-->
                <!--                </div>-->
                <div class="col-md-3">
                    <button class="profile-form__send helper-btn btn btn--primary" type="button"
                            v-on:click="submitFormSettings()" name="send-data">
                        <!--                            <button class="profile-form__send helper-btn btn btn&#45;&#45;primary" type="button" v-on:click="submitForm()" :disabled="!this.validateForm()" name="send-data">-->
                        <span v-html="$t('SetupStepThreeNextButton')"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div style="margin-top: 70px" v-else>
        <p>{{ $t('SetupStepAccessDenied') }}</p>
    </div>
</template>

<script>
import {setTheme} from '../../plugins/dark-mode';
import {mapGetters, mapActions} from 'vuex';
import ProfileLangSwitcher from "../../components/profile/ProfileLangSwitcher";
import ProfileStages from "../../components/profile/ProfileStages";
import ProfileSwitcher from "../../components/profile/ProfileSwitcher";

export default {
    components: {
        ProfileLangSwitcher,
        ProfileStages,
        ProfileSwitcher
    },
    data() {
        return {
            show: false,
            form: {
                sound: {
                    name: 'sound',
                    checked: true,
                    text: this.$t('SetupStepThreeCheckbox1')
                },
                sendEmail: {
                    name: 'sendEmail',
                    checked: true,
                    text: this.$t('SetupStepThreeCheckbox2')
                },
                googlePushMessages: {
                    name: 'googlePushMessages',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox3'),
                    isDisabled: true
                },
                importantThings: {
                    name: 'importantThings',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox4'),
                    isDisabled: true
                },
                deliveryServiceTracking: {
                    name: 'deliveryServiceTracking',
                    checked: true,
                    text: this.$t('SetupStepThreeCheckbox5')
                },
                darkMode: {
                    name: 'darkMode',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox6')
                },
                classicMode: {
                    name: 'classicMode',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox7'),
                    isDisabled: true
                },
                lifeLine: {
                    name: 'lifeLine',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox8')
                },
                background: {
                    name: 'background',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox9')
                }
            }
        }
    },
    computed: {
        ...mapGetters([
            'GET_THEME',
            'GET_LOCALE',
            'GET_LIFELINE',
            'GET_BACKGROUND'
        ]),
        darkTheme: function () {
            return this.form.darkMode.checked;
        },
        lifeLine: function () {
            return this.form.lifeLine.checked;
        },
        locale: function () {
            return this.GET_LOCALE;
        },
        backgroundUrl: function () {
            return this.GET_BACKGROUND;
        },
    },
    watch: {
        darkTheme: function (status) {
            this.GET_THEME_FROM_SWITCHER(status);
            setTheme(status);
        },
        lifeLine: function (status) {
            this.GET_LIFELINE_FROM_SWITCHER(status);
        },
        locale: function (locale) {
            this.updateLocale();
        }
    },
    mounted() {
        setTheme(this.GET_THEME);
        this.show = true
        // this.checkPermission()

        this.form.darkMode.checked = this.GET_THEME;
        this.form.lifeLine.checked = this.GET_LIFELINE;
        this.submitFormSettings(false)
    },

    methods: {
        ...mapActions([
            'GET_THEME_FROM_SWITCHER',
            'GET_LIFELINE_FROM_SWITCHER'
        ]),
        updateLocale() {
            this.form.sound.text = this.$t('SetupStepThreeCheckbox1');
            this.form.sendEmail.text = this.$t('SetupStepThreeCheckbox2');
            this.form.googlePushMessages.text = this.$t('SetupStepThreeCheckbox3');
            this.form.importantThings.text = this.$t('SetupStepThreeCheckbox4');
            this.form.deliveryServiceTracking.text = this.$t('SetupStepThreeCheckbox5');
            this.form.darkMode.text = this.$t('SetupStepThreeCheckbox6');
            this.form.classicMode.text = this.$t('SetupStepThreeCheckbox7');
            this.form.lifeLine.text = this.$t('SetupStepThreeCheckbox8');
            this.form.background.text = this.$t('SetupStepThreeCheckbox9');
        },

        submitFormSettings: function (reload = true) {
            axios.post('/customer/register/setting', {
                sound: this.form.sound.checked,
                sendEmail: this.form.sendEmail.checked,
                googlePushMessages: this.form.googlePushMessages.checked,
                importantThings: this.form.importantThings.checked,
                deliveryServiceTracking: this.form.deliveryServiceTracking.checked,
                darkMode: this.form.darkMode.checked,
                classicMode: this.form.classicMode.checked,
                lifeLine: this.form.lifeLine.checked,
                background: this.form.background.checked,
                key: this.$route.query.key,
                backgroundUrl: this.backgroundUrl,
            }).then((response) => {
                if (reload) {
                    window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/setup/process-four"
                }
            })
        },

        checkPermission: function () {
            if (this.$route.query.key !== '' && this.$route.query.key != undefined) {
                axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/register/permission/${this.$route.query.key}`).then((response) => {
                    if (response.data) {
                        this.show = true
                    }
                })
            }
        },
    },
}
</script>
