import Vue from 'vue';
import VueRouter from 'vue-router';

// import { default as SetupLayout } from '../pages/setup/SetupLayout';
//
// import { default as SetupStepOne } from '../pages/setup/SetupProcessOne';
// import { default as SetupStepTwo } from '../pages/setup/SetupProcessTwo';
// import { default as SetupStepThree } from '../pages/setup/SetupProcessThree';
// import { default as SetupStepFour } from '../pages/setup/SetupProcessFour';

// const SetupStepOne = () => import('../pages/setup/SetupProcessOne')
// const SetupStepTwo = () => import('../pages/setup/SetupProcessTwo')
// const SetupStepThree = () => import('../pages/setup/SetupProcessThree')
// const SetupStepFour = () => import('../pages/setup/SetupProcessFour')

import { default as ProfileSettings} from '../pages/dashboard/ProfileSettingViewPage';

import { default as DashboardLayout } from '../pages/dashboard/DashboardLayout';
import { default as PaymentFlowLayoutFrame } from '../pages/dashboard/PaymentFlowLayoutFrame';

import { default as DashboardHomePage } from '../pages/dashboard/HomePage';
import { default as DashboardProductPage } from '../pages/dashboard/ProductPage';
import { default as DashboardSupportCenter } from '../pages/dashboard/SupportCenter';
import { default as DashboardTicketPage } from '../pages/dashboard/TicketPage';
import { default as TicketNew } from '../pages/dashboard/TicketNew';
import { default as FAQ } from '../pages/dashboard/FAQ';
import { default as RMA } from '../pages/dashboard/RMA';
import { default as InteractiveCenter } from '../pages/dashboard/InteractiveCenter';
import { default as Staff } from '../pages/dashboard/Staff';
import { default as ContactCenter } from '../pages/dashboard/ContactCenter';

import { default as Chat } from '../pages/dashboard/Chat';
import { default as ChatModalPage } from '../pages/dashboard/ChatModalPage';

import { default as RequestPage } from '../pages/dashboard/RequestPage';
import { default as RequestChatPage } from '../pages/dashboard/RequestChatPage';
import { default as DedicatedHosting } from '../pages/dashboard/DedicatedHosting';

import { default as DownloadCenter } from '../pages/dashboard/DownloadCenter';
import { default as DashboardProfile } from '../pages/dashboard/ProfileSettingPage';
import { default as OrderCentrum } from '../pages/dashboard/OrderCentrum';
import { default as OrderCenterDashboard } from '../components/order-center/OrderCenterDashboard';
import { default as OrderCenterAccept } from '../components/order-center/orderCenterAccept';
import { default as OrderCenterOfferte } from '../components/order-center/OrderCenterOfferte';
import { default as OrderCenterReturn } from '../components/order-center/OrderCenterReturn';
import { default as OrderCenterContract } from '../components/order-center/OrderCenterContract';
import { default as OrderCenterUpload } from '../components/order-center/OrderCenterUpload';

import { default as PaymentFlowLayout } from '../pages/dashboard/PaymentFlowLayout';
import { default as StaticPage } from '../pages/dashboard/StaticPage';
// import StaticPage from "../components/dashboard/StaticPage";
import DashboardReturnService from "../pages/dashboard/DashboardReturnService";
import {LOGIN_PATH, MAIN_PAGE} from "../data/config";

Vue.use(VueRouter);

export const router = new VueRouter({

    routes: [
        // {
            // path: '/setup',
            // component: SetupLayout,
            // children: [
            //     {
            //         path: '/',
            //         redirect: '/setup/process-one'
            //     },
            //     {
            //         path: '/setup/process-one',
            //         component: SetupStepOne
            //     },
            //     {
            //         path: '/setup/process-two',
            //         component: SetupStepTwo
            //     },
            //     {
            //         path: '/setup/process-three',
            //         component: SetupStepThree
            //     },
            //     {
            //         path: '/setup/process-four',
            //         component: SetupStepFour
            //     }
            // ],
        // },
        {
            path: '/',
            component: DashboardLayout,
            children: [
                {
                    path: '/',
                    component: DashboardHomePage
                },
                {
                    path: '/profile-settings',
                    component: ProfileSettings ,
                },
                {
                    path: '/profile',
                    component: DashboardProfile
                },
                {
                    path: '/dedicated-hosting',
                    component: DedicatedHosting ,
                },
                {
                    path: '/catalog',
                    component: DashboardHomePage
                },
                {
                    path: '/search',
                    component: DashboardHomePage
                },
                {
                    name: 'product',
                    path: '/product/:productId',
                    component: DashboardProductPage
                },
                {
                    path: '/rma',
                    component: RMA
                },
                {
                    path: '/faq',
                    component: FAQ
                },
                {
                    path: '/return-service',
                    component: DashboardReturnService
                },
                {
                    path: '/support-center',
                    component: DashboardSupportCenter
                },
                {
                    name: 'ticket',
                    path: '/ticket/:id',
                    component: DashboardTicketPage
                },
                {
                    path: '/ticket-new',
                    component: TicketNew
                },
                {
                    path: '/download-center',
                    component: DownloadCenter
                },
                {
                    path: '/interactive-center',
                    component: InteractiveCenter,
                },
                {
                    name: 'staff',
                    path: '/staff/:id',
                    component: Staff
                },
                {
                    path: '/contact-center',
                    component: ContactCenter
                },
                {
                    name: 'chat',
                    path: '/live-support-chat',
                    component: Chat
                },
                {
                    name: 'chat-modal-page',
                    path: '/chat',
                    component: ChatModalPage
                },
                {
                    path: '/request',
                    component: RequestPage
                },
                {
                    path: '/request/:id',
                    name: 'request',
                    component: RequestChatPage
                },
                {
                    path: '/order-centrum',
                    component: OrderCentrum,
                    children: [
                        {
                            path: '/',
                            name: 'order-dashboard',
                            component: OrderCenterDashboard
                        },
                        {
                            path: '/order-center-offerte',
                            name: 'order-offerte',
                            component: OrderCenterOfferte
                        },
                        {
                            path: '/order-center-contract',
                            name: 'order-contract',
                            component: OrderCenterContract
                        },
                        {
                            path: '/order-center-return',
                            name: 'order-return',
                            component: OrderCenterReturn
                        },
                        {
                            path: '/order-center-upload',
                            name: 'order-upload',
                            component: OrderCenterUpload
                        },
                    ]
                },
                {
                    path: '/order-center-accept',
                    name: 'order-center-accept',
                    component: OrderCenterAccept
                },
                {
                    path: '/payment-flow',
                    name: 'payment-flow',
                    component: PaymentFlowLayout
                },
                {
                    path: '/page/:slug',
                    component: StaticPage
                },

            ]
        },

        {
            path: '/frame',
            component: DashboardLayout,
            children: [

                {
                    path: '/frame/catalog',
                    component: DashboardHomePage
                },
                {
                    name: '/frame/product',
                    path: '/frame/product/:productId',
                    component: DashboardProductPage
                },
                {
                    path: '/frame/search',
                    component: DashboardHomePage
                },
                {
                    path: '/frame/payment-flow',
                    name: '/frame/payment-flow',
                    component: PaymentFlowLayoutFrame
                },
            ]
        },
    ]
});

router.beforeEach((to, from, next) => {

    if (typeof from.matched != "undefined" && from.matched.length > 0 && to.matched.length > 0 && to.matched[0].path !== '/frame' && from.matched[0].path === '/frame') {
        
        let url = '/frame' + to.fullPath
        if (!to.fullPath.includes('frame=')) {
            url += '?frame=' + from.query.frame
        }
        next(url)
    } else {
        if (to.fullPath !== from.fullPath && !to.fullPath.includes('/frame')) {
            axios.post('/statistic/visits', {
                to: to.fullPath,
                path: to.path,
                from: from.fullPath,
            }).then((response) => {
                if (response.data.redirect === true) {
                    window.location.href = MAIN_PAGE
                } else {
                    next()
                }
            }).catch((errors) => {
                next()
                console.log(errors)
            })
        } else {
            next()
        }
    }
})

window.onbeforeunload = function() {
    axios.delete('/statistic/visits/uid', {
    }).then((response) => {
    }).catch((errors) => {
        console.log(errors)
    })
}
