<template>
    <div class="chat">

        <chat-page-main-title :isChatStarter="isChatStarter"
                              :department="department"
                              :creoNum="creoNum"
                              :cause="cause"
                              :consultReady="consultReady"
                              @chatClose="chatClose"
        />

        <div v-if="support">
            <chat-for-staff @startChatWithUser="startChatWithUser"/>
        </div>
        <div v-if="!isChatStarter && !isChatClosed && !support">
            <chat-page-form v-if="!consultReady" @setChat="setChat"/>
            <div v-if="consultReady && openWindowStartChat" class="communication-start">
                <p>U heeft een inkomende chat van</p>
                <p><strong>{{ consultReady.name }}</strong></p>
                <p>Wilt u deze beantwoorden?</p>
                <button class="btn btn--secondary" v-on:click="letStartChat">Start chat</button>
                <button class="btn btn--secondary" v-on:click="chatClose">Close</button>
            </div>
        </div>
        <chat-page-table v-if="isTableMessages" :chats="chats"
                         @chatClose="chatClose"
                         @startChatWithUser="startChatWithUser"
                         @getTableUnreadMessages="getTableUnreadMessages"
        />

        <chat-block v-if="isChatStarter"
                    :chatHistory="chatHistory"
                    :chatId="chatId"
                    :uidUser="uidUser"
                    :recipient="recipient"
                    :recipientOnline="recipientOnline"
                    :support="support"
                    @chatClose="chatClose"/>

        <chat-page-close v-if="isChatClosed" />
    </div>
</template>

<script>
import ChatBlock from "./chatBlock";
import ChatPageMainTitle from "./chatPageMainTitle";
import {mapActions} from 'vuex';
import ChatPageForm from "./chatPageForm";
import ChatForStaff from "./chatForStaff";
import {getMessageError} from "../../utils";
import ChatPageTable from "./chatPageTable";
import ChatPageClose from "./chatPageClose";

export default {
    name: "chatPageMain",
    components: {
        ChatPageClose,
        ChatPageTable,
        ChatForStaff,
        ChatPageForm,
        ChatPageMainTitle,
        ChatBlock,
    },
    data() {
        return {
            uidUser: '',
            error: '',
            isTableMessages: false,
            chats: [],
            cause: '',
            department: '',
            creoNum: '',
            openWindowStartChat: false,
            support: false,
            consultReady: null,
            isChatStarter: false,
            isChatClosed: false,
            chatId: '',
            chatHistory: null,
            recipientOnline: {},
            recipient: '',
        }
    },
    mounted() {
        this.$root.$emit('chatNew', '')
        this.GET_LOADING_FROM_REQUEST(false);
        this.uidUser = this.userId()

        this.checkEntryByMail()
        this.supportCustomer()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),

        getTableUnreadMessages() {
            axios.get('/chat/messages/table').then((response) => {
                if (response.data.length > 0) {
                    this.isTableMessages = true
                }
                    this.chats = response.data
            })
        },
        getCause(cause) {
            let arr = new Map([
                [1, 'Product vraag'],
                [2, 'Prijs opvragen'],
                [3, 'Factuur vraag'],
                [4, 'Technische vraag'],
                [5, 'Voorraad vraag'],
                [6, 'Transport vraag'],
                [7, 'Anders'],
            ])

            return arr.get(cause)
        },
        getDepartment(department) {
            let arr = new Map([
                [1, 'Sales'],
                [2, 'Inkoop'],
                [3, 'Technische dienst'],
                [4, 'Financiële administratie'],
                [5, 'RMA & Ticket support'],
                [6, 'Logistiek'],
            ])

            return arr.get(department)
        },
        startChatWithUser(chatId, recipient, cause) {
            this.cause = this.getCause(cause)
            this.recipient = recipient

            axios.get(`/chat/consultation?chatId=${chatId}&recipient=${recipient}`).then((response) => {
                let data = response.data

                this.consultReady = {
                    name: data.user.name,
                    uid: data.user.uid
                }
                this.chatId = chatId
                if (this.support) {
                    this.openWindowStartChat = true
                } else {
                    this.letStartChat()
                }

                this.support = false
                if (typeof data.messages !== undefined) {
                    this.chatHistory = data.messages
                }
                if (typeof data.online != undefined) {
                    this.recipientOnline = data.online
                }
                this.isTableMessages = false
                this.creoNum = data.creoNum
                this.cause = this.getCause(data.chat.cause)
                this.department = this.getDepartment(data.chat.department)
            }).catch((e) => {
                this.$root.$emit('popupMessages', getMessageError(e))
                console.log(e)
            })
        },
        checkEntryByMail() {
            let chatId = this.$route.query.chat,
                    uid = this.$route.query.uid
            if (chatId > 0 && uid > 0) {
                this.isTableMessages = false
                this.startChatWithUser(chatId, uid, 7)
            } else {
                this.getTableUnreadMessages()
            }
        },
        supportCustomer() {
            axios.get('/customer/support').then((response) => {
                if (Object.keys(response.data).length !== 0) {
                    this.support = true
                }
            })
        },
        setChat(data) {
            this.consultReady = {
                name: data.employee.name,
                uid: data.employee.uid
            }
            this.chatId = data.chat.id

            if (typeof data.messages !== undefined) {
                this.chatHistory = data.messages
            }
            this.recipient = data.recipient
            this.creoNum = data.creoNum

            this.cause = this.getCause(data.chat.cause)
            this.department = this.getDepartment(data.chat.department)

            this.isTableMessages = false
            this.letStartChat()
        },
        letStartChat() {
            this.isChatStarter = true
            this.openWindowStartChat = false
        },
        chatClose(chatId = '') {
            let chatIdTemp = chatId
            if (chatId == '') {
                chatIdTemp = this.chatId
            }

            axios.delete(`/chat/live/${chatIdTemp}`).then((response) => {
                this.chatId = ''
                this.consultReady = null
                this.isChatStarter = false
                this.chatHistory = null

                if (chatId == '') {
                    this.isChatClosed = true
                }
                this.getTableUnreadMessages()
            }).catch((errors) => {
                console.log(errors)
            })

        },
    },
}
</script>
