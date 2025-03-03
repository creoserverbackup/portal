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
            <select class="profile-form__select form-select required h-30px l-h-normal" v-model="form.department">
                <option v-for="item in departments" :value="item.value" :selected="item.value">{{ item.label }}</option>
            </select>
        </el-form-item>
        <el-form-item label="Uw bericht / vraag" prop="text" class="elc-form-item elc-form-item--fw-bold mb-20">
            <el-input type="textarea"
                      :autosize="{ minRows: 10, maxRows: 20 }"
                      show-word-limit
                      minlength="30"
                      maxlength="600"
                      v-model="form.text"></el-input>
        </el-form-item>
        <div class="row">
            <div class="col-12 ms-auto text-end">
                <button type="button" class="btn btn--t-50 btn--primary w-100" @click="submitForm('form')">
                    Verstuur bericht
                </button>
            </div>
        </div>
    </el-form>
</template>

<script>
import {getMessageError} from "../../utils";
import {mapActions} from "vuex";

export default {
    name: "contactFormForm",
    data() {
        return {
            form: {
                name: '',
                email: '',
                phone: '',
                department: 1,
                recaptcha: '',
                text: '',
                type: 'contactForm',
            },
            rules: {
                name: [
                    {required: true, message: this.$i18n.t('components.contactForm.textRequiredName'), trigger: 'blur'},
                    {min: 2, max: 60, message: this.$i18n.t('components.contactForm.textLengthName'), trigger: 'blur'}
                ],
                email: [
                    {
                        type: 'email',
                        required: true,
                        message: this.$i18n.t('components.contactForm.textRequiredEmail'),
                        trigger: 'blur'
                    }
                ],
                phone: [
                    {
                        required: true,
                        message: this.$i18n.t('components.contactForm.textRequiredPhone'),
                        trigger: 'blur'
                    },
                    {
                        min: 8,
                        max: 50,
                        message: this.$i18n.t('components.contactForm.textLengthPhone'),
                        trigger: 'blur'
                    },
                    {
                        pattern: /(^[0-9();\s+\- ]+$)+/,
                        message: this.$i18n.t('components.contactForm.textRegexPhone'),
                        trigger: 'blur'
                    }
                ],
                text: [
                    {
                        required: true,
                        message: this.$i18n.t('components.contactForm.textRequiredDescription'),
                        trigger: 'blur'
                    },
                    {
                        min: 30,
                        max: 600,
                        message: this.$i18n.t('components.contactForm.textLengthDescription'),
                        trigger: 'blur'
                    }
                ],
            },
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
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {


                    this.GET_LOADING_FROM_REQUEST(true)

                    axios.post(`${process.env.MIX_CREO_WORK_FLOW}/api/public/contact/form`, {
                        name: this.form.name,
                        email: this.form.email,
                        phone: this.form.phone,
                        department: this.form.department,
                        text: this.form.text,
                        type: 'contactForm',
                        site: 'portal',
                    }).then((response) => {
                        this.GET_LOADING_FROM_REQUEST(false)

                        this.$root.$emit('popupMessages', 'Success send')
                        this.clearForm();
                    }).catch((e) => {
                        this.GET_LOADING_FROM_REQUEST(false)
                        this.$root.$emit('popupMessages', getMessageError(e))
                        console.log(e)
                    })
                } else {
                    console.log('error submit!!!');
                    return false;
                }
            });
        },
        clearForm() {
            this.form.name = ''
            this.form.email = ''
            this.form.phone = ''
            this.form.department = 1
            this.form.text = ''
        }

    }
}
</script>

<style scoped>

</style>