<template>
    <div class="position-relative p-10 b-secondary">
        <div class="fs-24 c-secondary mb-40">Accountgegevens</div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Voor en Achternaam<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="text" placeholder="Voor en achternaam" v-model="user.username">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Adres<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="text" placeholder="Straat" v-model="user.address">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Postcode en huisnummer<span class="c-red">*</span>
            </div>
            <div class="w-50 d-flex-row-between">
                <div class="w-70">
                    <input class="w-100 fs-12 p-2" type="text" placeholder="Postcode" v-model="user.postcode">
                </div>
                <div class="w-25">
                    <input class="w-100 fs-12 p-2" type="text" placeholder="Nr." v-model="user.house">
                </div>
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Woonplaats<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="text" placeholder="Woonplaats" v-model="user.region">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Land<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <profile-select-country :objectSelect="country" :classAdd="'last-input'" @changeSelect="changeCountry"/>
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Email<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="email" placeholder="Email" v-model="user.email">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Email voor factuur <span class="c-gray fs-12">(optioneel)</span>
            </div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="email" placeholder="Email voor factuur" v-model="user.emailInvoice">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Telefoon nummer<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <input class="w-100 fs-12 p-2" type="text" placeholder="Telefoonnummer" v-model="user.phone">
            </div>
        </div>

        <div class="d-flex mb-4">
            <div class="fs-16 c-black w-50">
                Type Klant<span class="c-red">*</span>
            </div>
            <div class="w-50">
                <profile-category-select @changeSelect="changeCategory" :inputGroupData="category"/>
            </div>
        </div>
    </div>
</template>

<script>

import ProfileCategorySelect from "../profile/profileCategorySelect";
import ProfileSelectCountry from "../profile/ProfileSelectCountry";
import ProfileSelectInput from "../profile/ProfileSelectInput";
import ProfileSelectGroup from "../profile/ProfileSelectGroup";
import {default as CustomerCategories} from "../../data/customerCategories";
import {getMessageError} from "../../utils";
import {mapActions} from "vuex";

export default {
    name: "PaymentFlowStep2User",
    components: {
        ProfileCategorySelect,
        ProfileSelectCountry,
        ProfileSelectInput,
        ProfileSelectGroup
    },
    props: {
        user: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            isEditInfoUser: false,
            isEditInfoUserNumber: false,
            country: {
                name: 'country',
                selected: '',
                placeholder: 'Field country',
                value: [],
            },
            category: {
                placeholder: this.$t('SetupStepTwoCompanyPlaceholder'),
                name: 'category',
                selected: '',
                value: CustomerCategories,
                required: true,
                isValidate: false
            },
        }
    },
    mounted() {
        this.getCountry()
        this.checkBtw()
        this.setCategory()
    },
    methods: {
        ...mapActions([
            'SET_BTW',
        ]),
        setCategory() {
            this.category.value.forEach((item) => {
                if (this.user.category === item.categoryId) {
                    this.category.selected = item.categoryId
                }
            })
        },
        getCountry() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/countries`).then((response) => {
                this.country.value = response.data
                this.country.selected = this.user.country
            }).catch((e) => {
                console.log(e)
            })
        },
        changeCountry($object) {
            this.user.country = $object.selected
            this.checkBtw()
        },
        changeCategory(name, $object) {
            this.user.category = $object.selected
        },
        checkBtw() {
            axios.post(`/customer/btw`, {
                customer: this.user,
            }).then((response) => {
                this.SET_BTW(response.data)
            }).catch((e) => {
                this.$root.$emit('popupMessages', getMessageError(e))
            })
        },
    }
}
</script>

<style scoped>

</style>