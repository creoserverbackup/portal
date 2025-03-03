<template>
    <div class="request row">
        <div class="col-md-12">
            <request-form :categories="categories" v-if="!support"/>
        </div>
<!--        <div class="col-md-6">-->
<!--            <article class="post post&#45;&#45;max-width" v-html="text">-->
<!--            </article>-->
<!--        </div>-->
        <request-table :requests="requests" v-if="requests.length"/>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import RequestForm from "../../components/request/requestForm";
import RequestTable from "../../components/request/requestTable";

export default {
    components: {
        RequestTable,
        RequestForm,
    },
    data() {
        return {
            text: '',
            categories: [],
            requests: [],
            support: false,
        }
    },
    mounted() {
        this.getTextRequest()
        this.getLastRequest()
        this.supportCustomer()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        supportCustomer() {
            axios.get('/customer/support').then((response) => {
                if (Object.keys(response.data).length !== 0) {
                    this.support = true
                }
            })
        },
        getTextRequest() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/settings/page/content/offerte_aanvraag_pagina`
            ).then((response) => {
                this.GET_LOADING_FROM_REQUEST(false)
                this.text = response.data
            })
        },
        getLastRequest() {
            axios.get('/request/request').then((response) => {

                this.requests = response.data.requests
                this.categories = response.data.categories
            })
        },
    }
}
</script>
