<template>
    <div class="restore-password">

        <p><strong>{{$t('authPageStrongNotification')}}</strong></p>

        <form action="/" method="POST" name="auth" class="auth-form">

            <div class="auth-form__field">
              <label class="auth-form__label" for="u-email">{{$t('authEmailLabel')}}</label>
              <input type="email" class="auth-form__input" name="user-email" id="u-email">
            </div>

            <button type="submit" class="btn btn--primary auth-form__btn"><span>{{$t('authBtnRestore')}}</span></button>

        </form>

        <div class="auth-form__info">
<!--          <p>Ben je de login gegevens vergeten? Geen probleem je kan ze <router-link to="/restore">HIER</router-link> opnieuw aanragen.</p>-->
          <small>Creo Portal v 1.4.3</small>
        </div>

    </div>
</template>

<script>
    export default {}
</script>
