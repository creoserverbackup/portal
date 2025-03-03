<template>
    <div class="modal middle popup-chat-new" v-if="chat" v-bind:class="{ 'modal-open': chat !== ''}">
        <div class="modal-inner">
            <div class="modal-close" @click="chat = ''">
                <img src="/images/close.svg" alt="X">
            </div>
            <div class="modal-body">
                <div class="popup-chat-new__message">The administrator has created a chat with you</div>
                <div class="modal-form ml-3">
                    <button class="popup-chat-new__btn bg-red w-45  mr-4" @click="chat = '';">Ok</button>
                    <router-link :to="url">
                        <button class="popup-chat-new__btn bg-primary w-45">View chat</button>
                    </router-link>

                </div>
            </div>
        </div>
        <div class="bg-overlay" @click="chat = ''"></div>
    </div>
</template>

<script>
export default {
    name: "popupChatNew",
    data() {
        return {
            chat: '',
        }
    },
    mounted() {
        this.$root.$on('chatNew', (data) => {
            this.chat = data
        });
    },
    computed: {
        url() {
            if (this.chat != '') {
                return '/live-support-chat?chat=' + this.chat.id + '&uid=' + this.chat.uid
            } else {
                return ''
            }
        },
    }
}
</script>

<style scoped>

</style>

<style lang="scss">
@import "resources/sass/abstracts/variables";

.popup-chat-new {

    .modal-inner {
        border-radius: unset !important;
        min-width: 700px !important;
        padding: 20px !important;
    }

    .modal-body {
        padding: 10px 50px 40px;
    }

    &__message {
        font-size: 20px;
        text-align: center;
        color: $primary-color;
        padding: 20px;
    }

    &__btn {
        color: $white;
        border: 0;
        font-size: 15px;
        cursor: pointer;
        padding: 15px 32px 14px;
    }
}

</style>