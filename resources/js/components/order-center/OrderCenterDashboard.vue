<template>
    <div>
        <div class="row order-center-table__steps">
            <div class="col-2 mt-5">
                <span class="c-primary">{{ $t('orderCenterTableName') }}</span>
            </div>
            <div class="col-4 mt-5 text-right" v-bind:class="[creoNum ? '' : 'ml-auto']">
                <span class="c-gray">ORDER: </span>
                <span class="c-primary" v-if="creoNum">{{ creoNum }}</span>
                <span class="c-primary" v-else>GEEN ORDER GESLECTEERD</span>
                <span class="c-gray pl-5">VOORTGANG</span>
            </div>
            <div class="col-6" v-if="creoNum">
                <p class="has-notify" v-if="orderStep">Status: {{ orderStep }}</p>
                <p v-else>Status: order not selected</p>
                <nav class="order-center-table__nav order-center-table--thumbs">
                    <ul class="order-center-table__list">
                        <li v-if="orderStep" class="order-center-table__item"
                            v-for="(item, key) in orderStatus" :value="item.value"
                            :class="[item.passed > 0 ? [item.passed === 2 ? 'passes' :'passed'] : 'no_passed']"
                        >{{ item.key }}
                        </li>
                        <!--                            <li v-else class="order-center-table__item no_passed">{{ item.key }}</li>-->
                    </ul>
                </nav>
            </div>
        </div>
        <div class="order-center-table__div-table scroll">
            <table class="order-center-table__table support-center__table" border="1" cellspacing="0"
                   cellpadding="10">
                <thead>
                <tr>
                    <th>{{ $t('orderCenterTableDate') }}</th>
                    <th>{{ $t('orderCenterTableOrderNumber') }}</th>
                    <th>{{ $t('orderCenterTableProductDetails') }}</th>
                    <th>{{ $t('orderCenterTableStatus') }}</th>
                    <th>
                        <template v-if="customer.needNDS">{{ $t('orderCenterTablePriceIncludeBTW') }}</template>
                        <template v-else>{{ $t('orderCenterTablePriceIncludeBTW') }}
                            <!--                        {{ $t('orderCenterTablePriceExcludeBTW') }}-->
                        </template>
                    </th>
                    <th>{{ $t('orderCenterTableAction') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tr v-for="order in orders" :key="order.id" @click="selectOrder(order)">
                    <td>{{ order.date }}</td>
                    <td>{{ order.creoNum }}</td>
                    <td class="order-center-table__table-detail">{{ order.detail }}</td>
                    <td>{{ order.statusName }}</td>
                    <td>{{ checkPrice(order.price) }}</td>
                    <td>
                        <span v-bind:class="[ getClassStatus(order.status) ]" v-if="order.download"
                              v-on:click="downloadFile(order)">{{ order.document }}
                        </span>
                        <span v-else>
                            Kan niet downloaden
                        </span>
                    </td>
                    <td>
                        <router-link :to="'/order-center-accept?orderId=' + order.orderId" class="nav-sections__order-center-accept">
                        <span v-if="order.accept" class="c-primary">
                            Accepteer offerte
                        </span>
                        </router-link>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import {checkPriceHelper} from "../../helper.js"

export default {
    data() {
        return {
            orderId: '',
            creoNum: '',
            orderStep: '',
            orders: {},
            orderStatus: [
                {
                    key: 1,
                    passed: 1,
                },
                {
                    passed: 2,
                    key: 2,
                },
                {
                    passed: 0,
                    key: 3,
                },
                {
                    passed: 0,
                    key: 4,
                },
                {
                    passed: 0,
                    key: 5,
                },
            ],
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false);

        this.get()
        this.$root.$on('updateOrdersOld', () => {
            this.get()
        })
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        get() {
            axios.get('/order/center').then((response) => {
                if (typeof response.data !== "undefined") {
                    this.orders = response.data
                }
            }).catch((e) => {
                console.log(e)
            })
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        async downloadFile(order) {

            if (order.credit) {
                this.$root.$emit('popupMessages', 'De bestelling is niet betaald. U kunt het document niet downloaden')
            } else {

                try {
                    let data = await axios.get(order.download,
                            {responseType: 'blob'});
                    const url = window.URL.createObjectURL(new Blob([data.data]));
                    const link = document.createElement('a');
                    const fileName = order.creoNum + '.pdf';

                    link.href = url;
                    link.setAttribute('download', fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    return true;
                } catch (e) {
                    return e;
                }
            }
        },

        selectOrder(order) {
            this.orderId = order.orderId
            this.creoNum = order.creoNum
            // this.orderStep = this.getOrderStep(order.status)
            this.orderStep = order.statusName
            let status = order.status > 3 && order.status <= 7 ? 2 : order.status
            status = status == 9 ? 4 : status
            this.getLineStatusOrder(status)
        },

        getLineStatusOrder(status) {
            let searchStep = false
            this.orderStatus.forEach(step => {
                if (step.key === status) {
                    step.passed = 2
                    searchStep = true
                } else {
                    step.passed = searchStep ? 0 : 1
                }
            })
        },

        getOrderStep(status) {
            status = status > 3 && status < 9 ? 3 : status
            status = status == 10 ? 11 : status
            let arr = new Map([
                [1, 'Order got recieved'], // Заказ был получен
                [2, 'Order is being worked on'], // Заказ находится в стадии разработки
                [3, 'Order is on hold'], // Заказ отложен
                [9, 'Order is cancled'],
                [5, 'Order is done'], // Заказ выполнен
                [11, 'The order is ready for resending'], // Заказ готов к повторной отправке
            ])
            return status + ': ' + arr.get(status)
        },

        getClassStatus(status) {
            status = status > 3 && status < 9 ? 3 : status
            status = status == 10 ? 11 : status
            let arr = new Map([
                [1, 'c-success'],
                [2, 'c-primary'],
                [3, 'c-primary'],
                [9, 'c-red'],
                [5, 'c-success'],
            ])
            return arr.get(status)
        },
    },
    computed: {
        ...mapState([
            'customer'
        ])
    },
    destroyed() {
        this.$root.$off('updateOrdersOld')
    }
}
</script>
