<template>
    <div>
        <div class="c-gray mb-5">
            <p>{{ $t('chatSummaryDesc') }}</p>
            <small class="mb-5 ta-r d-block">CreoServer</small>
            <div class="text-center">
                <router-link to="/interactive-center">{{ $t('chatSummaryGoTo') }}</router-link>
            </div>
        </div>
        <a href="https://www.google.com/maps/place/CreoServer.com/@52.4891201,6.1393652,17z/data=!3m1!4b1!4m15!1m7!3m6!1s0x47c87e7774ff02a7:0x7a37a6861fe9bc99!2sCreoServer.com!8m2!3d52.4891169!4d6.1415539!10e1!3m6!1s0x47c87e7774ff02a7:0x7a37a6861fe9bc99!8m2!3d52.4891169!4d6.1415539!9m1!1b1"
           target="a_blank">
            <img class="d-block ml-auto mr-auto" src="/images/google-review-logo-white.png" width="220" alt="google-review-logo">
        </a>
    </div>
</template>

<script>
export default {
    name: "chatPageClose"
}
</script>