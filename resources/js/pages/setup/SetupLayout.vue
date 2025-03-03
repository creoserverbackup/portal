<template>
    <div class="pt-5 pl-5 pr-5">
        <div class="wrapper mb-5">

            <div class="layout-svg t-r"></div>
            <div class="layout-svg l-b"></div>

            <div class="logo">
                <router-link to="/" class="logo__img-wrap">
                    <img class="logo__img img-responsive" src="/images/logo.png" alt="">
                </router-link>
            </div>

            <router-view />

        </div>
    </div>
</template>

<script>
    export default {

    }
</script>
