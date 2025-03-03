<template>
    <div class="popup-message" v-if="messages.length > 0" v-bind:class="{ 'popup-message__open': messages.length > 0}">
        <div class="popup-message__inner">
            <div class="popup-message__close" @click="closeMessage">
                <img src="/images/close.svg" alt="X">
            </div>
            <div class="popup-message__body scroll">
                <div class="popup-message__body-message" v-for="message in messages">{{ message }}</div>
            </div>
            <div class="popup-message__submit">
                <input type="submit" @click="closeMessage" value="Ok">
            </div>
        </div>
        <div class="popup-message__background"></div>
    </div>
</template>

<script>
export default {
    name: "popupMessage",
    data() {
        return {
            messages: [],
        }
    },
    mounted() {
        this.$root.$on('popupMessages', (data) => {
            this.messages.push(data)
        })
    },
    methods: {
        closeMessage() {
            this.messages = []
        },
    },
}
</script>

<style scoped>

</style>