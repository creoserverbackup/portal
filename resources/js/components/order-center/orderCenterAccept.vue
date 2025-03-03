<template>
    <div class="order-center-accept">
        <header class="contact-center__header">
            <h1>
                {{ $t('navOrderCenterAccept') }}
            </h1>
            <router-link to="/interactive-center" class="order-center-accept__header-btn btn btn--secondary">
                {{ $t('orderCenterHeadingBtn') }}
            </router-link>
        </header>
        <div class="row mb-5">
            <nav class="col-6 order-center-table__nav order-center-table--thumbs mb-2">
                <ul class="order-center-table__list">
                    <li class="order-center-accept__step"
                        v-for="(item, index) in orders"
                        :class="item.active == true ? 'order-center-table__item-active' : 'order-center-table__item-passive'"
                        v-on:click="selectOrder(item.orderId)">
                        {{ ++index }}
                    </li>
                </ul>
            </nav>
        </div>
        <hr class="w-50">
        <div class="row d-flex order-center-accept__data">
            <div class="col-6 col col--2xl-6" v-if="order.orderId > 0">
                <div class="row mb-5 mt-5" v-if="order.orderId">
                    <div class="col-4">
                        <div>
                            <span>Creo odernummer: </span>
                            <span class="c-secondary">{{ order.creoNum }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <span>Aanmaak datum: </span>
                            <span class="c-secondary">{{ order.startDate }}</span>
                        </div>
                        <div>
                            <span>Verval datum: </span>
                            <span class="c-secondary">{{ order.endDate }}</span>
                        </div>
                    </div>
                    <div class="col-4 p-0">
                        <div>
                            <span>Uw referentie: </span>
                            <span class="c-secondary">{{ order.phone }}</span>
                        </div>
                        <div>
                            <span>Aantal dagen nog geldig: </span>
                            <span v-bind:class="invoiceExpiration(order.dayLeft)">
                            {{ order.dayLeft }} </span>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 mb-5 ml-1 order-center-table__inform-div" v-if="order.orderId > 0">
                <span>

                    U heeft nog <span v-bind:class="invoiceExpiration(order.dayLeft)">{{ order.dayLeft }} dagen</span> dagen voordat deze offerte ongeldig wordt. Mocht u meer tijd nodig hebben, neem dan contact met ons op.
                    Na <span v-bind:class="invoiceExpiration(order.dayLeft)">{{
                        order.dayLeft
                    }} dagen</span> zal deze proforma automatisch uit uw customer portal verdwijnen als u geen actie heeft ondernomen. Prijzen voor een toekomstige proforma kunnen dan gewijzigd zijn. Voor meer informatie verwijzen wij u graag naar onze algemene voorwaarden. Let op: het accepteren van deze proforma betekent automatisch akkoord gaan met de algemene voorwaarden.


                </span>
                    <span class="mr-auto ml-auto mt-5 fw-bold">Welke actie wilt u ondernemen op deze proforma?</span>
                </div>
                <div class="order-center-table__btn-div">


<!--                    <router-link :to="'/payment-flow?type=offerte&orderId=' + order.orderId">-->
                        <button v-on:click="accept()">{{ $t('orderCenterButtonAccept') }}</button>
<!--                        <button>{{  $t('orderCenterButtonAccept') }}</button>-->
<!--                    </router-link>-->

<!--                    <button v-on:click="accept()">{{ $t('orderCenterButtonAccept') }}</button>-->
                    <button v-on:click="deleteProforma()">{{ $t('orderCenterButtonRefuse') }}</button>
                </div>

                <div class="order-center-table__btn-div order-center-table__btn-button w-70 ml-auto mr-auto">
                    <button class="mr-2" v-on:click="getDocument(true)">{{ $t('orderCenterButtonPrint') }}</button>
                    <button class="mr-2" v-on:click="send('email')">{{ $t('orderCenterButtonMail') }}</button>
                    <button v-on:click="getDocument()">{{ $t('orderCenterButtonDownload') }}</button>
                </div>
            </div>
            <div class="col-6 col col--2xl-6" v-if="order.orderId > 0">
                <img @click="redirectionPDF(order.download)" :src="getPreview(order.preview)"
                     class="order-center-table__order-preview">
            </div>
        </div>
    </div>
</template>

<script>
import {DateTime} from "luxon";
import {mapActions} from "vuex";

export default {
    name: "orderCenterAccept",
    data() {
        return {
            orders: {},
            order: {},
        }
    },
    mounted() {
        this.get()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getPreview(data) {
            return 'data:image/jpg;base64,' + data;
        },
        get(redirect = false) {
            axios.get('/order/center/proforma-offerte').then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);

                this.orders = response.data
                if (redirect && this.orders.length == 0) {
                    this.$router.push('order-centrum')
                } else {
                    let orderIdTemp = this.$route.query.orderId

                    if (orderIdTemp) {
                        this.selectOrder(orderIdTemp)
                    } else {
                        this.selectOrder()
                    }
                }
            }).catch((e) => {
                console.log(e)
            })
        },
        getPic() {
            this.orders.forEach((order, i) => {
                if (order.orderId == this.order.orderId) {
                    axios.get(order.preview).then((data) => {
                        if (data.data != undefined && data.data != '') {
                            this.pic = 'data:image/jpg;base64,' + data.data;
                        }
                    }).catch((e) => {
                        console.log(e)
                    })
                }
            });
        },
        invoiceExpiration(days) {
            if (days <= 30 && days > 10) {
                return 'c-success';
            } else if (days <= 10 && days > 3) {
                return 'c-secondary';
            } else {
                return 'c-red';
            }
        },
        selectOrder(orderId = 1) {
            let finish = false
            this.order = {}
            let newStack = []

            this.orders.forEach((value) => {
                value.active = false
                if (orderId === 1 && finish == false) {
                    value.active = true
                    this.order = value
                    finish = true
                }
                if (value.orderId == orderId) {
                    value.active = true
                    this.order = value
                }
                newStack.push(value)
            })
            this.orders = newStack

            if (this.order.orderId == undefined) {
                this.selectOrder()
            }
        },
        getDate(date) {
            return DateTime.fromSeconds(date).toFormat('dd-MM-yyyy')
        },
        send(type = 'email') {
            axios.post(`/order/invoice/`, {
                orderId: this.order.orderId,
                type: type,
            }).then((response) => {
                this.$root.$emit('popupMessages', 'Email sent successfully')
            }).catch((e) => {
                console.log(e)
            })
        },
        getDocument(print) {
            axios.get(`/order/invoice/${this.order.orderId}`, {
                responseType: 'blob'
            }).then((response) => {
                if (print) {
                    const url = window.URL.createObjectURL(new Blob([response.data], {
                        type: "application/pdf;base64"
                    }));

                    let callbackLink = async function (url) {
                        let link = await document.createElement('iframe');
                        link.type = "application/pdf";
                        link.src = url;
                        link.style.visibility = '0';
                        link.style.position = 'absolute';
                        link.style.left = '-999999999px';
                        link.style.top = '-999999999px';
                        await document.body.appendChild(link);
                        await link.contentWindow.focus();
                        return link;
                    };

                    let link = callbackLink(url).then(function (link = link) {
                        link.contentWindow.focus();
                        link.contentWindow.print();

                        setTimeout(() => {
                            document.body.removeChild(link);
                        }, 60000); // printing service is active only for 60s
                    });
                } else {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    const fileName = this.order.creoNum + '.pdf';

                    link.href = url;
                    link.setAttribute('download', fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }).catch((e) => {
                console.log(e)
            })
        },
        deleteProforma() {
            this.GET_LOADING_FROM_REQUEST(true);
            axios.delete(`/order/invoice/${this.order.orderId}`).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);
                this.get(true)
            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },
        accept() {
            this.GET_LOADING_FROM_REQUEST(true);
            axios.put(`/order/invoice/${this.order.orderId}`).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false);

                if (response.data.product) {
                    let message = 'Je kunt het niet accepteren. Producten die momenteel niet op voorraad zijn:'
                    response.data.product.forEach(item => {
                        message += ' ' + item.name + ';'
                    });

                    this.$root.$emit('popupMessages', message)
                } else {
                    window.location.href = process.env.MIX_WEBSHOP_URL + '/accounts/#/payment-flow?orderId='+ this.order.orderId + '&type=offerte'
                }
                this.get()

            }).catch((e) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(e)
            })
        },
        redirectionPDF(url) {
            if (url != '') {
                window.open(url);
            }
        },
    }
}
</script>
