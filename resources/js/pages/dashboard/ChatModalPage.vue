<template>
    <div class="chat-page">
        <chat-popup :chatPage="true"/>
    </div>
</template>

<script>
import {mapActions} from "vuex";
import ChatPopup from "../../components/chat/chatPopup";

export default {
    name: "ChatModalPage",
    components: {
        ChatPopup,
    },
    mounted() {
        this.showLiveChat()
    },
    methods: {
        ...mapActions([
            'SET_SHOW_CHAT_MODAL'
        ]),
        showLiveChat() {
            this.SET_SHOW_CHAT_MODAL(true)
        },
    }
}
</script>

<style scoped>

</style>