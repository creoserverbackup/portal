<template>
    <ValidationProvider :name="inputGroupData.placeholder"
                        :rules="patternValidate">
        <div class="profile-form__field"
             slot-scope="{ errors }"
             :class="{error: errors[0] && !inFocus, success: inputGroupData.isValidate, 'm0': inputGroupData.noMargin}">
            <input class="profile-form__input"
                   :class="{required: inputGroupData.required}"
                   :name="inputGroupData.name"
                   :placeholder="inputGroupData.placeholder"
                   v-on:focusin="focusIn"
                   v-on:focusout="focusLost"
                   v-model="inputGroupData.value">
            <small class="profile-form__helper"
                   v-if="inputGroupData.helper && !(errors[0] && !inFocus)">{{ inputGroupData.helper }}</small>
            <!--            <p v-if="errors[0] && !inFocus" class="text-danger">{{ errors[0] }}</p>-->
        </div>
    </ValidationProvider>
</template>

<script>
import {validate} from 'vee-validate';
import {required, integer, min, max, regex, email} from 'vee-validate/dist/rules';
import {ValidationObserver, ValidationProvider, extend} from 'vee-validate';


extend('required', required);
extend('min', min);
extend('max', max);
extend('regex', regex);
extend('email', email);

export default {
    props: {
        inputGroupData: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            patternValidate: '',
            inFocus: false
        }
    },
    mounted() {
        this.patternValidate = this.generateValidatePattern();
    },
    methods: {
        generateValidatePattern() {
            let pattern = {};
            const validateRules = this.inputGroupData.validate;

            if (this.inputGroupData.required) {
                pattern.required = true;
            }

            if (this.inputGroupData.type === 'email') {
                pattern.email = true;
            }

            if (validateRules) {
                if (validateRules.minLength) {
                    pattern.min = validateRules.minLength
                }
                if (validateRules.maxLength) {
                    pattern.max = validateRules.maxLength;
                }
                if (validateRules.regEx) {
                    pattern.regex = new RegExp(validateRules.regEx);
                }
            }

            return pattern;
        },
        focusIn() {
            this.inputGroupData.isValidate = false;
            this.inFocus = true;
        },
        focusLost() {
            validate(this.inputGroupData.value, this.patternValidate).then(result => {
                this.inputGroupData.isValidate = result.valid &&  this.inputGroupData.value && this.inputGroupData.value.trim().length;
                this.inFocus = false;
            });
        }
    }
}
</script>
