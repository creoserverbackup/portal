<template>
    <div class="">
        <ticket-page-info :ticket="ticket" :timeLastMessage="timeLastMessage"/>
        <div class="d-inline-flex" v-if="ticket">
            <div class="mr-1" v-if="ticket.support">
                <button class="btn-closed btn btn--primary" type="button" v-on:click="setStatusTicket(2, ticket.id)">
                    {{ $t('setTicketInStatusOpen') }}
                </button>
                <button class="btn-closed btn btn--primary" type="button" v-on:click="setStatusTicket(3, ticket.id)">
                    {{ $t('setTicketInStatusWait') }}
                </button>
            </div>
            <button v-if="ticket.status != 4" class="btn-closed btn btn--primary" type="button"
                    v-on:click="setStatusTicket(4, ticket.id)">
                {{ $t('setTicketInStatusClosed') }}
            </button>
        </div>
        <!--        <div class="support-center__reviews scroll mb-4" ref="scrolledContainer">-->
        <!--            <article class="mb-2 review" v-for="message in messages">-->
        <!--                <header class="pb-1 fs-12 c-gray" :class="{ 'ta-r' : message.support}">-->
        <!--                    <span class="d-inline-block va-middle min-w-70px">{{ message.username }}</span>-->
        <!--                    <time class="d-inline-block va-middle" datetime="">{{ getDate(message.time) }}</time>-->
        <!--                </header>-->
        <!--                <div class="review__body" :class="{support: message.support}" v-html="message.message"></div>-->
        <!--            </article>-->
        <!--        </div>-->
        <div class="b-primary p-5">
            <div class="chat__messages-block chat__messages scroll h-450" ref="scrolledContainer">
                <div class="chat__message d-flex mt-4" v-for="message in messages"
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

                        <time class="chat__message-date">{{ getDate(message.time) }}
                            <i class="chat__message-status icon icon-check"
                               v-bind:class="[message.read ? 'c-g' : 'c-gray']"></i>
                        </time>
                    </div>
                </div>
            </div>
        </div>

        <div class="position-relative h-50px" v-if="ticket.status != 4">
            <div class="ticket-chat__send">
                <input type="file" class="upload-file__upload"
                       accept=".jpg, .png, .jpeg, .svg, .doc, .docx, .pdf, .txt, .zip, .rtf, .html, .rip, .xlsx, .xlsb"
                       v-on:change="onSelectFile" ref="file" id="chat-file" multiple>
                <label for="chat-file" class="btn btn--secondary chat__send-upload" ref="label">
                    <chat-clip/>
                </label>
                <input class="chat__send-input" ref="inputMessage" v-model.trim="message" type="text"
                       placeholder="Typ bericht…"
                       @keyup.enter="submitMessage">
                <button class="chat__send-btn btn btn--secondary" @click="submitMessage">
                    <chat-svg/>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'

import {DateTime} from 'luxon';
import ChatSvg from "../../components/chat/chatSvg";
import ChatClip from "../../components/chat/chatClip";
import TicketPageInfo from "./ticketPageInfo";
import {getMessageError} from "../../utils";

export default {
    name: "ticketPageMain",
    components: {
        TicketPageInfo,
        ChatClip,
        ChatSvg
    },
    data() {
        return {
            ticket: {
                messages: ''
            },
            messages: null,
            message: '',
            timeLastMessage: '',
            countMessages: 0,
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false)

        this.getTicketById()
        this.timeTicketMessages = setInterval(() => this.getTicketMessages(), 15000000)

        this.$root.$on('ticketUpdate', (data) => {
            if (data.data.value == this.ticket.id) {
                this.getTicketMessages()
            }
        })
    },
    methods: {
        ...mapActions([
            'SET_TICKET_UNREAD_MESSAGE',
        ]),
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getName(message) {
            if (this.support && message.support || !message.support && !this.support) {
                return 'U'
            } else {
                return message.username
            }
        },
        setStatusTicket(status, ticketId) {
            axios.post('/ticket/status', {
                ticketId: ticketId,
                status: status,
            }).then((response) => {
                this.ticket = response.data.ticket
                this.$root.$emit('popupMessages', 'Status changed')
            })
        },
        getTicketMessages() {

            if (this.ticket.id != undefined) {
                axios.get(`/ticket/message/${this.ticket.id}`).then((response) => {
                    if (response.data.ticket.length !== 0) {
                        this.messages = response.data.ticket
                        this.getLastComment()
                    }

                    this.SET_TICKET_UNREAD_MESSAGE(response.data.unread > 0 ? response.data.unread : 0)
                })
            }
        },
        getTicketById() {
            axios.get(`/ticket/page/${this.$route.params.id}`).then((response) => {
                this.ticket = response.data.ticket

                if (response.data.messages != undefined) {
                    this.messages = response.data.messages
                }
                this.getTicketMessages()
            })
        },
        submitMessage() {
            if ((typeof this.message === 'undefined' || this.message == '')) {
                this.$root.$emit('popupMessages', 'Fill the message')
            } else {
                axios.post('/ticket/message', {
                    message: this.message,
                    ticketId: this.ticket.id,
                }).then((response) => {
                    this.getTicketMessages()
                    this.message = ''
                })
            }
        },
        getDate(date) {
            if (date != undefined) {
                return DateTime.fromSeconds(date).toFormat('dd-MM-yyyy ~hh:mm')
            }
        },
        getLastComment() {
            this.nextTick()
            let lastUpdate = 0
            if (this.messages != undefined && this.messages.length > 0) {
                this.messages.filter(comment => {
                    lastUpdate = (comment.time > lastUpdate ? comment.time : lastUpdate)
                })
            }
            this.timeLastMessage = this.getDate(lastUpdate)
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
                    formData.append('ticketId', this.ticket.id)
                    axios.post('/ticket/files', formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }).then((response) => {
                    }).catch((e) => {
                        this.$root.$emit('popupMessages', getMessageError(e))
                    })
                }
            }
        },
        nextTick() {
            const blockMessages = this.$refs.scrolledContainer;

            setTimeout(() => {
                if (blockMessages != undefined) {
                    blockMessages.scrollTop = (blockMessages.scrollHeight - 50);
                }
            }, 1000)
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
        getImage(file) {
            window.open(file.url, '_blank', 'width=900, height=700');
        },
    },
    watch: {
        ticket: {
            handler() {
                if (this.countMessages < this.messages.length) {
                    this.countMessages = this.messages.length
                    setTimeout(this.nextTick, 1000);
                }
            }, deep: true
        }
    },
    destroyed() {
        clearInterval(this.timeTicketMessages)
        this.$root.$off('ticketUpdate')
    }
}
</script>


<style lang="scss">

.ticket-chat {
    &__send {
        position: absolute;
        left: 0;
        right: 0;
        bottom: .3rem;
    }
}

</style>