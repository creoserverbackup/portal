import {mapActions, mapGetters} from "vuex";

require('./bootstrap');

////////// VUE AND ITs DEPENDENCIES \\\\\\\\\\\\\
// Vue
import Vue from 'vue'

// Vuex
import {store} from './vuex/store'

// Router
import {router} from './plugins/vue-router'

// i18n
import {i18n} from './plugins/i18n';

// Vuedals
import {default as Vuedals} from 'vuedals';

Vue.use(Vuedals);


import VueCookies from 'vue-cookies'
// default options config: { expires: '1d', path: '/', domain: '', secure: '', sameSite: 'Lax' }
Vue.use(VueCookies, {expire: '7d'})

////////// COMPONENTS \\\\\\\\\\\\\

Vue.component('suggestions', require('v-suggestions').default);
Vue.component('ValidationProvider', require('vee-validate').ValidationProvider);
Vue.component('Vuedal', require('vuedals').Component);
Vue.component('editor', require('@tinymce/tinymce-vue').default);
Vue.component('VueHtml2pdf', require('vue-html2pdf').VueHtml2pdf);
Vue.component('simplebar', require('simplebar-vue').default);
Vue.component('unsplash', require('./components/Unsplash').default);
// Vue.component('recaptcha',                  require('./components/ReCaptcha').default);
Vue.component('selectBackground', require('./components/SelectBackground').default);
Vue.component('ScreenLoader', require('./components/ScreenLoader').default);
Vue.component('Application', require('./pages/MainLayout').default);
// Vue.component('CookiePopup',                require('./components/popup/CookiePopup').default);


////////////////////// REDIRECT FROM ROOT TO THE CATALOG \\\\\\\\\\\\\\\\\\\\\\\\
// history.pushState = ( f => function pushState(){
//     let ret = f.apply(this, arguments);
//     window.dispatchEvent(new Event('pushstate'));
//     window.dispatchEvent(new Event('locationchange'));
//     return ret;
// })(history.pushState);
//
// history.replaceState = ( f => function replaceState(){
//     let ret = f.apply(this, arguments);
//     window.dispatchEvent(new Event('replacestate'));
//     window.dispatchEvent(new Event('locationchange'));
//     return ret;
// })(history.replaceState);
//
// window.addEventListener('popstate',()=>{
//     window.dispatchEvent(new Event('locationchange'))
// });
//
// window.addEventListener('locationchange', ()=>{
//     if(location.hash === '#/')
//         location.replace('#/catalog');
// })
//
// if(location.hash === '#/')
//     location.replace('#/catalog');


////////// MOUNT POINT \\\\\\\\\\\\\
import SoundNotification from "./soundNotification";
import {default as TypeAdminEvent} from "./data/typeAdminEvent";
const app = new Vue({
    el: '#workflow',
    store,
    router,
    i18n,
    data() {
        return {
            chatsIds: [],
            ticketIds: [],
            soundNotification: ''
        }
    },
    mounted() {
            this.soundNotification = new SoundNotification('pick.wav')
            this.soundNotification.init()

        Echo.channel('user').listen('UserDataEvent', (data) => {

            console.log(" UserDataEvent UserDataEvent UserDataEvent ")
            console.log(data)

            if ((data.type === TypeAdminEvent.TICKET_UPDATE || data.type === TypeAdminEvent.TICKET_NEW) && this.ticketIds.includes(data.data.value)) {
                this.$root.$emit('ticketUpdate', data)
                this.getTicketUnreadMessagesCount()
            }

            if ((data.type === TypeAdminEvent.CHAT_MESSAGE_NEW || data.type === TypeAdminEvent.CHAT_MESSAGE_NEW_ADMIN) && this.chatsIds.includes(data.data.value)) {
                this.$root.$emit('chatMessageEvent', data.data)
                this.getChatUnreadMessagesCount()
            }

            if (data.type === TypeAdminEvent.CHAT_MESSAGE_PRINT && this.chatsIds.includes(data.data.id)) {
                this.$root.$emit('chatMessageEventPrint', data.data)
            }

            if (data.type === TypeAdminEvent.CHAT_NEW && (this.userId() == data.data.uid || this.userId() == data.data.recipient)) {
                this.$root.$emit('chatNew', data.data)
                this.reconnectingChat()
            }

        })


        Echo.channel('uid-' + this.userId()).listen('NewLifeLineCustomer', (data) => {
            this.$root.$emit('NewLifeLineCustomer')
            this.recalculateMessage()
            this.reconnectingChat()

            console.log(" data NewLifeLineCustomer type ")
            console.log(data)

            if (data.type == TypeAdminEvent.TICKET_NEW) {
                this.getListenerTicket()
            }

            if (data.data?.sound != undefined) {
                this.sendNotification(data.data)
            }

        }).listen('ChangeStatusOrderUser', (data) => {
            this.setLastOrder()
            this.$root.$emit('ChangeStatusOrderUser', data)
        })

        Echo.channel('order').listen('ChangeQuantityProduct', (data) => {
            console.log("ChangeQuantityProduct")
            console.log(data)

            // this.cart.orders.forEach((product) => {
            //     if (product.ProductId == data.prodId && product.quantity > data.quantity) {
            //         setTimeout(this.setCart, Math.random() * 1500)
            //     }
            //
            //     if (product.config != undefined && product.config.length > 0) {
            //         product.config.forEach((config) => {
            //             if (config.prodId == data.prodId && config.quantity * product.quantity > data.quantity) {
            //                 setTimeout(this.setCart, Math.random() * 1500)
            //             }
            //
            //         })
            //     }
            // });
            // this.$root.$emit('changeQuantityProduct', data)
        })

        Echo.channel('uid-' + this.userId()).listen('UpdateOrderUser', (data) => {
            this.setCart()
            this.getOfferCount()
            this.$root.$emit('getProductsInCart')
            this.$root.$emit('updateOrdersOld')
            setTimeout(() => {
                this.$root.$emit('UpdateOrderUser', data)
            }, 2000);
        })

        this.setCart()

        setTimeout(() => {
            this.start()
        }, 8000);

        this.askPermission()
    },
    methods: {
        ...mapActions([
            'REMOVE_PRODUCT_FROM_CART',
            'SET_CHAT_UNREAD_MESSAGE',
            'SET_REQUEST_UNREAD',
            'SET_TICKET_UNREAD_MESSAGE',
            'SET_ORDERS_LAST',
            'SET_COUNT_PROFORMA',
            'SET_CART',
            'SET_BACKGROUND_FROM_UNSPLASH'
        ]),

        askPermission() {
            return new Promise(function (resolve, reject) {
                const permissionResult = Notification.requestPermission(function (result) {
                    resolve(result);
                });

                if (permissionResult) {
                    permissionResult.then(resolve, reject);
                }
            }).then(function (permissionResult) {
                if (permissionResult !== 'granted') {
                    throw new Error("We weren't granted permission.");
                }
            });
        },

        sendNotification(data) {
            this.soundNotification.play()
            if (Notification.permission !== "denied") {
                const notification = new Notification(data.title, {
                    body: data.body,
                    requireInteraction: true,
                    icon: "https://creoonline.nl/img/logo.svg",
                    silent: true,
                });
            }
        },

        async start()
        {
            await this.setSetting()
            await this.checkChatsUser()
            await this.getListenerTicket()
            await this.setLastOrder()
            await this.getOfferCount()
            await this.getChatUnreadMessagesCount()
            await this.getTicketUnreadMessagesCount()
            await this.getRequestCount()
        },

        async recalculateMessage() {
            await this.getTicketUnreadMessagesCount()
            await this.getChatUnreadMessagesCount()
            await this.getRequestCount()
        },
        async reconnectingChat() {
            await this.checkChatsUser()
            await this.getChatUnreadMessagesCount()
        },
        setCart() {
            axios.post('/get/cart/preset', {
                frame: this.$route.path.indexOf('frame') > -1
            }).then((response) => {
                this.SET_CART(response.data);
            });
        },
        getTicketUnreadMessagesCount() {
            axios.get(`/ticket/unread`).then((response) => {
                this.SET_TICKET_UNREAD_MESSAGE(response.data > 0 ? response.data : 0)
            })
        },
        getChatUnreadMessagesCount() {
            axios.get('/chat/messages/unread').then((response) => {
                this.SET_CHAT_UNREAD_MESSAGE(response.data > 0 ? response.data : 0)
            })
        },
        getRequestCount() {
            axios.get('/request/count').then((response) => {
                this.SET_REQUEST_UNREAD(response.data > 0 ? response.data : 0)
            })
        },

        getOfferCount() {
            axios.get('/orders/offer').then((response) => {
                this.SET_COUNT_PROFORMA(response.data > 0 ? response.data : 0)
            })
        },
        setLastOrder() {
            axios.get('/order/center?active=true').then((response) => {
                this.SET_ORDERS_LAST(response.data);
            });
        },
        getListenerTicket() {
            axios.get('/listener/ticket').then((response) => {
                this.ticketIds = response.data
            });
        },
        checkChatsUser() {
            axios.get('/chat/live').then((response) => {
                this.chatsIds = response.data;
                if (response.data.length > 0) {
                    response.data.forEach(chat => {
                        this.$root.$emit('chatMessageEvent', {data: chat})
                        // this.addListenerChat(chat.id)
                    })
                }
            });
        },
        setSetting() {
            axios.get('/customer/setting').then((response) => {

                if (response.data.data.background != 0) {
                    this.SET_BACKGROUND_FROM_UNSPLASH(response.data.data.backgroundUrl);
                } else {
                    this.SET_BACKGROUND_FROM_UNSPLASH('');
                }
            }).catch((e) => {
                console.log(e)
            })
        }
    },
    computed: {
        ...mapGetters({
            cart: 'GET_CART',
        })
    },
    destroyed() {
        Echo.channel('order').stopListening('ChangeQuantityProduct')
    }
});

