<template>
    <div class="log-in-section">
        <div class="auth-intro">{{$t('authPageIntro')}}</div>
        <form action="/" method="POST" name="auth" class="auth-form">
            <div class="auth-form__field">
                <label class="auth-form__label" for="u-name">{{$t('authNameLabel')}}</label>
                <input type="text" class="auth-form__input" name="user-name" id="u-name">
            </div>

            <div class="auth-form__field">
                <label class="auth-form__label" for="u-password">{{$t('authPasswordLabel')}}</label>
                <div class="auth-form__field-wrap">
                    <input :type="isPasswordHidden ? 'password' : 'text'" class="auth-form__input auth-form__input--icon" name="user-password" id="u-password">
                    <span class="auth-form__field-icon" @click="switchPasswordVisibility">
                        <i class="icon-eye"></i>
                    </span>
                </div>
            </div>

            <div class="checkbox-label auth-form__checkbox">
                <input class="checkbox-label__input" type="checkbox"
                       name="privacy"
                       id="u-privacy">
                <label class="checkbox-label__main"
                       for="u-privacy">{{$t('authCheckboxPrivacy')}}</label>
            </div>

            <button type="submit" class="btn btn--primary auth-form__btn"><span>{{$t('authBtnText')}}</span></button>
        </form>

        <div class="auth-form__info">
            <p>Ben je de inloggegevens vergeten? Geen probleem, je kunt ze<router-link to="/restore">hier</router-link> opnieuw aanragen.</p>
            <small>Creo Portal v 1.4.3</small>
        </div>

        <aside class="alert-notification">
            <div class="alert-notification__heading">{{$t('alertNotificationHeading')}}</div>
            <p class="alert-notification__desc">{{$t('alertNotificationDesc')}}</p>
        </aside>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                isPasswordHidden: true
            }
        },
        methods: {
            switchPasswordVisibility() {
                this.isPasswordHidden = !this.isPasswordHidden;
            }
        }
    }
</script>
