<template>
    <div class="" v-bind:class="[!chatPage ? 'chat__livechat-modal-window' : 'h-100']"  v-if="showChatModal">
        <div class="chat" :class="{'h-100':chatPage}">
            <div class="chat__header">
                <div class="chat__header-nav" v-if="!chatPage">
                    <button class="btn text-white chat__header-nav-btn" @click="handleWindow">
                        <span class="chat__header-nav-handle-window">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="#fff"
                                      d="M472 48H40.335a24.027 24.027 0 00-24 24v384a24.027 24.027 0 0024 24H472a24.027 24.027 0 0024-24V72a24.027 24.027 0 00-24-24zm-8 32v71.981l-415.665-.491V80zM48.335 448V183.49l415.665.491V448z"/>
                            </svg>
                        </span>
                    </button>
                    <button class="btn btn-icon text-white" @click="handleCloseOpenChat"><span>&times;</span></button>
                </div>
                <div class="d-flex w-100">
                    <div class="px-1 py-1">
                        <img class="chat__header-avatar"
                             :src="getAvatar()"
                             alt="avatar"
                             width="65"
                             height="64">
                    </div>
                    <div class="d-flex flex-grow-1 justify-content-between flex-column ps-5 pt-2">
                        <div>
                            <div class="h6 text-white fw-bold d-inline-block chat__title"
                                 :class="{'chat__title--online':isOnline,'chat__title--offline':!isOnline}">
                                Live chat
                            </div>
                        </div>
                        <div>
                            <div class="chat__product-page-triangle"></div>
                            <div class="chat__product-page-main-header-div2-chat-with">
                                U chat momenteel met {{ this.consultReadyName }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <chat-block v-if="chatId != ''" :chatId="chatId"
                        :chatHistory="chatHistory"
                        :isChatStarter="isChatStarter"
                        :uidUser="uidUser"
                        :recipient="recipient"
                        :recipientOnline="recipientOnline"
                        :chatPage="chatPage"

                        :support="support"
                        @chatClose="chatClose"
                        :productPage="true"
            />

        </div>
    </div>
</template>

<script>
import {DateTime} from 'luxon'
import ChatSvg from "../chat/chatSvg";
import {mapActions, mapGetters} from "vuex";
import ChatBlock from "../chat/chatBlock";

export default {
    name: "chatPopup",
    props: {
        chatStarter: '',
        chatPage: '',
    },
    components: {
        ChatBlock,
        ChatSvg
    },
    data() {
        return {
            consultReady: null,
            consultReadyName: null,
            support: false,
            isChatClosed: false,
            chatId: '',
            uidUser: '',
            recipientOnline: '',
            avatar: '',
            isChatStarter: this.chatStarter,
            chatHistory: null,
            message: '',
            print: false,
            recipient: false,
            file: {
                name: 'user file',
                hasError: false
            }
        }
    },
    watch: {
        chatStarter: {
            deep: true,
            handler() {
                this.isChatStarter = this.chatStarter
            }
        },
        showChatModal: {
            deep: true,
            handler() {
                if (this.showChatModal && !this.$route.query.frame) {
                    this.getChatConsult()
                }
            }
        },
    },
    computed: {
        ...mapGetters([
            'GET_SHOW_CHAT_MODAL',
            'GET_CHAT_FIRST_MESSAGE',
        ]),
        showChatModal: function () {
            return this.GET_SHOW_CHAT_MODAL;
        },
        chatFirstMessage: function () {
            return this.GET_CHAT_FIRST_MESSAGE;
        },
        isOnline() {
            return (this.recipientOnline)
        }
    },
    methods: {
        ...mapActions([
            'SET_SHOW_CHAT_MODAL',
            'SET_CHAT_FIRST_MESSAGE',
        ]),
        handleWindow() {
            window.open(process.env.MIX_WEBSHOP_URL + "/accounts/#/chat", '_blank', 'width=900, height=700');
            this.handleCloseOpenChat()
        },
        handleCloseOpenChat() {
            this.SET_SHOW_CHAT_MODAL(false)
        },
        getAvatar() {
            return 'data:image/jpg;base64,' + this.avatar;
        },
        getChatConsult() {
            axios.post('/chat/live', {
                cause: 1,
                department: 1,
            }).then((response) => {
                this.consultReady = {
                    name: response.data.employee.name,
                    uid: response.data.employee.uid
                }

                this.consultReadyName = response.data.employee.name;
                this.chatId = response.data.chat.id
                this.uidUser = response.data.uid
                this.recipientOnline = response.data.online
                this.avatar = response.data.avatar;
                this.recipient = response.data.chat.recipient
                if (typeof response.data.messages !== undefined) {
                    this.chatHistory = response.data.messages
                }
                this.sendFullUrl()
            })
        },
        chatClose() {
            this.isChatStarter = false
            this.$emit('chatClose')
        },
        sendFullUrl() {
            if (!this.chatFirstMessage) {
                this.message = this.$route.fullPath;
                this.sendMessage(true);
                this.SET_CHAT_FIRST_MESSAGE(true)
            }
        },
        sendMessage(slug = false) {
            if (this.message.trim().length) {
                axios.post('/chat/message', {
                    message: this.message,
                    slug: slug,
                    time: DateTime.local().toSeconds(),
                    chatId: this.chatId,
                    support: this.support,
                })
                this.message = ''
            }
        },
    }
}
</script>
