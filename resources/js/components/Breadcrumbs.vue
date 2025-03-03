<template>
    <div class="breadcrumb-line">
        <router-link to="/catalog" itemprop="item">
            Main dashboard
        </router-link>
        <span class="breadcrumb-separator">»</span>
        <span v-if="type == 'category'">{{ categoryName }}</span>
        <a v-if="type == 'product'"
                     v-bind:href="webshopUrl + '/accounts/#/search?search=&category_id=' + categoryProdId">
            {{ categoryName }}
        </a>
        <span class="breadcrumb-separator" v-if="type == 'product'">»</span>
        <span v-if="type == 'product'">{{ productName }}</span>
    </div>
</template>

<script>
export default {
    props: {
        type: {},
        categoryId: {},
        article: {},
        productName: {},
    },

    data() {
        return {
            categoryName: '',
            categoryProdId: '',
        }
    },
    computed: {
        webshopUrl: function () {
            return process.env.MIX_WEBSHOP_URL;
        }
    },
    mounted() {
        this.getBreadcrumbs()
    },
    methods: {
        getBreadcrumbs() {
            let categoryId = this.categoryId ?? ''
            let article = this.article ?? ''
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/breadcrumbs?categoryId=${categoryId}&article=${article}`)
                .then((response) => {
                    this.categoryName = response.data.categoryName
                    this.categoryProdId = response.data.categoryId
                });
        },
    }
}
</script>

