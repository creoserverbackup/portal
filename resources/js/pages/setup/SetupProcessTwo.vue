<template>
    <div>
        <div class="container-fluid" v-show="show">
            <selectBackground/>
            <ProfileStages/>
            <messages :messages="messages"/>

            <vue-recaptcha
                ref="recaptcha"
                size="invisible"
                :loadRecaptchaScript="true"
                :sitekey="siteKey"
                @verify="register"
                @expired="onCaptchaExpired"
            />

            <form enctype="multipart/form-data" class="profile-form">
                <p class="profile-form__info">{{ $t('SetupStepTwoText') }}</p>
                <div class="row">
                    <div class="col-md-4">
                        <fieldset class="profile-form__fieldset">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading1') }}</legend>
                            <ProfileInputGroup v-bind:rules="{ required : true }" :inputGroupData="form.customerName"/>
                            <div class="row m-0">
                                <div class="col-8 m-0 p-0 mr-4">
                                    <ProfileInputGroup :inputGroupData="form.address"/>
                                </div>
                                <span class="col-1 p-2 ml-3">Nr:</span>
                                <div class="col-2 m-0 p-0 ml-1">
                                    <ProfileInputGroup :inputGroupData="form.house"/>
                                </div>
                            </div>
                            <!--                            <ProfileInputGroup :inputGroupData="form.address"/>-->
                            <div class="row">
                                <div class="col-5">
                                    <ProfileInputGroup :inputGroupData="form.postcode"/>
                                </div>
                                <div class="col-7">
                                    <ProfileSelectInput :objectSelect="form.country"
                                                        :classAdd="'last-input'"
                                                        @changeSelect="changeCountry"/>
                                </div>
                                <div class="col-5">
                                    <ProfileInputGroup :inputGroupData="form.mailbox"/>
                                </div>
                                <div class="col-md-7">
                                    <ProfileSelectInput :objectSelect="form.region"
                                                        :classAdd="'last-input'"/>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="profile-form__fieldset">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading2') }}</legend>
                            <ProfileInputGroup :inputGroupData="form.username"/>
                            <ProfileInputGroup :inputGroupData="form.email"/>
                            <ProfileInputGroup :inputGroupData="form.phone"/>
                            <ProfileInputGroup :inputGroupData="form.phoneMobile"/>
                        </fieldset>
                        <fieldset class="profile-form__fieldset">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading3') }}</legend>
                            <ProfileSelectGroup @changeSelect="changeSelect"
                                                :inputGroupData="form.selects.category"/>
                            <ProfileInputGroup :inputGroupData="form.kvk"/>
                            <ProfileInputGroup :inputGroupData="form.btw"/>
                        </fieldset>
                    </div>
                    <div class="col-md-4">
                        <fieldset class="profile-form__fieldset profile-form__fieldset--min-height-flow">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading5') }}</legend>
                            <profilecheckbox :inputGroupData="form.weekday"/>
                            <profilecheckbox :inputGroupData="form.weekend"/>
                            <profilecheckbox :inputGroupData="form.certainDays"/>
                            <div class="checkbox-label profile-form__checkbox profile-form__checkbox--days">
                                <div class="profile-form__days">
                                    <div class="profile-form__day" v-for="day in form.days">
                                        <input class="checkbox-label__input" type="checkbox"
                                               :name="day.day"
                                               :id="day.day"
                                               :checked="day.isActive"
                                               :disabled="day.isDisabled"
                                               v-model="day.isActive">
                                        <label class="checkbox-label__main"
                                               :for="day.day">{{ day.text }}</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="profile-form__fieldset">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading6') }}</legend>
                            <profilecheckbox :inputGroupData="form.neighbour"/>
                        </fieldset>
                        <fieldset class="profile-form__fieldset">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading7') }}</legend>
                            <figure class="profile-form__user-logo upload-file"
                                    :class="{error: form.file.hasError}">
                                <div class="upload-file__img-wrap">
                                    <img src="images/upload-img.jpg" alt="" class="upload-file__img" ref="image"
                                         width="100">
                                </div>
                                <figcaption class="upload-file__figcaption">
                                    <input type="file" :name="form.file.name" class="upload-file__upload"
                                           accept=".png, .jpg, .jpeg, .svg" v-on:change="onSelectFile" ref="file"
                                           id="user-logo-file">
                                    <label for="user-logo-file" class="upload-file__label" ref="label"
                                           v-html="$t('SetupStepTwoUploadButton')"></label>
                                    <small class="upload-file__desc" ref="helper"
                                           v-html="$t('SetupStepTwoUploadHelper')"></small>
                                </figcaption>
                            </figure>
                        </fieldset>
                    </div>
                    <div class="col-md-4 profile-form__column">
                        <fieldset class="profile-form__fieldset profile-form__fieldset--min-height-flow">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading8') }}</legend>
                            <div class="profile-form__keycode">
                                <div class="profile-form__keycode-heading"
                                     v-html="$t('SetupStepTwoGeneratedLabel')"></div>
                                <div class="profile-form__keycode-code" v-html="$t('SetupStepTwoGeneratedText')"></div>
                            </div>
                            <p class="profile-form__helper" v-html="$t('SetupStepTwoGeneratedHelper')"></p>
                        </fieldset>
                        <fieldset class="profile-form__fieldset">
                            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading9') }}</legend>
                            <profilecheckbox :inputGroupData="form.registerAndSubscribe"/>
                            <profilecheckbox :inputGroupData="form.agreePrivacyTerms"/>
                        </fieldset>
                        <div class="profile-form__fieldset text-center">
                            <!--                        <recaptcha></recaptcha>-->
                        </div>
                        <p class="profile-form__summary" v-html="$t('SetupStepTwoLastText')"></p>
                        <div class="profile-form__bottom">
                            <button class="profile-form__send helper-btn btn btn--primary" type="button"
                                    v-on:click="startSubmitForm()" name="send-data">
                                <!--                            <button class="profile-form__send helper-btn btn btn&#45;&#45;primary" type="button" v-on:click="submitForm()" :disabled="!this.validateForm()" name="send-data">-->
                                <span v-html="$t('SetupStepTwoSubmit')"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="profile-form__permission" v-if="!show">
            <p>{{ $t('SetupStepAccessDenied') }}</p>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
import {setTheme} from '../../plugins/dark-mode'
import Messages from "./Messages";
import ProfileStages from "../../components/profile/ProfileStages";
import ProfileInputGroup from "../../components/profile/ProfileInputGroup";
import ProfileSelectInput from "../../components/profile/ProfileSelectInput";
import ProfileSelectGroup from "../../components/profile/ProfileSelectGroup";
import VueRecaptcha from 'vue-recaptcha';

export default {
    components: {
        Messages,
        ProfileStages,
        ProfileInputGroup,
        ProfileSelectInput,
        ProfileSelectGroup,
        VueRecaptcha
    },
    data() {
        return {
            siteKey: process.env.MIX_RECAPTCHA_SITEKEY_V2,
            recaptchaToken: '',
            show: false,
            messages: [],
            file: '',
            form: {
                customerName: {
                    placeholder: this.$t('SetupStepTwoCompanyNamePlaceholder'),
                    name: 'company-name',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        required: true,
                        minLength: 3
                    }
                },
                address: {
                    placeholder: this.$t('SetupStepTwoAddressPlaceholder'),
                    name: 'address',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 2,
                        regEx: '[/0-9a-zA-Z /ig]'
                    }
                },
                house: {
                    placeholder: this.$t('SetupStepTwoHousePlaceholder'),
                    name: 'house',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 1,
                        regEx: '[/0-9a-zA-Z /ig]'
                    }
                },
                postcode: {
                    placeholder: this.$t('SetupStepTwoPostalCodePlaceholder'),
                    name: 'postal-code',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 4
                    }
                },
                // region: {
                //     placeholder: this.$t('SetupStepTwoRegionPlaceholder'),
                //     name: 'region',
                //     value: '',
                //     required: true,
                //     isValidate: false,
                //     validate: {
                //         minLength: 2,
                //         regEx: '[0-9a-zA-Z ]+'
                //     }
                // },
                mailbox: {
                    placeholder: this.$t('SetupStepTwoMailboxPlaceholder'),
                    name: 'mailbox',
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        minLength: 2,
                        regEx: '[0-9a-zA-Z ]+'
                    }
                },
                username: {
                    placeholder: this.$t('SetupStepTwoUsernamePlaceholder'),
                    name: 'user-name',
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 3,
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
                        minLength: 8,
                        // regEx: '[^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$]'
                    }
                },
                phoneMobile: {
                    placeholder: this.$t('SetupStepTwoMobilePlaceholder'),
                    name: 'phone-second',
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        // regEx: '[^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$]'
                    },
                    helper: this.$t('SetupStepTwoMobileHelper')
                },
                kvk: {
                    placeholder: this.$t('SetupStepTwoKvkNumberPlaceholder'),
                    name: 'kvk-number',
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        maxLength: 8,
                        regEx: '[0-9a-zA-Z ]+'
                    },
                },
                btw: {
                    placeholder: this.$t('SetupStepTwoBtwNumberPlaceholder'),
                    name: 'btw-number',
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        maxLength: 25,
                        regEx: '[0-9a-zA-Z -]+'
                    },
                },
                selects: {
                    category: {
                        selected: 1,
                        placeholder: this.$t('SetupStepTwoCompanyPlaceholder'),
                        name: 'company',
                        value: [],
                        required: true,
                        isValidate: false
                    },
                },
                country: {
                    name: 'country',
                    selected: '',
                    placeholder: 'Field country',
                    value: [],
                },
                region: {
                    name: 'region',
                    placeholder: 'Woonplaats',
                    value: [],
                    selected: '',
                    disabled: true,
                },
                weekday: {
                    name: 'working_days',
                    status: false,
                    text: this.$t('SetupStepTwoCheckbox1')
                },
                weekend: {
                    name: 'weekend',
                    status: false,
                    text: this.$t('SetupStepTwoCheckbox2')
                },
                certainDays: {
                    name: 'certain days',
                    status: false,
                    text: this.$t('SetupStepTwoCheckbox3')
                },
                neighbour: {
                    name: 'neighbour',
                    status: false,
                    text: this.$t('SetupStepTwoCheckbox5')
                },
                registerAndSubscribe: {
                    name: 'registerAndSubscribe',
                    status: false,
                    text: this.$t('SetupStepTwoCheckbox9')
                },
                agreePrivacyTerms: {
                    name: 'agreePrivacyTerms',
                    status: false,
                    text: this.$t('SetupStepTwoCheckbox10')
                },
                days: [
                    {
                        day: 'monday',
                        text: this.$t('SetupStepTwoMonday'),
                        isActive: true,
                        isDisabled: true
                    },
                    {
                        day: 'tuesday',
                        text: this.$t('SetupStepTwoTuesday'),
                        isActive: true,
                        isDisabled: true
                    },
                    {
                        day: 'wednesday',
                        text: this.$t('SetupStepTwoThursday'),
                        isActive: true,
                        isDisabled: true
                    },
                    {
                        day: 'thursday',
                        text: this.$t('SetupStepTwoMonday'),
                        isActive: true,
                        isDisabled: true
                    },
                    {
                        day: 'friday',
                        text: this.$t('SetupStepTwoFriday'),
                        isActive: true,
                        isDisabled: true
                    },
                    {
                        day: 'saturday',
                        text: this.$t('SetupStepTwoSaturday'),
                        isActive: false,
                        isDisabled: true
                    },
                    {
                        day: 'sunday',
                        text: this.$t('SetupStepTwoSunday'),
                        isActive: false,
                        isDisabled: true
                    }
                ],
                file: {
                    name: 'company-logo',
                    hasError: false
                },
                avatar: {}
            }
        }
    },
    computed: {
        ...mapGetters([
            'GET_THEME'
        ]),
        statusDates: function () {
            return this.form.certainDays.status
        }
    },
    watch: {
        statusDates: function (status) {
            for (let day in this.form.days) {
                let thisInput = this.form.days[day]
                thisInput.isDisabled = !status
            }
        }
    },
    mounted() {
        setTheme(this.GET_THEME)
        this.checkPermission()
        this.getCountry()
        this.getCompanyType()
        this.$refs.recaptcha.execute()
    },
    methods: {
        register(recaptchaToken) {
            this.recaptchaToken = recaptchaToken
            this.submitForm()
        },
        onCaptchaExpired() {
            this.$refs.recaptcha.reset()
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
        getCompanyType() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/customer/categories`).then((response) => {
                this.form.selects.category.value = response.data
            }).catch((e) => {
                console.log(e)
            })
        },
        changeCountry($object) {
            this.form.region.selected = ''
            this.form.region.disabled = this.form.country.selected == ''
            this.getRegion($object)
        },
        getCountry() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/countries`).then((response) => {
                this.form.country.value = response.data
            }).catch((e) => {
                console.log(e)
            })
        },
        getRegion($object) {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/regions?countryName=${$object.selected}`).then((response) => {
                this.form.region.value = response.data
            }).catch((e) => {
                console.log(e)
            })
        },
        changeSelect(name, $object) {
            for (let i in this.form.selects) {
                if (i === name) {
                    this.form.selects[i] = $object
                }
            }
        },
        getAllData() {
            let days = []
            for (let day in this.form.days) {
                if (this.form.days[day].isActive) {
                    days.push(this.form.days[day].day)
                }
            }
            return {
                customerName: this.form.customerName.value,
                address: this.form.address.value,
                house: this.form.house.value,
                postcode: this.form.postcode.value,
                // region: this.form.region,
                mailbox: this.form.mailbox.value,
                username: this.form.username.value,
                email: this.form.email.value,
                phone: this.form.phone.value,
                phoneMobile: this.form.phoneMobile.value,
                kvk: this.form.kvk.value,
                btw: this.form.btw.value,
                category: this.form.selects.category.selected,

                weekday: this.form.weekday.status,
                weekend: this.form.weekend.status,
                certainDays: this.form.certainDays.status,
                neighbour: this.form.neighbour.status,
                registerAndSubscribe: this.form.registerAndSubscribe.status,
                agreePrivacyTerms: this.form.agreePrivacyTerms.status,

                country: this.form.country.selected,
                region: this.form.region.selected,

                days: days,

                avatar: this.form.avatar,
                avatarFileName: this.form.file.name,
                key: this.$route.query.key,
                recaptcha: this.recaptchaToken

            }
        },

        async startSubmitForm() {
            await this.$refs.recaptcha.execute()
        },

        submitForm() {
            let stackErrors = []
            let data = this.getAllData()

            axios.post('/customer/register', data).then((response) => {
                this.$refs.recaptcha.reset()
                window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/setup/process-three?key=" + this.$route.query.key
            }).catch((errors) => {
                this.$refs.recaptcha.reset()
                for (let i in errors.response.data.errors) {
                    stackErrors.push(errors.response.data.errors[i])
                }
                this.messages = stackErrors
            })
        },
        onSelectFile() {
            this.form.file.name = this.$refs.file.files[0].name
            const file = this.$refs.file.files && this.$refs.file.files[0];

            function readFile(file, cb) {
                let FR = new FileReader();
                FR.readAsDataURL(file);
                FR.onloadend = () => {
                    cb(FR.result);
                };
            }

            if (file) {
                readFile(file, cb => {
                    this.$set(this.form, 'avatar', cb);
                });
            }

            const input = this.$refs['file'],
                label = this.$refs['label'],
                image = this.$refs['image'],
                helper = this.$refs['helper']

            let fileName = '',
                letThis = this

            if (input.files && input.files.length > 1) {
                fileName = (input.getAttribute('data-multiple-caption') || '').replace('{count}', input.files.length)
            } else {
                fileName = input.value.split('\\').pop()
            }
            if (fileName) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader()

                    reader.onload = function (e) {
                        const uploadedImageBase64 = e.target.result

                        let loadedImg = new Image()
                        loadedImg.onload = function () {
                            if (this.width > 500 || this.height > 500 || input.files[0].size > 2097152) {
                                letThis.form.file.hasError = true
                                helper.innerHTML = letThis.$t('SetupStepTwoUploadHelper')
                            } else {
                                letThis.form.file.hasError = false
                                helper.innerHTML = fileName
                            }
                            image.src = uploadedImageBase64
                        }
                        loadedImg.src = uploadedImageBase64
                    }
                    reader.readAsDataURL(input.files[0])
                    this.file = input.files[0]
                }
            } else {
                label.innerHTML = letThis.$t('SetupStepTwoUploadButton')
            }
        }
    },

}
</script>
