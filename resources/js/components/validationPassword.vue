<template>
    <form method="POST" :action="items.urlForm" data-type="1111"
          name="auth" class="auth-form">
        <input type="hidden" name="_lang" value=""/>
        <input type="hidden" name="temporaryPassword" v-bind:value="items.temporaryPassword"/>
        <div class="auth-form__field">
            <label class="auth-form__label">{{ items.authNewPassword }}</label>
            <input type="password" class="auth-form__input" required name="passwordFirst" v-model="passwordFirst"
                   @input="changePassword()" :class="{ 'valid' : isConfirmPassword }">
        </div>
        <div class="auth-form__field">
            <label class="auth-form__label">{{ items.authNewPasswordConfirm }}</label>
            <input type="password" class="auth-form__input" required name="passwordSecond" v-model="passwordSecond"
                   :class="{ 'valid' : isConfirmPassword }" @input="changePasswordSecond()">
            <p class="auth-form__label" :class="{'validate': isPasswordLength}"> > {{ items.pswRequirementParam1 }}</p>
<!--            <p class="auth-form__label" :class="{'validate': isPasswordLetter}"> > {{ items.pswRequirementParam2 }}</p>-->
            <p class="auth-form__label" :class="{'validate': isPasswordUppercase}"> >
                {{ items.pswRequirementParam3 }}</p>
            <p class="auth-form__label" :class="{'validate': isPasswordNumber}"> > {{ items.pswRequirementParam4 }}</p>
        </div>
        <div v-if="items.errors" v-for="error in items.errors">
            <div class="auth-form__info-error-back">
                <p>{{ error }}</p>
            </div>
        </div>
        <button type="submit" class="btn btn--primary auth-form__btn"
                :disabled="!isPasswordNumber || !isPasswordUppercase || !isPasswordLength || !isConfirmPassword">
            <span>{{ items.authBtnReset }}</span></button>
    </form>
</template>

<script>
export default {
    name: "validationPassword",
    props: [
        'itemsstring',
    ],
    data: function () {
        return {
            isPasswordLength: false,
            isPasswordNumber: false,
            isPasswordLetter: false,
            isPasswordUppercase: false,
            isConfirmPassword: false,
            passwordFirst: '',
            passwordSecond: '',
        }
    },
    methods: {
        changePassword() {

            //check length password
            if (this.passwordFirst.length >= 8) {
                this.isPasswordLength = true;
            } else {
                this.isPasswordLength = false;
            }

            //check uppercase password
            if (this.passwordFirst.toLowerCase() != this.passwordFirst) {
                this.isPasswordUppercase = true;
            } else {
                this.isPasswordUppercase = false;
            }

            //check number password
            if (this.passwordFirst.match(/\d/g)?.length >= 1) {
                this.isPasswordNumber = true;
            } else {
                this.isPasswordNumber = false;
            }

            //check letter password
            // if ((/[a-z]/i.test(this.passwordFirst))) {
            //     this.isPasswordLetter = true;
            // } else {
            //     this.isPasswordLetter = false;
            // }

            if (this.passwordFirst == this.passwordSecond) {
                this.isConfirmPassword = true;
            } else {
                this.isConfirmPassword = false;
            }
        },
        changePasswordSecond() { //check passwordSecond == passwordFirst
            if (this.passwordFirst == this.passwordSecond) {
                this.isConfirmPassword = true;
            } else {
                this.isConfirmPassword = false;
            }
        }
    },
    computed: {
        items: function () {
            return JSON.parse(this.itemsstring);
        }
    }
}
</script>

<style scoped>

</style>
