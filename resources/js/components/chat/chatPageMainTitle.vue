<template>
    <div class="chat-heading mb-3">
        <div class="d-flex align-items-end">
            <img class="img-responsive" src="images/chat.png" alt="chat">
            <h2 class="m-0 ml-3 c-black f-s-3rem">{{ $t('DashboardChatLiveTitle') }}</h2>
        </div>
        <div class="d-flex flex-row justify-content-between align-items-end mt-5" v-if="isChatStarter">
            <div>
                <table class="chat-heading__info">
                    <tr>
                        <td>Aanleiding:</td>
                        <td>
                            <span class="c-sc">{{ cause }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Afdeling:</td>
                        <td>
                            <span class="c-sc">{{ department }}</span>
                        </td>
                    </tr>
                    <tr v-if="creoNum">
                        <td>Ordernr.:</td>
                        <td>
                            <span class="c-sc">{{ creoNum }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="chat-heading__info-right fs-16 d-flex align-items-end" v-if="isChatStarter">
                <div class="d-flex mr-4">
                    <div class="c-sc mr-2">Let op!</div>
                    <div class="">Uw chat sluit automatisch na 1 uur inactiviteit</div>
                </div>
                <div class="ml-5">
                    <span class="chat-heading__info-right-close c-primary" @click="chatClose">
                        {{ $t('DashboardChatClose') }}
                    </span>
                </div>
            </div>
        </div>
        <p class="mt-3 c-black" v-if="!isChatStarter"
           v-html="`${$t('DashboardChatLiveText1')} ${returnStatus} ${$t('DashboardChatLiveText2')}`"></p>
    </div>
</template>

<script>
export default {
    name: "chatPageMainTitle",
    props: {
        isChatStarter: '',
        consultReady: '',
        department: '',
        creoNum: '',
        cause: '',
    },
    data() {
        return {
            isOnline: false,
        }
    },
    mounted() {
        this.isOnlineChat()
    },
    methods: {
        isOnlineChat() {
            const today = new Date()
            let hours = today.getHours()
            let day = today.getDay()
            this.isOnline = day < 6 && hours > 8 && hours < 17
        },
        chatClose()
        {
            this.$emit('chatClose')
        }
    },
    computed: {
        returnStatus: function () {
            let html

            if (this.isOnline) {
                html = '<span class="c-green">'
                html += this.$t('DashboardChatLiveStatusOnline')
            } else {
                html = '<span class="c-red">'
                html += this.$t('DashboardChatLiveStatusOffline')
            }

            html += '</span>'

            return html
        }
    },
}
</script>

<style scoped>

</style>