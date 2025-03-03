<template>
    <div class="order-center">
        <header class="order-center__header">
            <h1>
                {{ $t('orderCenterHeading') }}
                <small class="support-center__heading-desc">{{ $t('orderCenterSubHeading') }}</small>
            </h1>
            <router-link to="/interactive-center" class="order-center__header-btn btn btn--secondary">
                {{ $t('orderCenterHeadingBtn') }}
            </router-link>
        </header>

        <div class="nav-sections nav-sections--order-nav row p-0 m-0 mb-3" v-if="countProforma">
            <div class="nav-sections-col m-0 p-0">
                <router-link to="/order-center-accept" class="nav-sections__order-center-accept">
                    <span class="nav-section">
                        <figure class="nav-section__main">
                            <span class="nav-section__notification" v-if="countProforma">{{ countProforma }}</span>
                            <div class="nav-section__img-wrap">
                                <i class="nav-section__img icon-project-offerte"></i>
                            </div>
                            <figcaption class="nav-section__caption">{{ $t('navOrderCenterAccept') }}</figcaption>
                        </figure>
                    </span>
                </router-link>
            </div>
        </div>
        <div class="nav-sections nav-sections--order-nav row p-0 m-0 mb-3" v-else>
            <div class="nav-sections-col m-0 p-0">
                <div class="nav-sections__order-center-accept">
                    <span class="nav-section">
                        <figure class="nav-section__main">
                            <div class="nav-section__img-wrap">
                                <i class="nav-section__img icon-project-offerte"></i>
                            </div>
                            <figcaption class="nav-section__caption">{{ $t('navOrderCenterAccept') }}</figcaption>
                        </figure>
                    </span>
                </div>
            </div>
        </div>
        <div class="order-center-table">
            <router-view/>
        </div>
    </div>
</template>


<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    data() {
        return {}
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false);
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ])
    },
    computed: {
        ...mapGetters([
            'GET_COUNT_PROFORMA'
        ]),
        countProforma() {
            return this.GET_COUNT_PROFORMA;
        },
    },

}
</script>
