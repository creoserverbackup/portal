<template>
    <div class="curtain" :style="{opacity: this.opacity}" v-if="isLoading">
        <div class="loader">
            <div class="atom-spinner">
                <div class="spinner-inner">
                    <div class="spinner-line"></div>
                    <div class="spinner-line"></div>
                    <div class="spinner-line"></div>
                    <div class="spinner-circle"></div>
                </div>
            </div>
            <div id="loader-msg" style="white-space: pre;text-align: center;">{{ msg }}</div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

import * as loadingTexts from "../../json/loadingTexts.json"

export default {
    data() {
        return {
            loadingMsg: [],
            msg: "L O A D I N G . . .",
            isLoading: false,
            opacity: .9
        }
    },
    computed: {
        ...mapGetters([
            'GET_LOADING'
        ]),
        loading: function () {
            return this.GET_LOADING;
        }
    },
    mounted() {
    },
    created() {
        this.startLoading()
    },
    methods: {
        async startLoading() {
            await this.getLoadingMsq()
            // await this.randMsg()
            await this.theWatchTower()
        },
        getLoadingMsq() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/loading`).then((response) => {
                if (response.data != undefined) {
                    this.loadingMsg = response.data
                }
                this.randMsg()
            })
        },
        randMsg: function () {
            this.msg = this.loadingMsg[Math.floor(Math.random() * (+this.loadingMsg.length))];
        },
        theWatchTower: function () {
            if (this.isLoading && !this.GET_LOADING) {
                (function fade(self_ = self) {
                    (self_.opacity -= .05) < 0 ? self_.isLoading = false : setTimeout(fade, 25)
                })(self = this);
            } else if (!this.isLoading && this.GET_LOADING) {
                this.randMsg();
                this.opacity = .9;
                this.isLoading = true;
            }
        }
    },
    watch: {
        loading: function (status) {
            // this.theWatchTower();
            this.startLoading()
        }
    }
};
</script>
