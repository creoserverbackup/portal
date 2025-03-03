<template>
    <div class="static-page">
        <contact-form-main v-if="'our-data' === $route.params.slug"/>
        <main class="static-page col-lg-10 col-md-12 col-sm-12 col-xl-10 p-0" v-html="text" v-else>
        </main>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import Vue from 'vue'
import VueSocialSharing from 'vue-social-sharing'
import ContactFormMain from "../../components/page/contactFormMain";

Vue.use(VueSocialSharing)

export default {
    components: {
        ContactFormMain,
    },
    created() {
        this.fetchData()
    },
    data() {
        return {
            text: '',
        }
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
        ]),
        fetchData() {
            if (this.$route.params.slug !== undefined) {
                axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/settings/page/content/${this.$route.params.slug}`
                ).then((response) => {
                    this.GET_LOADING_FROM_REQUEST(false)

                    if (response.data) {
                        this.text = response.data
                    } else {
                        this.getOldText()
                    }
                }).catch((e) => {
                    this.GET_LOADING_FROM_REQUEST(false)
                    this.text = 'Page not found'
                    console.log(e)
                })
            }
        },
        getOldText() {
            switch (this.$route.params.slug) {
                default:
                    this.text = '<h1>404 page</h1><p>Page not found</p>'
                    break
            }
        }
    },
    watch: {
        $route(to, from) {
            this.fetchData()
        }
    }
}
</script>

<style lang="scss">

.page-payment-terms {

    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
    }

    font-size: 16px;
    table {
        width: 70%;
        td {
            border: none;
            padding: 0 0 0 20px;
        }

        tr {
            td:nth-child(1) {
                width: 21%;
            }
        }
    }

    &__table-pay-data {
        td {
            padding: 0px;
        }
    }
}

.page-general-terms {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

.page-complaints-policy {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

.page-return-policy {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

.page-right-withdrawal {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

.page-warranty-policy {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

.page-privacy-policy {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

.page-shipping-conditions {
    font-size: 16px;
    &__title {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 0px;
    }

    &__subtitle {
        font-weight: bold;
        font-size: 20px;

    }
}

</style>
