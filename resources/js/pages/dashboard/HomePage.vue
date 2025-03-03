<template>
    <div class="products-list" v-if="products">
        <Breadcrumbs v-if="this.$route.query.category_id != undefined" type="category"
                     :categoryId="this.$route.query.category_id"/>
        <div class="product-controls">
            <div class="row align-items-center product-controls-container">
                <div class="col-sm-8">
                    <div class="product-controls__sort">
                        <label class="product-controls__sort-label">Sorteren op:</label>
                        <select class="product-controls__sort-select" v-model="sort" v-on:change="getProducts()">
                            <option value="default">{{ $t('DashboardSortingDefault') }}</option>
                            <option value="sortByPriceLowToHigh">{{ $t('DashboardSortingLow') }}</option>
                            <option value="sortByPriceHighToLow">{{ $t('DashboardSortingHigh') }}</option>
                            <option value="sortBySoldHighToLow">{{ $t('DashboardSortingSold') }}</option>
                            <option value="sortByRatingHighToLow">{{ $t('DashboardSortingRating') }}</option>
                            <option value="sortByCounterHighToLow">{{ $t('DashboardSortingQuantity') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="product-controls__sort-result">{{ totalProductOnPage }} Resultaten gevonden</div>
                </div>
            </div>
        </div>
        <div class="row">
            <template v-for="(product, index) in products">
                <div class="col-12 product-controls__card">
                    <DashboardProductCard :product="product" :isLoadingProductsInfo="isLoadingProductsInfo"/>
                </div>
                <div v-if="!((index + 1) % pagination.productsPerPage) && ((index + 1) !== products.length)"
                     class="banner-block-wrapper">
                    <BannerBlockSlider/>
                </div>
            </template>
        </div>

        <div ref="infiniteScrollTrigger"></div>
        <a href="#" class="scroll-top" @click.prevent="scrollUp" :class="{visible: isScrollVisible}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="up">
                <path
                        d="M256 0C114.844 0 0 114.833 0 256s114.844 256 256 256 256-114.833 256-256S397.156 0 256 0zm0 490.667C126.604 490.667 21.333 385.396 21.333 256S126.604 21.333 256 21.333 490.667 126.604 490.667 256 385.396 490.667 256 490.667z"></path>
                <path
                        d="M263.542 173.792c-4.167-4.167-10.917-4.167-15.083 0L131.125 291.125c-4.167 4.167-4.167 10.917 0 15.083 4.167 4.167 10.917 4.167 15.083 0L256 196.417l109.792 109.792a10.634 10.634 0 0 0 7.542 3.125c2.729 0 5.458-1.042 7.542-3.125 4.167-4.167 4.167-10.917 0-15.083L263.542 173.792z"></path>
            </svg>
        </a>
        <div class="home-page__separator"></div>

        <pagination
                :countProductOnPage="countProductOnPage"
                :totalProductOnPage="totalProductOnPage"
                :totalProductInSite="totalProductInSite"
                :pagination="pagination"
                :offset="3"
                @changeCountProducts="changeCountProducts"
                @paginate="getProducts()"
        />
    </div>
    <div v-else class="ta-c fs-16">{{ result }}</div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Breadcrumbs from "../../components/Breadcrumbs";
import BannerBlockSlider from "../../components/dashboard/BannerBlockSlider";
import DashboardProductCard from "../../components/dashboard/DashboardProductCard";
import Pagination from "../../components/dashboard/Pagination";

export default {
    components: {
        Pagination,
        Breadcrumbs,
        DashboardProductCard,
        BannerBlockSlider
    },
    data() {
        return {
            sort: 'default',
            countProductOnPage: 30,
            totalProductOnPage: 0,
            totalProductInSite: 0,
            lastAllNewProducts: null,
            lastAddedProducts: null,
            lastReadyToGoProducts: null,
            lastAllNewProductsToggle: true,
            lastAddedProductsToggle: true,
            lastReadyToGoProductsToggle: true,
            products: null,
            result: '',
            resultCounterProducts: 0,
            sortingName: 'default',
            isScrollVisible: false,
            isScrollPanel: false,
            isLoadingProductsInfo: false,

            searchInput: '',

            pagination: {
                pageCount: 10,
                productsPerPage: 20,
                lastPage: 1,
                current_page: 1,
                countProduct: 30
            },
        }
    },
    computed: {
        ...mapGetters([
            'GET_LOADING'
        ])
    },
    mounted() {

        this.GET_LOADING_FROM_REQUEST(true);

        if (Object.keys(this.$router.history.current.query).length) {

            if (this.$router.history.current.query?.discount) {
                alert('discount request');
            }
            if (this.$router.history.current.query?.ready) {
                alert('ready to buy request');
            }
            if (this.$router.history.current.query?.configuration) {
                alert('configuration request');
            }
        }

        this.getProducts(this.$route.query.search)
        this.$root.$on('startSearch', (search) => {
            this.searchInput = search
            this.getProducts(search);
        })

        this.$root.$on('changeQuantityProduct', (data) => {
            this.checkQuantityProduct(data)
        })
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        checkQuantityProduct(data) {
            if (this.products != undefined && this.products != null) {
                this.products.forEach((option) => {
                    if (option.id != undefined && option.id == data.prodId) {
                        option.quantity = data.quantity
                    }
                })
            }
        },
        changeCountProducts(item) {
            this.pagination.current_page = 1
            this.countProductOnPage = item
            this.getProducts()
        },
        getProducts(search = '') {

            if (search == '') {
                search = this.searchInput
            }

            axios.post('/catalog-products', {
                ...this.$route.query,
                page: this.pagination.current_page,
                limit: this.countProductOnPage,
                sort: this.sort,
                search: search,
                frame: this.$route.path.indexOf('frame') > -1,
                full: 'false',
            }).then(response => {
                if (response.data.meta.total > 0) {
                    this.products = response.data.data
                    this.totalProductOnPage = response.data.meta.total
                    this.totalProductInSite = response.data.meta.total_catalog
                    this.pagination.lastPage = Math.ceil(response.data.meta.total / this.countProductOnPage)
                    this.getProductListInfo()
                } else {
                    this.products = null
                    this.pagination.lastPage = 1

                    if (this.$route.query.type !== undefined && this.$route.query.type === 'Sale') {
                        this.result = "Helaas, momenteel hebben wij geen aanbiedingen lopen"
                    } else {
                        this.result = "Sorry wij hebben helaas niet gevonden wat u zoekt"
                    }
                }
                this.GET_LOADING_FROM_REQUEST(false);
            }).catch((errors) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(errors.response.data)
            })
        },
        async getProductListInfo()
        {
            this.isLoadingProductsInfo = true
            let productIds = [];
            this.products.forEach(option => {
                productIds.push(option.id);
            });

            axios.post('/catalog-products-info', {
                productIds: productIds,
            }).then(response => {
                response.data.forEach(productInfo => {
                    this.products.forEach(product => {
                        if (productInfo.id == product.id) {
                            product.price = productInfo.price
                            product.priceBase = productInfo.priceBase
                            product.priceOld = productInfo.priceOld
                            product.priceNds = productInfo.priceNds
                            product.attributes = productInfo.attributes
                        }
                    })
                });
                this.isLoadingProductsInfo = false

            }).catch((errors) => {
                console.log(errors.response.data)
            })
        },
        // scrollTrigger() {
        //     const observer = new IntersectionObserver((entries) => {
        //         entries.forEach((entry) => {
        //             if (entry.intersectionRatio > 0 && this.pagination.currentPage < this.pagination.pageCount && !this.GET_LOADING) {
        //                 const scrollY = window.scrollY || window.pageYOffset;
        //                 this.GET_LOADING_FROM_REQUEST(true);
        //
        //                 // simulate ajax call
        //                 setTimeout(() => {
        //
        //                     this.pagination.currentPage += 1;
        //
        //                     axios.post('/getProductsHomePage', {page: this.pagination.currentPage})
        //                         .then((resp) => {
        //                             this.products = this.products.concat(resp.data)
        //                             this.sortProducts = this.products.slice();
        //
        //                             if (this.sortingName !== 'default') {
        //                                 this.sortingByFunction(this.sortingName);
        //                             }
        //
        //                             setTimeout(() => {
        //                                 window.scrollTo(0, scrollY);
        //                                 this.GET_LOADING_FROM_REQUEST(false);
        //                             });
        //                         })}, 1000);
        //             }
        //         });
        //     });
        //
        //     observer.observe(this.$refs['infiniteScrollTrigger']);
        // },
        changeLastAddedProducts() {
            this.lastAddedProductsToggle = !this.lastAddedProductsToggle;
        },
        changeLastAllNewProducts() {
            this.lastAllNewProductsToggle = !this.lastAllNewProductsToggle;
        },
        changeLastReadyToGoProducts() {
            this.lastReadyToGoProductsToggle = !this.lastReadyToGoProductsToggle;
        },
    },
    watch: {
        $route(to, from) {
            if (to.path === '/catalog') {
                this.getProducts()
            } else {
                this.$router.go()
            }
        }
    },
    destroyed() {
        this.$root.$off('changeQuantityProduct')
    }
}
</script>
