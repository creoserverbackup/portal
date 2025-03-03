<template>
    <nav class="dashboard__nav dashboard-nav">
        <ul class="dashboard-nav__list">
            <li class="dashboard-nav__item">
                <router-link to="/page/our-data" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-star"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarAbout') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/request" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-scroll"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarOffer') }}</span>
                </router-link>
                <small class="dashboard-nav__notification" v-if="requestUnread">{{ requestUnread }}</small>
            </li>
            <li class="dashboard-nav__item">
<!--                <router-link to="/dedicated-hosting" class="dashboard-nav__link">-->
                <div class="dashboard-nav__link dashboard-nav__link-disable">
                    <i class="dashboard-nav__icon icon-setup"></i>

                    <span class="dashboard-nav__text">{{ $t('navbarDedicated') }}</span>
                </div>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/faq" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-question-mark"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarSupport') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/return-service" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-wrench"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarReturnService') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/rma" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-rotation"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarRMA') }}</span>
                </router-link>
            </li>

            <!--
                                <li class="dashboard-nav__item">
                                    <router-link to="/catalog?discount=true" class="dashboard-nav__link">
                                        <i class="dashboard-nav__icon icon-percent"></i>
                                        <span class="dashboard-nav__text">{{ $t('navbarPerCent') }}</span>
                                    </router-link>
                                </li>
                                <li class="dashboard-nav__item">
                                    <router-link to="/catalog?ready=true" class="dashboard-nav__link">
                                        <i class="dashboard-nav__icon icon-box"></i>
                                        <span class="dashboard-nav__text">{{ $t('navbarBox') }}</span>
                                    </router-link>
                                </li>
                                <li class="dashboard-nav__item">
                                    <router-link to="/catalog?configuration=true" class="dashboard-nav__link">
                                        <i class="dashboard-nav__icon icon-two-settings-cogwheels"></i>
                                        <span class="dashboard-nav__text">{{ $t('navbarCogs') }}</span>
                                    </router-link>
                                </li>
                                <li class="dashboard-nav__item">
                                    <a href="#" class="dashboard-nav__link">
                                        <i class="dashboard-nav__icon icon-euro"></i>
                                        <span class="dashboard-nav__text">{{ $t('navbarPayment') }}</span>
                                    </a>
                                </li>
                                <li class="dashboard-nav__item">
                                    <a href="#" class="dashboard-nav__link">
                                        <i class="dashboard-nav__icon icon-wrench"></i>
                                        <span class="dashboard-nav__text">{{ $t('navbarSettings') }}</span>
                                    </a>
                                </li> -->
        </ul>
    </nav>
</template>

<script>
export default {
    name: "DashboardMenuPageButtonTop",
    props: {
        requestUnread: {},
    },
}
</script>

<style scoped>

</style>