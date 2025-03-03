<template>
    <div class="col-4" v-else>
        <div class="position-relative p-10 b-secondary">
            <div class="fs-24 c-secondary mb-40">Edit your profile</div>
            <span class="payment-flow__step2-links-div" v-on:click="save">save</span>
            <span class="fs-16 d-block mb-20">
                <span class="fs-16 c-black">Creo klantnummer: </span>
                <span class="fs-16 c-gray">{{ user.customerId }} </span>
            </span>



            <table>
                <tbody>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Bedrijfsnaam:</span>
                    </td>
                    <td>
                        <input class="fs-12 p-2" type="text" v-model="user.customerName">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Contact persoon: </span>
                    </td>
                    <td>
                        <input class="fs-12 p-2" type="text" v-model="user.username">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Adres: </span>
                    </td>
                    <td class="row m-0 d-flex">
                        <input class="col-8 fs-12 p-2" type="text" v-model="user.address">
                        <span class="col-2 pt-1">Nr:</span>
                        <input class="col-2 fs-12 p-2" type="text" v-model="user.house">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Postcode: </span>
                    </td>
                    <td>
                        <input class="fs-12 p-2" type="text" v-model="user.postcode">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Land: </span>
                    </td>
                    <td>
                        <profile-select-country :objectSelect="country"
                                                :classAdd="'last-input'"
                                                @changeSelect="changeCountry"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Woonplaats: </span>
                    </td>
                    <td>
                        <ProfileSelectInput :objectSelect="region"
                                            :classAdd="'last-input'"
                                            @changeSelect="changeRegion"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Email: </span>
                    </td>
                    <td>
                        <input class="fs-12 p-2" type="text" v-model="user.email">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Email Invoice: </span>
                    </td>
                    <td>
                        <input class="fs-12 p-2" type="text" v-model="user.emailInvoice">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fs-16 c-black">Type Klant: </span>
                    </td>
                    <td>
                        <input class="fs-12 p-2" type="text" v-model="user.phone">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import ProfileCategorySelect from "../profile/profileCategorySelect";
import ProfileSelectCountry from "../profile/ProfileSelectCountry";
import ProfileSelectInput from "../profile/ProfileSelectInput";
import ProfileSelectGroup from "../profile/ProfileSelectGroup";
import {default as CustomerCategories} from "../../data/customerCategories";

export default {
    name: "PaymentFlowStep2UserDataEdit",
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
            region: {
                name: 'region',
                placeholder: 'Woonplaats',
                value: [],
                selected: '',
            },
            category: {
                placeholder: this.$t('SetupStepTwoCompanyPlaceholder'),
                name: 'category',
                value: CustomerCategories,
                required: true,
                isValidate: false
            },
        }
    },
    mounted() {
        this.getCountry()
    },
    methods: {
        getCountry() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/countries`).then((response) => {
                this.country.value = response.data
                this.country.selected = this.user.country
                this.region.selected = this.user.region
                this.getRegion(this.country)
            }).catch(function (e) {
                console.log(e)
            })
        },
        changeCountry($object) {
            this.user.country = $object.selected
            this.region.selected = ''
            this.region.disabled = this.country.selected == ''

            this.getRegion($object)
        },

        changeRegion($object) {
            this.user.region = $object.selected
        },
        getRegion($object) {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/regions?countryName=${$object.selected}`).then((response) => {
                this.region.value = response.data
            }).catch(function (e) {
                console.log(e)
            })
        },

        save() {
            this.$emit('saveUser', this.user);
        }
    }
}
</script>

<style scoped>

</style>