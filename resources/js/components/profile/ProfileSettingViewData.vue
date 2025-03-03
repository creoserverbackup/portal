<template>
    <div class="profile-setting">
        <div>
            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading1') }}</legend>
            <ProfileInputGroup :inputGroupData="form.username"/>
            <!--                <ProfileInputGroup  :inputGroupData="form.customerName"/>-->
            <ProfileInputGroup :inputGroupData="form.address"/>
            <div class="row no-gutters">
                <div class="col-7">
                    <ProfileInputGroup :inputGroupData="form.postcode"/>
                </div>
                <div class="col-5 pl-5">
                    <div class="d-flex align-items-baseline">
                        <div class="px-2">Nr:</div>
                        <div>
                            <ProfileInputGroup :inputGroupData="form.house"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-5">
                    <ProfileInputGroup :inputGroupData="form.region"/>
                    <!--                        <ProfileSelectInput :objectSelect="form.region" :classAdd="'last-input'"/>-->
                </div>
                <div class="col-7">
                    <profile-select-country :objectSelect="form.country" :classAdd="'last-input'"/>
                </div>
            </div>

            <!--            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading2') }}</legend>-->
            <ProfileInputGroup :inputGroupData="form.email"/>
            <profile-input-group :inputGroupData="form.emailInvoice"/>
            <ProfileInputGroup :inputGroupData="form.phone"/>
            <!--            <ProfileInputGroup :inputGroupData="form.phoneMobile"/>-->

            <!--            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading3') }}</legend>-->
            <profile-category-select @changeSelect="changeSelect" :inputGroupData="form.selects.category"/>
        </div>
        <div>
            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading5') }}</legend>
            <ProfileInputGroup :inputGroupData="form.customerName"/>
            <ProfileInputGroup :inputGroupData="form.kvk"/>
            <ProfileInputGroup :inputGroupData="form.btw"/>

            <legend class="fs-22 mb-3 mt-50 c-primary">{{ $t('ProfileCheckboxTitle') }} <span class="c-gray fs-12">(optioneel)</span>
            </legend>
            <profile-checkbox :inputGroupData="form.weekday"/>
            <profile-checkbox :inputGroupData="form.weekend"/>
            <profile-checkbox :inputGroupData="form.certainDays"/>
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
            <profile-checkbox :inputGroupData="form.neighbour"/>
        </div>
        <div>
            <legend class="fs-22 mb-3 c-primary">{{ $t('SetupStepTwoHeading8') }}</legend>
            <div class="profile-form__keycode-profile">
                <div class="profile-form__keycode-heading" v-html="$t('SetupStepTwoGeneratedLabel')"></div>
                <div class="profile-form__keycode-code-profile">{{ form.customerId }}</div>
            </div>
            <p class="profile-form__helper" v-html="$t('SetupStepTwoGeneratedHelper')"></p>

            <legend class="fs-22 mb-3 mt-50 c-primary">{{ $t('SetupStepTwoHeading7') }}</legend>
            <figure class="profile-form__user-logo" :class="{error: form.file.hasError}">
                <div class="upload-file__img-wrap" v-if="form.uploadLogo != ''">
                    <img :src="form.uploadLogo" class="upload-file__img" ref="image" width="100">
                </div>
                <div class="upload-file__img-wrap" v-else>
                    <img src="images/upload-img.jpg" alt="" class="upload-file__img" ref="image" width="100">
                </div>
                <figcaption class="upload-file__figcaption">
                    <input type="file" :name="form.file.name" class="upload-file__upload"
                           accept=".png, .jpg, .jpeg, .svg" v-on:change="onSelectFile" ref="file"
                           id="user-logo-file">
                    <label for="user-logo-file" class="upload-file__label" ref="label"
                           v-html="$t('SetupStepTwoUploadButton')"></label>
                    <small class="upload-file__desc" ref="helper" v-html="$t('SetupStepTwoUploadHelper')"></small>
                </figcaption>
            </figure>
        </div>

        <div>
            <legend class="fs-22 mb-3 c-primary">{{ $t('DeliveryAddress') }}</legend>
            <ProfileInputGroup :inputGroupData="form.deliveryUsername"/>
            <ProfileInputGroup :inputGroupData="form.deliveryCustomerName"/>
            <ProfileInputGroup :inputGroupData="form.deliveryNamens"/>
            <ProfileInputGroup :inputGroupData="form.deliveryAddress"/>
            <div class="row no-gutters">
                <div class="col-7">
                    <ProfileInputGroup :inputGroupData="form.deliveryPostcode"/>
                </div>
                <div class="col-5 pl-5">
                    <div class="d-flex align-items-baseline">
                        <div class="px-2">Nr:</div>
                        <div>
                            <ProfileInputGroup :inputGroupData="form.deliveryHouse"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-5">
                    <ProfileInputGroup :inputGroupData="form.deliveryRegion"/>
                </div>
                <div class="col-7">
                    <profile-select-country :objectSelect="form.deliveryCountry" :classAdd="'last-input'"/>
                </div>
            </div>
            <ProfileInputGroup :inputGroupData="form.deliveryEmail"/>
            <ProfileInputGroup :inputGroupData="form.deliveryPhone"/>
        </div>

        <div>

        </div>

        <div>
            <div class="d-flex">
                <button @click="goBack" type="button" class="helper-btn btn btn--primary mr-15">
                    <span>Annuleren</span>
                </button>
                <button v-on:click="submitForm()" type="button" class="helper-btn btn btn--primary">
                    <span>Wijziging opslaan</span>
                </button>
            </div>
        </div>

    </div>

</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import {setTheme} from '../../plugins/dark-mode'
import ProfileInputGroup from "./ProfileInputGroup";
import ProfileSelectInput from "./ProfileSelectInput";
import ProfileSelectGroup from "./ProfileSelectGroup";

import VueRecaptcha from 'vue-recaptcha';
import ProfileSelectCountry from "./ProfileSelectCountry";
import {getMessageError} from "../../utils";
import ProfileCheckbox from "./profileCheckbox";
import {default as CustomerCategories} from '../../data/customerCategories'
import ProfileCategorySelect from "./profileCategorySelect";


export default {
    name: "ProfileSettingViewData",
    components: {
        ProfileCategorySelect,
        ProfileCheckbox,
        ProfileSelectCountry,
        ProfileInputGroup,
        ProfileSelectInput,
        ProfileSelectGroup,
        VueRecaptcha
    },
    data() {
        return {
            file: '',
            startSubmit: false,
            form: {
                customerId: '',
                uploadLogo: '',
                username: {
                    placeholder: this.$t('SetupStepTwoUsernamePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 3,
                    }
                },
                address: {
                    placeholder: this.$t('SetupStepTwoAddressPlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 2,
                        regEx: '[/0-9a-zA-Z /ig]'
                    }
                },
                postcode: {
                    placeholder: this.$t('SetupStepTwoPostalCodePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 4
                    }
                },
                house: {
                    placeholder: this.$t('SetupStepTwoHousePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 1,
                        regEx: '[/0-9a-zA-Z /ig]'
                    }
                },
                region: {
                    placeholder: 'Woonplaats ...',
                    value: '',
                    required: true,
                },
                mailbox: {
                    placeholder: this.$t('SetupStepTwoMailboxPlaceholder'),
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        minLength: 2,
                        regEx: '[0-9a-zA-Z ]+'
                    }
                },
                email: {
                    placeholder: this.$t('SetupStepTwoEmailPlaceholder'),
                    value: '',
                    type: 'email',
                    required: true,
                    isValidate: false,
                },
                emailInvoice: {
                    placeholder: this.$t('SetupStepTwoEmailInvoicePlaceholder'),
                    value: '',
                    type: 'email',
                    required: false,
                    isValidate: false,
                },
                phone: {
                    placeholder: this.$t('SetupStepTwoPhonePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        // regEx: '[^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$]'
                    }
                },
                phoneMobile: {
                    placeholder: this.$t('SetupStepTwoMobilePlaceholder'),
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        // regEx: '[^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$]'
                    },
                    helper: this.$t('SetupStepTwoMobileHelper')
                },
                customerName: {
                    placeholder: this.$t('SetupStepTwoCompanyNamePlaceholder'),
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        required: true,
                        minLength: 3
                    }
                },
                kvk: {
                    placeholder: this.$t('SetupStepTwoKvkNumberPlaceholder'),
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
                    value: '',
                    type: 'number',
                    required: false,
                    isValidate: true,
                    validate: {
                        maxLength: 25,
                        regEx: '[0-9]+'
                    },
                },
                selects: {
                    category: {
                        selected: 1,
                        placeholder: this.$t('SetupStepTwoCompanyPlaceholder'),
                        value: CustomerCategories,
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
                avatar: {},


                deliveryCustomerName: {
                    placeholder: this.$t('SetupStepTwoCompanyNamePlaceholder'),
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        required: true,
                        minLength: 3
                    }
                },
                deliveryUsername: {
                    placeholder: this.$t('SetupStepTwoUsernamePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 3,
                    }
                },

                deliveryNamens: {
                    placeholder: 'Namens ...',
                    value: '',
                    required: false,
                    isValidate: false,
                    validate: {
                        minLength: 3,
                    }
                },
                deliveryAddress: {
                    placeholder: this.$t('SetupStepTwoAddressPlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 2,
                        regEx: '[/0-9a-zA-Z /ig]'
                    }
                },
                deliveryPostcode: {
                    placeholder: this.$t('SetupStepTwoPostalCodePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 4
                    }
                },
                deliveryHouse: {
                    placeholder: this.$t('SetupStepTwoHousePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        minLength: 1,
                        regEx: '[/0-9a-zA-Z /ig]'
                    }
                },
                deliveryRegion: {
                    placeholder: 'Woonplaats ...',
                    value: '',
                    required: true,
                },
                deliveryEmail: {
                    placeholder: this.$t('SetupStepTwoEmailPlaceholder'),
                    value: '',
                    type: 'email',
                    required: true,
                    isValidate: false,
                },
                deliveryPhone: {
                    placeholder: this.$t('SetupStepTwoPhonePlaceholder'),
                    value: '',
                    required: true,
                    isValidate: false,
                    validate: {
                        // regEx: '[^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$]'
                    }
                },
                deliveryCountry: {
                    name: 'country',
                    selected: '',
                    placeholder: 'Field country',
                    value: [],
                },


            }
        }
    },
    mounted() {
        setTheme(this.GET_THEME)
        this.GET_LOADING_FROM_REQUEST(false)
        this.getCountry()
        this.getCustomerInfo()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getCustomerInfo() {
            axios.get('/customer/info').then((response) => {

                console.log(" response.data response.data")
                console.log(response.data)

                this.setValueCustomer(response.data)
            }).catch((e) => {
                console.log(e)
            })
        },
        submitForm() {
            let data = this.getAllData()

            this.GET_LOADING_FROM_REQUEST(true)
            axios.post('/customer/info', data).then((response) => {
                this.$root.$emit('popupMessages', "Saved successfully")
                this.GET_LOADING_FROM_REQUEST(false)
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.$root.$emit('popupMessages', getMessageError(e))
            })
        },
        setValueCustomer(data) {
            this.form.customerId = data.customerId

            if (typeof data.logo != "undefined") {
                this.form.uploadLogo = data.logo
            }
            for (let key in data) {
                for (let i in this.form) {
                    if (i === key && key != 'country') {
                        if (this.form[i].hasOwnProperty('value')) {
                            this.form[i].value = data[key]
                        }
                        if (this.form[i].hasOwnProperty('status')) {
                            this.form[i].status = data[key]
                        }
                    }
                }
                for (let i in this.form.selects) {
                    if (i === key && data[key]) {
                        this.form.selects[i].selected = data[key]
                    }
                }
                for (let i in this.form.days) {
                    if (key === this.form.days[i].day) {
                        this.form.days[i].isActive = data[key]
                    }
                }
            }
            this.form.country.selected = data.country
            this.form.deliveryCountry.selected = data.deliveryCountry
            this.checkCountry()
        },
        checkCountry() {
            let searchCountry = false
            for (let country in this.form.country.value) {
                if (this.form.country.selected === country) {
                    searchCountry = true
                }
            }

            if (searchCountry == false) {
                this.form.country.value.push({name: this.form.country.selected})
            }
        },
        getCountry() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/countries`).then((response) => {
                this.form.country.value = response.data
                this.form.deliveryCountry.value = response.data
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
        goBack() {
            this.$router.go(-1)
        },
        getAllData() {

            let days = []
            for (let day in this.form.days) {
                if (this.form.days[day].isActive) {
                    days.push(this.form.days[day].day)
                }
            }
            return {
                customerId: this.form.customerId,
                country: this.form.country.selected,
                region: this.form.region.value,
                address: this.form.address.value,
                house: this.form.house.value,
                postcode: this.form.postcode.value,
                mailbox: this.form.mailbox.value,
                username: this.form.username.value,
                email: this.form.email.value,
                emailInvoice: this.form.emailInvoice.value,
                phone: this.form.phone.value,
                phoneMobile: this.form.phoneMobile.value,
                customerName: this.form.customerName.value,
                kvk: this.form.kvk.value,
                btw: this.form.btw.value,
                category: this.form.selects.category.selected,

                weekday: this.form.weekday.status,
                weekend: this.form.weekend.status,
                certainDays: this.form.certainDays.status,
                neighbour: this.form.neighbour.status,
                days: days,

                avatar: this.form.avatar,
                avatarFileName: this.form.file.name,

                deliveryCustomerName: this.form.deliveryCustomerName.value,
                deliveryUsername: this.form.deliveryUsername.value,
                deliveryNamens: this.form.deliveryNamens.value,
                deliveryAddress: this.form.deliveryAddress.value,
                deliveryPostcode: this.form.deliveryPostcode.value,
                deliveryHouse: this.form.deliveryHouse.value,
                deliveryRegion: this.form.deliveryRegion.value,
                deliveryEmail: this.form.deliveryEmail.value,
                deliveryPhone: this.form.deliveryPhone.value,
                deliveryCountry: this.form.deliveryCountry.selected,

            }
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

            this.file = this.$refs.file.files[0]
            const input = this.$refs['file'],
                    label = this.$refs['label'],
                    image = this.$refs['image'],
                    helper = this.$refs['helper']

            let fileName = '',
                    letThis = this

            let typeAllowed = ['image/jpeg', 'image/png', 'image/svg+xml']
            let isAllowedFormat = typeAllowed.indexOf(file.type) !== -1

            if (!isAllowedFormat) {
                letThis.form.file.hasError = true
                helper.innerHTML = this.$t('SetupStepTwoUploadHelper')
            }

            if (input.files && input.files.length > 1) {
                fileName = (input.getAttribute('data-multiple-caption') || '').replace('{count}', input.files.length)
            } else {
                fileName = input.value.split('\\').pop()
            }
            if (fileName) {
                this.form.uploadLogo = ''
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
    computed: {
        ...mapGetters([
            'GET_THEME'
        ]),
        statusDates: function () {
            return this.form.certainDays.status
        }
    },
    watch: {
        form: {
            deep: true,
            handler(value) {
                // if (this.form.btw.value != null) {
                //     this.form.btw.value = this.form.btw.value.replace(/\D/g, '')
                // }
            }
        },
        statusDates: function (status) {
            for (let day in this.form.days) {
                let thisInput = this.form.days[day]
                thisInput.isDisabled = !status
            }
        }
    },
}
</script>
