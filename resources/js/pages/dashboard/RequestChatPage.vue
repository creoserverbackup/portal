<template>
    <div class="offer" v-if="checkPermission">
        <ul class="offer__list">
            <li class="offer__item">Verzonden datum: <span>{{ date(request.time) }}</span> om
                <span>{{ time(request.time) }}</span></li>
            <li class="offer__item">Aanvraag optie: <span>{{ request.title }}</span></li>
            <li class="offer__item">Productijn: <span>{{ request.categoryName }}</span></li>
        </ul>
        <div class="support-center__reviews scroll" ref="scrolledContainer">
            <article class="support-center__review review" v-for="comment in comments">
                <header class="review__header">
                    <span class="review__author">{{ comment.username }}</span>
                    <time class="review__date" datetime="">{{ getDate(comment.time) }}</time>
                </header>
                <div class="review__body" :class="{support: comment.support}" v-html="comment.message"></div>
            </article>
        </div>

        <textarea v-model="message" cols="30" rows="14" @keyup.enter="submitMessage()"></textarea>

        <!--        <editor v-model.trim="message" @keyup.enter="submitMessage()"-->
        <!--                api-key="no-api-key"-->
        <!--                :init="{-->
        <!--                    height: 200,-->
        <!--                    menubar: false,-->
        <!--                    plugins: [-->
        <!--                        'autolink link image tinydrive',-->
        <!--                    ],-->
        <!--                    toolbar:-->
        <!--                        'fontselect | fontsizeselect | bold italic underline | forecolor |\-->
        <!--                        link ',-->
        <!--                    images_upload_url: 'postAcceptor.php',-->
        <!--                    //content_css: 'tinymce-iframe-night-mode.css'-->
        <!--                }"/>-->
        <button class="new-ticket__btn btn btn--secondary" type="button" v-on:click="submitMessage ()">{{
                $t('RequestOfferSendMessage')
            }}
        </button>
        <div class="text-center">
            <router-link to="/request">Terug naar Offerte Aanvragen</router-link>
        </div>
    </div>
    <div v-else>Toegang geweigerd</div>
</template>

<script>
import {mapActions} from 'vuex'
import {DateTime} from 'luxon'

export default {
    data() {
        return {
            request: {
                id: '',
                date: '',
                title: '',
                categoryName: '',
            },
            checkPermission: false,
            message: '',
            comments: '',
        }
    },
    mounted() {
        this.getOffer()
        this.GET_LOADING_FROM_REQUEST(false)
        this.checkingForNewMessages()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getOffer() {
            axios.get(`/request/request/${this.$route.params.id}`).then((response) => {
                if (typeof response.data.categoryName !== "undefined") {
                    this.checkPermission = true
                    this.request = response.data
                    this.getNewMessages()
                } else {
                    this.checkPermission = false
                }
            })
        },
        getDate(date) {
            return DateTime.fromSeconds(date).toFormat('dd-MM-yyyy ~hh:mm')
        },
        date(date) {
            if (typeof date == "undefined" || date == '') {
                return;
            }
            return DateTime.fromSeconds(date).toFormat('dd-MM-yyyy')
        },
        time(date) {
            if (typeof date == "undefined" || date == '') {
                return;
            }
            return DateTime.fromSeconds(date).toFormat('HH:mm')
        },
        checkingForNewMessages() {
            this.interval = setInterval(() => this.getNewMessages(), 10000)
        },
        getNewMessages() {
            axios.get(`/request/message/${this.request.id}`).then((response) => {
                if (Object.keys(response.data).length !== 0) {
                    this.comments = response.data
                }
            })
        },
        submitMessage() {
            if ((typeof this.message === 'undefined' || this.message === '')) {
                this.$root.$emit('popupMessages', 'Fill the message')
            } else {
                axios.post('/request/message', {
                    message: this.message,
                    requestId: this.request.id,
                }).then((response) => {
                    this.getNewMessages()
                    this.message = ''
                })
            }
        },
        nextTick() {
            const blockMessages = this.$refs.scrolledContainer;
            if (blockMessages != undefined) {
                blockMessages.scrollTop = blockMessages.scrollHeight;
            }
        },
    },
    watch: {
        comments: {
            handler() {
                setTimeout(this.nextTick, Math.random() * 500);
            }, deep: true
        },
    },
    destroyed() {
        clearInterval(this.interval)
    }
}
</script>
