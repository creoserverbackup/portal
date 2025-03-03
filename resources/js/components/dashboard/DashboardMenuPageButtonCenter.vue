<template>
    <nav class="dashboard__nav dashboard-nav">
        <ul class="dashboard-nav__list">
            <li class="dashboard-nav__item">
                <router-link to="/page/warranty-policy" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-shield"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarGuarantee') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/page/return-policy" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-return-box"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarReturn') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/page/right-withdrawal" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-rounded-check"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarRecallRights') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/page/algemene_voorwaarden" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-file"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarTerms') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/page/shipping-conditions" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-truck"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarDelivery') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/page/payment-terms" class="dashboard-nav__link">
<!--                    <img class="icon dashboard-nav__item-icon-payment-terms-full"-->
<!--                         v-bind:src="'/images/icon/payment-terms.svg'" alt="">-->

                        <svg
                                version="1.0"
                                width="18px"
                                height="18px"
                                viewBox="0 0 151.97525 101.97739"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:svg="http://www.w3.org/2000/svg">
                            <g
                                    transform="matrix(0.1,0,0,-0.1,-24,151)"
                                    fill="slategrey"
                                    stroke="none">
                                <path
                                        d="m 260,1488 c -19,-21 -20,-33 -20,-429 0,-386 1,-408 19,-430 l 19,-24 492,-3 493,-3 44,-39 c 89,-78 200,-91 304,-37 161,83 198,284 78,420 l -39,45 v 239 c 0,237 0,240 -23,261 l -23,22 H 942 281 Z m 1348,-20 c 7,-7 12,-34 12,-60 v -48 H 945 270 v 48 c 0,26 5,53 12,60 17,17 1309,17 1326,0 z m 7,-208 v -65 H 945 275 l -3,54 c -2,29 -1,60 2,67 5,12 114,14 673,12 l 668,-3 z m 3,-174 3,-79 -28,7 c -15,4 -58,10 -94,13 -53,4 -75,1 -117,-17 -58,-25 -115,-82 -143,-144 -21,-46 -25,-136 -8,-185 6,-18 9,-34 6,-37 -2,-3 -217,-5 -478,-4 -363,0 -475,3 -481,13 -9,14 -11,501 -2,510 4,3 306,5 673,4 l 666,-2 z m -23,-109 c 97,-49 153,-173 126,-274 -6,-23 -26,-61 -44,-85 -138,-181 -426,-86 -427,142 0,100 54,181 150,225 43,19 152,15 195,-8 z"/>
                                <path
                                        d="m 443,991 c -74,-46 -84,-147 -21,-202 37,-33 76,-43 119,-30 30,9 49,9 74,1 47,-16 106,2 139,41 20,24 26,41 26,80 0,42 -5,55 -34,87 -36,39 -34,38 -156,32 -14,-1 -45,1 -70,5 -35,5 -52,2 -77,-14 z m 98,-41 c -6,-45 -4,-115 3,-137 19,-52 -98,-31 -123,23 -32,71 19,147 94,141 26,-2 30,-6 26,-27 z m 187,-5 c 26,-31 30,-90 7,-122 -24,-35 -77,-48 -118,-28 -75,35 -76,132 -2,174 30,17 87,5 113,-24 z"/>
                                <path d="m 1520,785 -80,-79 -28,27 c -28,27 -52,35 -52,18 0,-13 72,-81 85,-81 6,0 48,38 95,85 64,65 82,87 73,96 -9,9 -31,-6 -93,-66 z"/>
                            </g>
                        </svg>
                    <span class="dashboard-nav__text pl-3">{{ $t('navbarPaymentTerms') }}</span>
                </router-link>
            </li>
            <li class="dashboard-nav__item">
                <router-link to="/page/privacy-policy" class="dashboard-nav__link">
                    <i class="dashboard-nav__icon icon-lock"></i>
                    <span class="dashboard-nav__text">{{ $t('navbarPrivacy') }}</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "DashboardMenuPageButtonCenter"
}
</script>

<style lang="scss">

.dashboard-nav__item-icon-payment-terms-full {
    height: 23px;
    width: 23px;
    margin-right: 5px;
}

</style>