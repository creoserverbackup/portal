<template>
    <div>
        <div class="nav-sections">
            <div class="nav-sections-col">
                <router-link to="/live-support-chat" class="nav-section">
                    <figure class="nav-section__main">
                        <span class="nav-section__notification" v-if="chatUnreadMessage">{{
                                chatUnreadMessage
                            }}</span>
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-chat"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionChat') }}</figcaption>
                    </figure>
                </router-link>
            </div>
            <div class="nav-sections-col">
                <router-link to="/support-center" class="nav-section">
                    <figure class="nav-section__main">
                        <span class="nav-section__notification" v-if="ticketUnreadMessage">{{
                                ticketUnreadMessage
                            }}</span>
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-validating-ticket"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionTickets') }}</figcaption>
                    </figure>
                </router-link>
            </div>
            <div class="nav-sections-col">
                <router-link to="/faq" class="nav-section">
                    <figure class="nav-section__main">
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-question"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionFAQ') }}</figcaption>
                    </figure>
                </router-link>
            </div>
            <div class="nav-sections-col">
                <router-link to="/page/our-data" class="nav-section">
                    <figure class="nav-section__main">
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-settings"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionData') }}</figcaption>
                    </figure>
                </router-link>
            </div>
            <div class="nav-sections-col">
                <router-link to="/download-center" class="nav-section">
                    <figure class="nav-section__main">
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-download"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionDownload') }}</figcaption>
                    </figure>
                </router-link>
            </div>
            <div class="nav-sections-col">
                <div class="nav-section nav-section__archive">
                    <figure class="nav-section__main">
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-setup"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionTech') }}</figcaption>
                    </figure>
                </div>
            </div>
            <div class="nav-sections-col">
                <router-link to="/order-centrum" class="nav-section">
                    <figure class="nav-section__main">

                        <span class="nav-section__notification" v-if="countProforma">{{ countProforma }}</span>

                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-shopping-cart"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionOrder') }}</figcaption>
                    </figure>
                </router-link>
            </div>
            <div class="nav-sections-col">
                <div class="nav-section nav-section__archive">
                    <figure class="nav-section__main">
                        <div class="nav-section__img-wrap">
                            <i class="nav-section__img icon-box"></i>
                        </div>
                        <figcaption class="nav-section__caption">{{ $t('navSectionArchives') }}</figcaption>
                        <span>{{ $t('navSectionArchivesSub') }}</span>
                    </figure>
                </div>
            </div>
        </div>
        <support-list-section />
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import SupportListSection from "../../components/interactive-center/supportListSection";

export default {
    name: "InteractiveCenter",
    components: {
        SupportListSection
    },
    data() {
        return {
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false)
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
    },
    computed: {
        ...mapGetters([
            'GET_CHAT_UNREAD_MESSAGE',
            'GET_TICKET_UNREAD_MESSAGE',
            'GET_COUNT_PROFORMA',
        ]),
        chatUnreadMessage: function () {
            return this.GET_CHAT_UNREAD_MESSAGE;
        },
        ticketUnreadMessage: function () {
            return this.GET_TICKET_UNREAD_MESSAGE;
        },
        countProforma: function () {
            return this.GET_COUNT_PROFORMA;
        },
    },
}
</script>
