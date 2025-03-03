<template>
    <div class="profile-page">
        <div class="profile-page-row">
            <div class="profile-page-column">
                <div class="helper-section">
                    <div class="h2">{{ $t('SetupStepThreeHeading1') }}</div>

                    <ProfileSwitcher :inputGroupData="form.sound"/>
                    <ProfileSwitcher :inputGroupData="form.sendEmail"/>
                    <ProfileSwitcher :inputGroupData="form.googlePushMessages"/>
                    <ProfileSwitcher :inputGroupData="form.importantThings"/>
                    <ProfileSwitcher :inputGroupData="form.deliveryServiceTracking"/>
                </div>
                <div class="profile-page-taal">
                    <div class="helper-section">
                        <div class="h2">{{ $t('SetupStepThreeHeading3') }}</div>
                        <ProfileLangSwitcher/>
                    </div>
                </div>
            </div>
            <div class="profile-page-column">
                <div class="helper-section">
                    <div class="h2">{{ $t('SetupStepThreeHeading2') }}</div>
                    <ProfileSwitcher :inputGroupData="form.darkMode"/>
                    <ProfileSwitcher :inputGroupData="form.classicMode"/>
                    <ProfileSwitcher :inputGroupData="form.lifeLine"/>
                    <unsplash :inputGroupData="form.background"/>
                </div>
            </div>
        </div>
        <div class="profile-page-buttons">
            <button class="btn btn--primary" @click='cancel'>Annuleren</button>
            <button class="btn btn--primary" @click='saveCustomerSettings'>Wijziging opslaan</button>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import {setTheme} from "../../plugins/dark-mode";
import ProfileLangSwitcher from "./ProfileLangSwitcher";
import ProfileSwitcher from "./ProfileSwitcher";

export default {
    name: "ProfileMain",
    components: {
        ProfileLangSwitcher,
        ProfileSwitcher
    },
    data() {
        return {
            settings: {},
            form: {
                sound: {
                    name: 'sound',
                    checked: false,
                    text: this.$t('SetupStepThreeCheckbox1'),
                    isDisabled: true
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
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false)
        this.getCustomerSettings()
    },
    methods: {
        ...mapActions([
            'GET_THEME_FROM_SWITCHER',
            'GET_LIFELINE_FROM_SWITCHER',
            'GET_LOADING_FROM_REQUEST',
            'SET_BACKGROUND_FROM_UNSPLASH'
        ]),
        updateCheckboxStatus(goBack = false) {
            this.form.sound.checked = !!Number(this.settings.sound)
            this.form.sendEmail.checked = !!Number(this.settings.sendEmail)
            this.form.googlePushMessages.checked = !!Number(this.settings.googlePushMessages)
            this.form.importantThings.checked = !!Number(this.settings.importantThings)
            this.form.deliveryServiceTracking.checked = !!Number(this.settings.deliveryServiceTracking)

            this.form.darkMode.checked = !!Number(this.settings.darkMode)
            this.form.classicMode.checked = !!Number(this.settings.classicMode)
            this.form.lifeLine.checked = !!Number(this.settings.lifeLine)
            this.form.background.checked = !!Number(this.settings.background)
            setTheme(this.settings.darkMode);
            this.GET_THEME_FROM_SWITCHER(this.settings.darkMode)
            this.GET_LIFELINE_FROM_SWITCHER(this.form.lifeLine.checked)

            if (this.settings.background != 0) {
                this.SET_BACKGROUND_FROM_UNSPLASH(this.settings.backgroundUrl);
            } else {
                this.SET_BACKGROUND_FROM_UNSPLASH('');
            }

            if (goBack) {
                this.goBack()
            }
        },
        getCustomerSettings(goBack = false) {
            this.SET_BACKGROUND_FROM_UNSPLASH('');
            axios.get('/customer/setting').then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.settings = response.data.data
                this.updateCheckboxStatus(goBack)
            }).catch((e) => {
                console.log(e)
            })
        },
        saveCustomerSettings() {

            let checkboxStatuses = {
                'sound': this.form.sound.checked,
                'sendEmail': this.form.sendEmail.checked,
                'googlePushMessages': this.form.googlePushMessages.checked,
                'importantThings': this.form.importantThings.checked,
                'deliveryServiceTracking': this.form.deliveryServiceTracking.checked,
                'darkMode': this.form.darkMode.checked,
                'classicMode': this.form.classicMode.checked,
                'lifeLine': this.form.lifeLine.checked,
                'background': this.form.background.checked,
                'backgroundUrl': this.backgroundUrl,
            }

            this.GET_LOADING_FROM_REQUEST(true);

            axios.post('/customer/setting', JSON.stringify(checkboxStatuses)).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.settings = response.data.data
                this.updateCheckboxStatus()
                this.$root.$emit('popupMessages', 'Saved successfully')
            }).catch((error) => {
                this.$root.$emit('popupMessages', 'An error occurred while saving')
                this.GET_LOADING_FROM_REQUEST(false);
            })
        },
        async cancel() {
            await this.getCustomerSettings(true)
        },
        goBack() {
            this.$router.go(-1)
        },
    },
    computed: {
        ...mapGetters([
            'GET_THEME',
            'GET_LOCALE',
            'GET_LIFELINE',
            'GET_BACKGROUND'
        ]),
        darkTheme: function() {
            return this.form.darkMode.checked;
        },
        lifeLine: function() {
            return this.GET_LIFELINE;
        },
        locale: function() {
            return this.GET_LOCALE;
        },
        backgroundUrl: function() {
            return this.GET_BACKGROUND;
        },
    },
    watch: {
        // darkTheme: function(status) {
        //     this.GET_THEME_FROM_SWITCHER(status);
        //     // setTheme(status);
        // },
        // lifeLine: function(status) {
        //     this.GET_LIFELINE_FROM_SWITCHER(status);
        // },
        locale: function(locale) {
            this.updateLocale();
        }
    },

}
</script>
<style scoped>
</style>
