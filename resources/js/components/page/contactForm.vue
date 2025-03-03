<template>

    <div class="contact-form mt-3">
        <h1 class="mb-10">Service & Contact</h1>
        <div class="row mb-15 d-flex">
            <div class="col-12 col-md-7 col-2xl-7 mb-30">
                <form>
                    <div class="h4 fw-bold">Contact formulier</div>
                    <div>
                        <label class="">Uw naam</label>
                        <input type="text" v-model="name">
                    </div>
                    <div>
                        <label>Uw e-mail adres</label>
                        <input type="text" v-model="email">
                    </div>
                    <div>
                        <label>Uw telefoonummer</label>
                        <input type="text" v-model="phone">
                    </div>
                    <div class="d-f-column">
                        <label>Afdeling</label>
                        <div>
                            <v-select required
                                      :reduce="(department) => department.value"
                                      v-model="department"
                                      :options="departments">
                            </v-select>
                        </div>
                    </div>
                    <div>
                        <label>Uw bericht / vraag</label>
                        <textarea autocomplete="off" minlength="30" maxlength="600" v-model="text"
                                  style="min-height: 222px; height: 222px;">
                                    </textarea>

                        <div class="row">
                            <div class="col-12 ms-auto" @click="startSend">
                                <button type="button" class="btn btn--t-50 btn--primary w-100">Verstuur bericht</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-5 col-lg-4 col-3xl-3 ms-auto mb-30">
                <contact-form-aside class="h-100 d-flex flex-column justify-content-between"/>
            </div>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2429.448702098057!2d6.139365215806313!3d52.489116879808336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c87e7774ff02a7%3A0x7a37a6861fe9bc99!2sCreoServer.com!5e0!3m2!1sen!2sby!4v1651668293453!5m2!1sen!2sby"
                width="100%" height="490" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

</template>


<script>

import {mapActions} from "vuex";
import {getMessageError} from "../../utils";

import ContactFormAside from "./contactFormAside";

export default {
    name: "ContactForm",
    components: {ContactFormAside},
    data() {
        return {
            siteKey: process.env.MIX_RECAPTCHA_SITEKEY_V2,
            recaptchaToken: '',
            sendFormAfterCaptcha: false,
            name: '',
            email: '',
            phone: '',
            department: 1,
            recaptcha: '',
            text: '',
            type: 'contactForm',

            departments: [
                {label: 'Verkoop', value: 1},
                {label: 'Inkoop', value: 2},
                {label: 'Administratie', value: 3},
                {label: 'Technisch', value: 4},
            ]
        }
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        register(recaptchaToken) {
            this.recaptcha = recaptchaToken
            if (this.sendFormAfterCaptcha) {
                this.sendContactForm()
            }
            this.sendFormAfterCaptcha = false
        },
        onCaptchaExpired() {
            this.$refs.recaptcha.reset()
        },
        async startSend() {
            this.sendFormAfterCaptcha = true
            await this.$refs.recaptcha.execute()
        },
        sendContactForm() {
            this.GET_LOADING_FROM_REQUEST(true)

            axios.post(`${process.env.MIX_CREO_WORK_FLOW}/api/public/contact/form`, {
                name: this.name,
                email: this.email,
                phone: this.phone,
                department: this.department,
                recaptcha: this.recaptcha,
                text: this.text,
                type: 'contactForm',
                site: 'portal',
            }).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.$refs.recaptcha.reset()
                this.$root.$emit('popupMessages', 'Success send')
                this.clearForm();
            }).catch((e) => {
                this.$refs.recaptcha.reset()
                this.GET_LOADING_FROM_REQUEST(false)
                this.$root.$emit('popupMessages', getMessageError(e))
                console.log(e)
            })
        },
        clearForm() {
            this.name = ''
            this.email = ''
            this.phone = ''
            this.department = 1
            this.recaptcha = ''
            this.text = ''
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <el-form :model="form" :rules="rules" ref="form" class="contact-form">
        <div class="h4 fw-bold">Contact formulier</div>
        <el-form-item label="Uw naam" prop="name" class="elc-form-item elc-form-item--fw-bold">
            <el-input v-model="form.name" class="elc-input elc-input--t-40 elc-input--s-gray"></el-input>
        </el-form-item>
        <el-form-item label="Uw e-mail adres" prop="email" class="elc-form-item elc-form-item--fw-bold">
            <el-input v-model="form.email" class="elc-input elc-input--t-40 elc-input--s-gray"></el-input>
        </el-form-item>
        <el-form-item label="Uw telefoonummer" prop="phone" class="elc-form-item elc-form-item--fw-bold">
            <el-input v-model="form.phone" class="elc-input elc-input--t-40 elc-input--s-gray"></el-input>
        </el-form-item>
        <el-form-item label="Afdeling" class="elc-form-item elc-form-item--fw-bold">
            <el-select v-model="form.department" class="elc-select elc-select--t-40 elc-select--s-gray">
                <el-option v-for="(item,key) in departments"
                           :key="key"
                           :label="item.label"
                           :value="item.value">
                </el-option>
            </el-select>
        </el-form-item>
        <el-form-item label="Uw bericht / vraag" prop="text" class="elc-form-item elc-form-item--fw-bold">
            <el-input type="textarea"
                      :autosize="{ minRows: 10, maxRows: 20 }"
                      show-word-limit
                      minlength="30"
                      maxlength="600"
                      v-model="form.text"></el-input>
        </el-form-item>
        <div class="row">
            <div class="col-12 ms-auto text-end">
                <button type="button" class="btn btn--t-50 btn--s-primary w-100" @click="submitForm('form')">
                    Verstuur bericht
                </button>
            </div>
        </div>
    </el-form>
</template>

<script>
