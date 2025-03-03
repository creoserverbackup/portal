<template>
    <div class="support-list-section row">
        <div class="col-md-6 br-gray">
            <div class="support-list table-responsive support-list-section__info scroll">
                <table class="support-list__table">
                    <tr v-for="support in supportList">
                        <td>
                            <!--                                    <i class="support-list__avatar icon-man"></i>-->
                            <i class="support-list__avatar"
                               :class="support.gender ? 'icon-' + support.gender : 'icon-man'"></i>
                        </td>
                        <td>
                            <router-link :to="getLink(support.uid)">
                                <span class="support-list__name">{{ support.username }}</span>
                            </router-link>
                        </td>
                        <td>
                            <span class="support-list__division">{{ support.role }}</span>
                        </td>
                        <td>
                                <span class="support-list__status" :class="support.online ? 'online' : 'offline'">{{
                                        support.online ? 'Online' : 'Offline'
                                    }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6" v-if="lastOrders">
            <simplebar class="support-list-section__info scroll" data-simplebar-auto-hide="false">
                <ul class="mb-2" v-for="(item, key) in lastOrders">
                    <li class="row bg-primary c-white pl-3 pt-1 pb-1">
                        <span class="col-md-1">.{{ ++key }}</span>
                        <span class="col-md-3">{{ item.creoNum }}</span>
                        <span class="col-md-5">{{ item.statusName }}</span>
                        <span class="col-md-3 ta-r">{{ item.date }}</span>
                    </li>
                </ul>
            </simplebar>
        </div>
        <div class="col-md-6" v-else>
            <p class="support-list-section__desc">U heeft momenteel geen orders uitstaan</p>
        </div>
    </div>

</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "supportListSection",
    data() {
        return {
            supportList: [],
        }
    },
    mounted() {
        this.getSupportList()
    },
    methods: {
        getSupportList() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/staff`).then((response) => {
                this.supportList = response.data
            }).catch((e) => {
                console.log(e)
            })
        },
        getLink(id) {
            return {
                name: 'staff',
                params: {
                    id
                }
            }
        },
    },
    computed: {
        ...mapGetters([
            'GET_ORDERS_LAST',
        ]),
        lastOrders() {
            return this.GET_ORDERS_LAST;
        },
    }
}
</script>

<style scoped>

</style>