<template>
    <div class="lang-area">
        <div class="lang-area__desc" v-html="$t('SetupStepThreeLangText')"></div>

        <ul class="lang-area__list">
            <li class="lang-area__item"
                v-for="lang in languages">
                <input type="radio" name="lang"
                       :id="`${lang.name}-lang`"
                       :value="lang.name"
                       v-on:click="setLocale(lang.name)"
                       :checked="lang.status">
                <label class="lang-area__lang"
                       :for="`${lang.name}-lang`">
                    <img class="lang-area__img"
                         :src="`/images/langs/${lang.name}.svg`"
                         :alt="lang.text">
                    <span class="lang-area__caption">{{ lang.text }}</span>
                </label>
            </li>
        </ul>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    data() {
        return {
            languages: [
                {
                    name: 'nl',
                    status: false,
                    text: 'nederlands'
                },
                {
                    name: 'en',
                    status: false,
                    text: 'english'
                },
                // {
                //     name: 'de',
                //     status: false,
                //     text: 'deutch'
                // },
                // {
                //     name: 'fr',
                //     status: false,
                //     text: 'francais'
                // },
                // {
                //     name: 'es',
                //     status: false,
                //     text: 'spanish'
                // },
                // {
                //     name: 'ru',
                //     status: false,
                //     text: 'русский'
                // }
            ]
        }
    },
    computed: {
        ...mapGetters([
            'GET_LOCALE'
        ])
    },
    mounted() {
        let localeNow = this.GET_LOCALE || process.env.MIX_VUE_APP_I18N_LOCALE;

        this.languages.find(lang => {
            if (lang.name === localeNow) {
                return lang.status = true;
            }
        });

    },
    methods: {
        ...mapActions([
            'GET_LOCALE_FROM_SWITCHER'
        ]),
        setLocale(locale) {
            this.$root.$emit('popupMessages', 'Language change is temporarily unavailable')
            // this.GET_LOCALE_FROM_SWITCHER(locale);
            //
            // localStorage.setItem('locale', locale);
            // this.locale = locale;
            // this.$i18n.locale = locale;
        }
    }
}
</script>

