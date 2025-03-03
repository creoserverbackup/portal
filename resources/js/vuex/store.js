import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'
import SecureLS from 'secure-ls';

const ls = new SecureLS({ isCompression: false });

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        mobile: false,
        locale: window.lang,
        background: '',
        loading: false,
        darkTheme: false,
        disableLifeLine: false,
        cart: [],
        lastOrders: {},
        needNds: true,
        btw: {
            valid: false,
            error: '',
        },
        logo: '',
        customer: {},
        chatUnreadMessage: 0,
        requestUnread: 0,
        countProforma: 0,
        ticketUnreadMessage: 0,
        typeTextDelivery: [
            {
                value: 1,
                text: 'Eigen adres',
                option: 'Per post /'
            },
            {
                value: 2,
                text: 'Eigen adres',
                option: 'Eigen bezorging / own delivery'
            },
            {
                value: 3,
                text: 'Afhalen',
                option: 'Afhalen / own pickup'
            },
            {
                value: 4,
                text: 'Customer adres',
                option: 'Dropshipping'
            },
            {
                value: 5,
                text: 'Buitenlands transport',
                option: 'Buitenlands transport / Forgein transport'
            },
        ],
        showChatModal: false,
        chatFirstMessage: false,
        menuLeftShow: true,
        reRenderKey: 0,
    },
    mutations: {
        SET_IS_MOBILE_TO_STATE: (state, payload) => {
            state.mobile = payload
        },
        SET_LOCALE_TO_STATE: (state, payload) => {
            state.locale = payload.locale;
        },
        SET_BACKGROUND_TO_STATE: (state, payload) => {
            state.background = payload.background;
        },
        SET_THEME_TO_STATE: (state, payload) => {
            state.darkTheme = payload.darkTheme;
        },
        SET_LOADING_TO_STATE: (state, payload) => {
            state.loading = payload.loading;
        },
        SET_LIFELINE_TO_STATE: (state, payload) => {
            state.disableLifeLine = payload.disableLifeLine;
        },
        SET_NEED_NDS_TO_STATE: (state, payload) => {
            state.needNds = payload.state;
        },
        SET_BTW_TO_STATE: (state, bool) => {
            state.btw = bool.state;
        },
        SET_LOGO_CUSTOMER_TO_STATE: (state, payload) => {
            state.logo = payload.state;
        },
        SET_CUSTOMER_TO_STATE: (state, payload) => {
            state.customer = payload.state;
        },
        SET_CART_TO_STATE: (state, payload) => {
            state.cart = payload.state;
        },
        SET_ORDERS_LAST_TO_STATE: (state, payload) => {
            state.lastOrders = payload.state;
        },
        SET_CHAT_UNREAD_MESSAGE_TO_STATE: (state, payload) => {
            state.chatUnreadMessage = payload.state;
        },
        SET_REQUEST_UNREAD_TO_STATE: (state, payload) => {
            state.requestUnread = payload.state;
        },
        SET_COUNT_PROFORMA_TO_STATE: (state, payload) => {
            state.countProforma = payload.state;
        },
        SET_TICKET_UNREAD_MESSAGE_TO_STATE: (state, payload) => {
            state.ticketUnreadMessage = payload.state;
        },
        SET_SHOW_CHAT_MODAL_TO_STATE: (state, payload) => {
            state.showChatModal = payload.state;
        },
        SET_CHAT_FIRST_MESSAGE_TO_STATE: (state, payload) => {
            state.chatFirstMessage = payload.state;
        },
        SET_MENU_LEFT_SHOW_TO_STATE: (state, payload) => {
            state.menuLeftShow = payload.state;
        },
        REMOVE_PRODUCT_FROM_CART_TO_STATE: (state, payloads) => {
            axios.post('/del/cart/product', {product: payloads.payload.product, frame: payloads.payload.frame}).then((response) => {
                state.cart = response.data
                ++state.reRenderKey;
            })
        },
        ADD_PRODUCT_TO_CART_TO_STATE: (state, payloads) => {
            axios.post('/cart/product', {
                product: payloads.payload.product,
                frame: payloads.payload.frame
            }).then((response) => {
                axios.post('/get/cart/preset', {
                    frame: payloads.payload.frame
                }).then((response) => {
                    state.cart = response.data
                    ++state.reRenderKey;
                })
            })
        },
        SET_RE_RENDER_KEY_TO_STATE: (state) => {
            ++state.reRenderKey;
        },
    },
    actions: {
        setMobile(context, payload)
        {
            context.commit({
                type: 'SET_IS_MOBILE_TO_STATE',
                mobile: payload
            })
        },
        GET_LOCALE_FROM_SWITCHER(context, payload) {
            context.commit({
                type: 'SET_LOCALE_TO_STATE',
                locale: payload
            });
        },
        SET_BACKGROUND_FROM_UNSPLASH(context, payload) {
            context.commit({
                type: 'SET_BACKGROUND_TO_STATE',
                background: payload
            });
        },
        GET_THEME_FROM_SWITCHER(context, payload) {
            context.commit({
                type: 'SET_THEME_TO_STATE',
                darkTheme: payload
            });
        },
        GET_LOADING_FROM_REQUEST(context, payload) {
            context.commit({
                type: 'SET_LOADING_TO_STATE',
                loading: payload
            })
        },
        GET_LIFELINE_FROM_SWITCHER(context, payload) {
            context.commit({
                type: 'SET_LIFELINE_TO_STATE',
                disableLifeLine: payload
            });
        },
        REMOVE_PRODUCT_FROM_CART(context, payload) {
            context.commit({
                type: 'REMOVE_PRODUCT_FROM_CART_TO_STATE',
                payload: payload
            });
        },
        SET_NEED_NDS_COUNTRY(context, payload) {
            context.commit({
                type: 'SET_NEED_NDS_TO_STATE',
                state: payload
            });
        },
        SET_BTW(context, bool) {
            context.commit({
                type: 'SET_BTW_TO_STATE',
                state: bool
            });
        },
        SET_LOGO_CUSTOMER(context, payload) {
            context.commit({
                type: 'SET_LOGO_CUSTOMER_TO_STATE',
                state: payload
            });
        },
        SET_CUSTOMER(context, payload) {
            context.commit({
                type: 'SET_CUSTOMER_TO_STATE',
                state: payload
            });
        },
        SET_CART(context, payload) {
                context.commit({
                    type: 'SET_CART_TO_STATE',
                    state: payload
            })
        },
        SET_ORDERS_LAST(context, payload) {
                context.commit({
                    type: 'SET_ORDERS_LAST_TO_STATE',
                    state: payload
            })
        },
        SET_CHAT_UNREAD_MESSAGE(context, payload) {
                context.commit({
                    type: 'SET_CHAT_UNREAD_MESSAGE_TO_STATE',
                    state: payload
            })
        },
        SET_REQUEST_UNREAD(context, payload) {
                context.commit({
                    type: 'SET_REQUEST_UNREAD_TO_STATE',
                    state: payload
            })
        },
        SET_COUNT_PROFORMA(context, payload) {
                context.commit({
                    type: 'SET_COUNT_PROFORMA_TO_STATE',
                    state: payload
            })
        },
        SET_TICKET_UNREAD_MESSAGE(context, payload) {
                context.commit({
                    type: 'SET_TICKET_UNREAD_MESSAGE_TO_STATE',
                    state: payload
            })
        },
        SET_SHOW_CHAT_MODAL(context, payload) {
                context.commit({
                    type: 'SET_SHOW_CHAT_MODAL_TO_STATE',
                    state: payload
            })
        },
        SET_CHAT_FIRST_MESSAGE(context, payload) {
                context.commit({
                    type: 'SET_CHAT_FIRST_MESSAGE_TO_STATE',
                    state: payload
            })
        },
        SET_MENU_LEFT_SHOW(context, payload) {
                context.commit({
                    type: 'SET_MENU_LEFT_SHOW_TO_STATE',
                    state: payload
            })
        },
        ADD_PRODUCT_TO_CART(context, payload) {
            context.commit({
                type: 'ADD_PRODUCT_TO_CART_TO_STATE',
                payload: {
                    product: payload.product,
                    frame: payload.frame,
                }
            });
        },

        SET_RE_RENDER_KEY(context) {
            context.commit({
                type: 'SET_RE_RENDER_KEY_TO_STATE'
            })
        },

    },
    getters: {
        isMobile() {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true
            } else {
                return false
            }
        },
        GET_LOCALE(state) {
            return state.locale;
        },
        GET_BACKGROUND(state) {
            return state.background;
        },
        GET_NEED_NDS(state) {
            return state.needNds;
        },
        GET_BTW(state) {
            return state.btw;
        },
        GET_CUSTOMER(state) {
            return state.customer;
        },
        GET_LOGO(state) {
            return state.logo;
        },
        GET_THEME(state) {
            return state.darkTheme;
        },
        GET_LOADING(state) {
            return state.loading;
        },
        GET_LIFELINE(state) {
            return state.disableLifeLine;
        },
        GET_CART(state) {
            return state.cart;
        },
        GET_CHAT_UNREAD_MESSAGE(state) {
            return state.chatUnreadMessage;
        },
        GET_TICKET_UNREAD_MESSAGE(state) {
            return state.ticketUnreadMessage;
        },
        GET_REQUEST_UNREAD(state) {
            return state.requestUnread;
        },
        GET_ORDERS_LAST(state) {
            return state.lastOrders;
        },
        GET_TYPE_TEXT_DELIVERY(state) {
            return state.typeTextDelivery;
        },
        GET_COUNT_PROFORMA(state) {
            return state.countProforma;
        },
        GET_SHOW_CHAT_MODAL(state) {
            return state.showChatModal;
        },
        GET_CHAT_FIRST_MESSAGE(state) {
            return state.chatFirstMessage;
        },
        GET_MENU_LEFT_SHOW(state) {
            return state.menuLeftShow;
        },
        GET_RE_RENDER_KEY(state) {
            return state.reRenderKey;
        },
    },
    plugins: [
        createPersistedState({
            storage: {
                getItem: (key) => ls.get(key),
                setItem: (key, value) => ls.set(key, value),
                removeItem: (key) => ls.remove(key),
            }
        })
    ]
});
