<template>
    <div :class="{'h-100':chatPage}">
        <div class="chat__main p-3" :class="{'h-90':chatPage}">
            <div class="chat__messages-block chat__messages scroll" ref="scrolledContainer">
                <div class="chat__message d-flex mt-4" v-for="message in dataMessages"
                     v-bind:class="{ 'chat__message-is-support': message.support }">
                    <div class="chat__message-icon d-flex flex-column mt-3">
                        <i class="icon-man"></i>
                        <span class="fs-10 text-center chat__message-username">{{ getName(message) }}</span>
                    </div>
                    <div class="d-flex flex-column ml-3">
                        <template v-if="message.url != undefined && message.image">
                            <img class="chat__message-img" :src="message.url" @click="getImage(message)">
                        </template>
                        <template v-else-if="message.url != undefined">
                            <div class="chat__message-block">
                                <div class="chat__message-text chat__message-file" v-html="message.message"
                                     @click="getFile(message)"></div>
                            </div>
                        </template>
                        <div class="chat__message-block" v-else>
                            <div class="chat__message-text" v-html="message.message">
                            </div>
                        </div>

                        <time class="chat__message-date" datetime="">
                            {{ getDate(message.time) }}
                            <i class="chat__message-status icon icon-check"
                               v-bind:class="[message.read ? 'c-g' : 'c-gray']"></i>
                        </time>
                    </div>
                </div>
            </div>
            <template v-if="print">
                <div class="chat__messages">
                    <div class="chat__message d-flex mt-4"
                         v-bind:class="[!support ? 'chat__message-is-support' : '']">
                        <div class="chat__message-icon d-flex flex-column mt-3">
                            <i class="icon-man"></i>
                            <span class="fs-10 text-center chat__message-username">{{ getName({}, true) }}</span>
                        </div>
                        <div class="d-flex flex-column ml-3">
                            <div class="chat__message-block">
                                <div class="chat__message-text">. . .</div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div class="chat__send">
                <div class="upload-file__figcaption">
                    <input type="file" class="upload-file__upload"
                           accept=".jpg, .png, .jpeg, .svg, .doc, .docx, .pdf, .txt, .zip, .rtf, .html, .rip, .xlsx, .xlsb"
                           v-on:change="onSelectFile" ref="file" id="chat-file" multiple>
                    <label for="chat-file" class="btn btn--secondary chat__send-upload" ref="label">
                        <chat-clip/>
                    </label>
                </div>
                <input class="chat__send-input" ref="inputMessage" v-model="message" type="text"
                       placeholder="Typ bericht…"
                       @keyup.enter="startSendMessage"
                       v-on:input="setPrintsUser(true)"
                       v-on:blur="setPrintsUser(false)">
                <button class="chat__send-btn btn btn--secondary" @click="startSendMessage">
                    <chat-svg/>
                </button>
            </div>
        </div>
        <small class="upload-file__desc" ref="helper" v-html="$t('ChatFilePermission')"></small>
        <!--        <div class="chat__exit" v-if="!productPage">-->
        <!--            <button class="btn btn&#45;&#45;secondary" v-on:click="chatClose">{{ $t('DashboardChatClose') }}</button>-->
        <!--        </div>-->

        <vue-recaptcha
                ref="recaptcha"
                size="invisible"
                :loadRecaptchaScript="true"
                :sitekey="siteKey"
                @verify="register"
                @expired="onCaptchaExpired"
        />
    </div>
</template>

<script>

import {DateTime} from "luxon";
import ChatSvg from "./chatSvg";
import ChatClip from "./chatClip";
import VueRecaptcha from "vue-recaptcha";
import {getMessageError} from "../../utils";

export default {
    components: {
        ChatClip,
        ChatSvg,
        VueRecaptcha
    },
    props: {
        chatPage: false,
        chatHistory: {},
        isChatStarter: false,
        chatId: '',
        uidUser: '',
        support: '',
        recipientOnline: {},
        recipient: '',
        productPage: false,
    },
    data() {
        return {
            siteKey: process.env.MIX_RECAPTCHA_SITEKEY_V2,
            recaptchaToken: '',
            sendMessageAfterCaptcha: false,
            message: '',
            errorMessage: '',
            dataMessages: '',
            chatRecipientOnline: '',
            recipientInfo: '',
            countMessages: 0,
            print: false,
            printUser: false,
            timer: null
        }
    },
    mounted() {
        this.dataMessages = this.chatHistory
        this.chatRecipientOnline = this.recipientOnline
        this.getNewMessages()

        this.$root.$on('chatMessageEventPrint', (data) => {
            if (data.id != undefined && this.chatId == data.id && this.uidUser != data.uidPrint) {
                this.print = data.print
            }
        })

        this.$root.$on('chatMessageEvent', (data) => {
            if (data.value != undefined && this.chatId == data.value) {
                this.getNewMessages()
            }
        })

        this.timer = setInterval(() => {
            this.getNewMessages()
        }, 15000);
    },
    methods: {
        register(recaptchaToken) {
            this.recaptchaToken = recaptchaToken
            if (this.sendMessageAfterCaptcha) {
                this.sendMessage()
            }
            this.sendMessageAfterCaptcha = false
        },
        onCaptchaExpired() {
            this.$refs.recaptcha.reset()
        },
        async startSendMessage() {
            await this.sendMessage()
            // this.sendMessageAfterCaptcha = true
            // await this.$refs.recaptcha.execute()
        },

        setPrintsUser(print = false) {
            if (this.printUser != print) {
                this.printUser = print
                axios.post('/event/chat/print', {
                    chatId: this.chatId,
                    uidPrint: this.uidUser,
                    print: print,
                })
            }
        },
        getName(message, print = false) {

            if (!print && (this.support && message.support || !message.support && !this.support)) {
                return 'U'
            } else {
                return this.recipientInfo !== '' && this.recipientInfo != undefined &&
                this.recipientInfo.username != undefined ? this.recipientInfo.username : ''
            }
        },
        getNewMessages() {
            if (this.chatId != '') {
                axios.get(`/chat/message?chatId=${this.chatId}&recipient=${this.recipient}&uid=${this.uidUser}`)
                        .then((response) => {
                            if (Object.keys(response.data).length !== 0) {
                                this.dataMessages = response.data.messages
                                this.chatRecipientOnline = response.data.online
                                this.recipientInfo = response.data.recipientInfo
                                if (response.data.status == 6) {
                                    this.chatClose()
                                }
                                if (this.chatId != '' && this.countMessages < this.dataMessages.length) {
                                    this.countMessages = this.dataMessages.length
                                    setTimeout(this.nextTick, Math.random() * 1500);
                                }

                                // if (!response.data.checkCaptcha && this.$refs.recaptcha) {
                                //     this.$refs.recaptcha.reset()
                                // }
                            }
                        })
            }
        },

        async sendMessage() {
            await this.setPrintsUser(false)
            await this.send()
        },
        send() {
            this.errorMessage = ''
            if (this.message.trim().length) {
                axios.post('/chat/message', {
                    message: this.message,
                    time: DateTime.local().toSeconds(),
                    chatId: this.chatId,
                    support: this.support,
                    recaptcha: this.recaptchaToken,
                    site: 'portal',
                }).then((response) => {
                    this.$refs.inputMessage.focus()
                    this.$refs.recaptcha.reset()
                    this.getNewMessages()
                })
                this.message = ''
            }
        },
        getDate(date) {
            return DateTime.fromSeconds(date).toFormat('hh:mm')
        },
        onSelectFile() {
            this.errorMessage = ''
            const input = this.$refs['file']
            if (input.files && input.files.length > 0) {
                let formData = new FormData()
                for (let i = 0; i < input.files.length; i++) {
                    let file = input.files[i]

                    if (file.size > 200097152) {
                        this.$root.$emit('popupMessages', "The file size is more than 200mb")
                        this.errorMessage = "The file size is more than 200mb"
                        this.$refs['file'].files = null
                    } else {
                        formData.append('files[' + i + ']', file)
                    }
                }

                if (this.errorMessage == '') {
                    formData.append('chatId', this.chatId)
                    axios.post('/chat/files', formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }).then((response) => {
                        this.getNewMessages()
                    }).catch((e) => {
                        this.$root.$emit('popupMessages', getMessageError(e))
                    })
                }
            }
        },
        getImage(file) {
            window.open(file.url, '_blank', 'width=900, height=700');
        },
        getFile(file) {
            axios.post('/chat/file/info', {file: file},
                    {
                        responseType: 'blob'
                    }).then((response) => {

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                const fileName = file.file_name;

                link.href = url;
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })
        },
        chatClose() {
            this.$emit('chatClose')
        },
        nextTick() {
            const blockMessages = this.$refs.scrolledContainer;

            if (blockMessages != undefined) {
                blockMessages.scrollTop = blockMessages.scrollHeight;
            }
        },
    },

    watch: {
        dataMessages: {
            handler() {
                // setTimeout(this.nextTick, Math.random() * 500);
            }, deep: true
        },
        print: {
            handler() {
                // setTimeout(this.nextTick, Math.random() * 500);
            }, deep: true
        },
    },

    destroyed() {
        clearInterval(this.timer);
        this.$root.$off('chatMessageEventPrint')
        this.$root.$off('chatMessageEvent')
    }
}
</script>
