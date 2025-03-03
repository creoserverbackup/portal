<template>
    <div class="auth-main">
        <div class="row align-items-center">
            <AuthBackground/>
            <div class="col-md-5">
                <div class="inner-logo auth-main__logo d-none d-md-block">
                    <router-link class="inner-logo__img-wrap" to="/">
                        <img class="inner-logo__img img-responsive" src="images/logo.png" alt="logotype">
                    </router-link>
                </div>
                <router-view/>
            </div>

            <div class="col-md-7 order-first order-md-last">
                <div class="inner-logo auth-main__logo d-md-none">
                    <router-link class="inner-logo__img-wrap" to="/">
                        <img class="inner-logo__img img-responsive" src="/images/logo.png" alt="logotype">
                    </router-link>
                </div>
                <div class="auth-promo-desc" :class="{'switched': switchedText}">{{ returnText(selectedText) }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                switchedText: false,
                selectedText: 1,
                textCounter: 17
            }
        },
        methods: {
            returnText(i) {
                return this.$t('authPagePromoText' + i);
            }
        },
        mounted() {
            setInterval(() => {
                let i = this.selectedText + 1;

                if (i > this.textCounter) i = 1;

                this.switchedText = true;

                setTimeout(() => {
                    this.selectedText = i;
                    this.switchedText = false;
                }, 500);

            }, 2500);
        },
    }
</script>
