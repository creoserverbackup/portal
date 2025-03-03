<template>
    <div class="product-main" v-if="product">
        <Breadcrumbs type="product" :article="product.article" :productName="product.name"/>
        <div class="row product-main__raw">
            <div class="product-main__col-content">
                <header class="product-main__header">
                    <div class="product-main__heading">{{ product.name }}</div>
                </header>
                <div class="product-main__body">
                    <div class="product-mai n__details">
                        <product-header-info :product="product"/>

                        <template v-if="isLoading">
                            <div class="h-200 w-100">
                                <loader/>
                            </div>
                        </template>
                        <template v-else>
                            <product-tray
                                    :product="product"
                                    :trays="trays"
                                    :tray="tray"
                                    @selectTray="selectTray"
                            />
                            <product-configurator
                                    :product="product"
                                    :selectedInOptions="selectedInOptions"
                                    @selectOption="selectOption"
                                    @rebuildOptions="rebuildOptions"
                            />

                            <product-associated v-if="product.associated.length" :associated="product.associated"/>

                            <product-footer-info :product="product" :otherWarranty="otherWarranty"
                                                 :textGaranty="textGaranty" :textService="textService"
                                                 ref="productFooterInfo"
                                                 @addWarranty="addWarranty"/>
                        </template>
                    </div>
                </div>
            </div>
            <div class="product-main__col-aside">
                <div class="product-summary"
                     v-bind:class="[($route.path.indexOf('frame') > -1) ? 'product-main__is_frame' : '']">
                    <div class="product-summary__wrapper">
                        <div class="product-summary__data">
                            <span class="product-summary__name">{{ product.name }}</span>

                            <div class="h-170" v-if="isLoading">
                                <loader/>
                            </div>
                            <template v-else>
                                <product-prices
                                        :product="product"
                                        :quantity="quantity"
                                        :prices="getPrices"
                                />
                                <span class="product-summary__separator"></span>
                                <!--                            <div class="product-summary__qnt" v-if="!isSoldOut && !product.masterId">-->
                                <div class="product-summary__qnt">
                                    <div class="product-summary__qnt-left">
                                        <label for="productQnt" class="m-0 fs-15">Aantal:</label>

                                        <input :disabled="isSoldOut" type="number" v-if="isSoldOut"
                                               v-model="emptyQuantity"
                                               class="product-summary-input"/>
                                        <input type="number" id="productQnt" v-else
                                               v-on:change="verifyQuantity()" v-model="quantity"
                                               v-bind:max="product.quantity"
                                               v-bind:min="getMinProduct()"
                                               v-bind:step="getStep()"
                                               class="product-summary-input"/>
                                    </div>
                                    <product-rating :product="product" :isSoldOut="isSoldOut"/>
                                </div>
                                <product-button :isSoldOut="isSoldOut" @addProduct="addProduct" :product="product"
                                                @downloadOffer="addProduct('download')"/>
                                <!--                            <a href="https://google.com" target="_blank" v-if="!product.dedicatedHosting"-->
                                <!--                               class="product-main__side-summary__button">Dedicated laten hosten</a>-->
                                <product-config-select :selectedInOptionsAdded="selectedInOptionsAdded"
                                                       @deleteOption="deleteOption"/>
                            </template>
                        </div>
                        <social-sharing-data :product="product" v-if="!(this.$route.path.indexOf('frame') > -1)"/>
                    </div>
                </div>
            </div>
        </div>
        <popup-product-caddy-warning v-if="popupProductCaddyShow" :popupProductCaddyShow.sync="popupProductCaddyShow"/>
        <popup-product-raid-warning v-if="popupProductRaidShow" :popupProductRaidShow.sync="popupProductRaidShow"/>
        <popup-message-quantity-max-product :productMaim="product"/>
        <popup-product-configurator-out-stock v-if="productsConfiguratorOutStock.length"
                :productsConfiguratorOutStock="productsConfiguratorOutStock" :productMaim="product"/>

        <popup-product-configurator-default-change v-else-if="productsConfiguratorDefaultChange.length"
                :productsConfiguratorDefaultChange="productsConfiguratorDefaultChange" :productMaim="product"/>

    </div>
    <div class="fs-16" v-else>{{ error }}</div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import {checkPriceHelper} from "../../helper.js"
import Vue from 'vue'
import VueSocialSharing from 'vue-social-sharing'
import Breadcrumbs from "../Breadcrumbs";
import ProductRating from "./productRating";
import SocialSharingData from "./socialSharingData";
import _ from "lodash";
import ProductHeaderInfo from "./productHeaderInfo";
import ProductFooterInfo from "./productFooterInfo";
import ProductPrices from "./productPrices";
import ProductConfigSelect from "./productConfigSelect";
import categories, {default as Categories} from '../../data/categories'
import ProductConfigurator from "./productConfigurator";
import ProductAssociated from "./productAssociated";
import PopupMessageQuantityMaxProduct from "../popup/popupMessageQuantityMaxProduct";
import ProductButton from "./productButton";
import PopupProductConfiguratorOutStock from "../popup/popupProductConfiguratorOutStock";
import Loader from "../loader/loader";
import PopupProductCaddyWarning from "../popup/popupProductCaddyWarning";
import PopupProductRaidWarning from "../popup/popupProductRaidWarning";
import ProductTray from "./productTray";
import PopupProductConfiguratorDefaultChange from "../popup/popupProductConfiguratorDefaultChange";

Vue.use(VueSocialSharing)
export default {
    name: "product",
    components: {
        PopupProductConfiguratorDefaultChange,
        ProductTray,
        PopupProductRaidWarning,
        PopupProductCaddyWarning,
        Loader,
        PopupProductConfiguratorOutStock,
        ProductButton,
        PopupMessageQuantityMaxProduct,
        ProductAssociated,
        ProductConfigurator,
        ProductConfigSelect,
        ProductPrices,
        ProductFooterInfo,
        ProductHeaderInfo,
        SocialSharingData,
        ProductRating,
        Breadcrumbs,
    },
    data() {
        return {
            error: '',
            selected: {},
            samenvatting: false,
            selectedInOptions: [],
            selectedInOptionsAdded: [],
            selectedInOptionsAddedWithDefault: [],
            selectExtraWarranty: false,
            product: null,
            quantity: 1,
            emptyQuantity: '',
            scrollPosition: 0,
            otherWarranty: 0,
            textService: '',
            textGaranty: '',
            maxQntConfigurator: '',
            maxQntConfiguratorProducts: [],
            productsConfiguratorOutStock: [],
            productsConfiguratorDefaultChange: [],

            isLoading: true,

            trays: '',
            tray: '',
            popupProductCaddyShow: false,
            popupProductCaddyShowOnce: false,

            popupProductRaidShow: false,
            popupProductRaidShowOnce: false,
        }
    },
    mounted() {
        this.getProductInfo()

        this.$root.$on('changeQuantityProduct', (data) => {
            if (data != undefined) {
                if (data.prodId != undefined && this.product.id == data.prodId) {
                    this.getProductInfo()
                } else {
                    this.checkEditQuantityProduct(data)
                }
            }
        })

        this.SET_CHAT_FIRST_MESSAGE(false)
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
            'ADD_PRODUCT_TO_CART',
            'SET_CHAT_FIRST_MESSAGE',
        ]),
        getProductInfo(option = false) {
            axios.get(`/catalog-products/${this.$route.params.productId}`,
                    {
                        params: {
                            frame: this.$route.query.frame,
                            option: option,
                        }
                    }).then((response) => {
                console.log(response.data)
                if (response.data) {
                    this.product = response.data.data
                    this.trays = response.data.trays
                    this.maxQntConfigurator = response.data.data.quantity
                    this.quantity = response.data.data.multiBatch > 0 ? response.data.data.multiBatch : 1
                    this.textService = response.data.service
                    this.textGaranty = response.data.warranty_delivery
                    this.otherWarranty = response.data.warranty_options
                    this.setDefaultConfigurator()

                    if (this.product.products_configurator_out_stock) {
                        this.productsConfiguratorOutStock = this.product.products_configurator_out_stock
                    }

                    if (this.product.products_configurator_default_change) {
                        this.productsConfiguratorDefaultChange = this.product.products_configurator_default_change
                    }
                }

                if (option === false) {
                    this.getProductInfo(true)
                } else {
                    this.isLoading = false
                }

                this.GET_LOADING_FROM_REQUEST(false);
            }).catch((errors) => {
                this.GET_LOADING_FROM_REQUEST(false);
                console.log(errors?.response?.data)
                this.error = "Product not found"
            })
        },
        setDefaultConfigurator() {
            this.product.options.forEach((option) => {
                option.overInfo = ''
                option.values.forEach((value) => {
                    if (value.base) {
                        this.selectOption(value.article, option, false)
                    }
                })
            })

            this.rebuildOptions()
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        selectTray(tray) {
            this.tray = tray
        },
        async selectOption(article, option, rebuildOptions = true) {

            if (option.categoryId == Categories.backplane && rebuildOptions) {
                await this.setDefaultHardDisk()
            }

            option.counter = 1
            option.urlSelect = ''
            option.values.forEach(element => {
                element.selected = element.value == article
                if (element.article == 0) {
                    option.isNumberAllowed = false
                }

                if (element.selected) {
                    if (element.article != 0 &&
                            (
                                    option.categoryId == Categories.bays ||
                                    option.categoryId == Categories.backplane ||
                                    option.categoryId == Categories.license ||
                                    option.categoryId == Categories.warranty
                            )
                    ) {

                        option.isNumberAllowed = false
                        option.counter = 1
                    } else {
                        option.isNumberAllowed = element.article != 0
                        option.isProduct = element.isProduct
                        option.baseQuantity = element.baseQuantity
                        option.counter = element.baseQuantity > 1 ? element.baseQuantity : element.article == 0 ? element.baseQuantity : 1

                        if (Categories.cpu === option.categoryId && option.quantityMinCategory > 0 && element.article != 0) {
                            option.counter = option.quantityMinCategory
                        }


                        if (element.visible) {
                            option.urlSelect = process.env.MIX_WEBSHOP_URL + "/accounts/#/product/" + element.id
                        }
                    }
                }


                if ((this.product.categoryMain == Categories.servers || this.product.categoryMain == Categories.storage)
                        && !this.popupProductCaddyShow && !this.popupProductCaddyShowOnce
                        && this.checkCategoryDisk(option.categoryId)
                        && element.selected && !element.base) {
                    this.popupProductCaddyShow = true
                    this.popupProductCaddyShowOnce = true
                }
            })

            if (rebuildOptions) {
                await this.rebuildOptions()
            }
        },
        async setDefaultHardDisk() {
            await this.deleteOption(Categories.sata_ssd, false)
            await this.deleteOption(Categories.sata_m2_ssd, false)
            await this.deleteOption(Categories.sas_ssd, false)
            await this.deleteOption(Categories.nvme_m2_ssd, false)
            await this.deleteOption(Categories.nvme_u2_ssd, false)
            await this.deleteOption(Categories.nvme_pci_ssd, false)
            await this.deleteOption(Categories.sata_hdd, false)
            await this.deleteOption(Categories.sas_hdd, false)
        },
        checkEditQuantityProduct(data) {
            this.product.options.forEach((option) => {
                option.overInfo = ''
                let rebuildProduct = false
                option.values.forEach((value) => {
                    if (value.id != undefined && value.id == data.prodId) {

                        if (value.selected) {
                            if (data.quantity < 1) {
                                rebuildProduct = value
                            } else {
                                option.counter = data.quantity < value.quantity ? data.quantity : value.quantity
                            }
                        }
                        value.quantity = data.quantity
                    }
                })

            })
            this.verifyQuantity()
        },
        deleteOption(categoryId, rebuild = true) {
            this.product.options.forEach((item, index) => {
                if (item.categoryId === categoryId) {

                    item.values.forEach((value, key) => {
                        if (value.base) {
                            this.selectOption(value.article, item, rebuild)
                        }
                    })

                    let selectProduct = item.values.find((value) => {
                        return (value.selected === true)
                    })
                    this.product.options[index].counter = selectProduct.baseQuantity
                }
            })

            if (categoryId == categories.warranty) {
                this.selectExtraWarranty = false
                this.$refs.productFooterInfo.deleteExtraWarranty()
            }

            if (rebuild === true) {
                this.rebuildOptions()
            }
        },
        async rebuildOptions() {
            this.selectedInOptions = []
            this.selectedInOptionsAdded = []
            this.selectedInOptionsAddedWithDefault = []
            this.maxQntConfiguratorProducts = []
            this.maxQntConfigurator = this.product.quantity

            await this.product.options.forEach((option) => {
                let selectProduct = option.values.find((value) => {
                    return (value.selected === true)
                })
                option.overInfo = ''

                if (selectProduct) {
                    let quantityOrder = option.counter

                    if (selectProduct.selected && quantityOrder > 0) {
                        let selectOption = _.clone(option);
                        selectOption.counter = quantityOrder
                        this.selectedInOptions.push(selectOption)

                        let selectedOptionTemp = {}
                        let selectedOptionTempWithDefault = {}

                        let quantityOrderAdded = option.counter - +option.baseQuantity


                        if (quantityOrderAdded > 0 || (
                                (option.categoryId == Categories.license || option.categoryId == Categories.warranty) && !selectProduct.base)) {
                            selectedOptionTemp = {
                                baseQuantity: selectOption.baseQuantity,
                                categoryId: selectOption.categoryId,
                                label: selectOption.label,
                                counter: quantityOrderAdded,
                                values: selectOption.values,
                                priceBase: selectOption.priceBase,
                                base: selectProduct.base,
                            }
                            this.selectedInOptionsAdded.push(selectedOptionTemp)
                        }

                        if (option.counter > 0) {
                            selectedOptionTempWithDefault = {
                                baseQuantity: selectOption.baseQuantity,
                                categoryId: selectOption.categoryId,
                                label: selectOption.label,
                                counter: option.counter,
                                values: selectOption.values,
                                priceBase: selectOption.priceBase,
                                base: selectProduct.base,
                            }
                            this.selectedInOptionsAddedWithDefault.push(selectedOptionTempWithDefault)
                        }

                        // if (selectProduct.installed == 0) {
                        let quantityMax = Math.floor(selectProduct.quantity / option.counter)

                        if (this.maxQntConfigurator > quantityMax && option.counter > 0 && option.installed == false) {
                            this.maxQntConfiguratorProducts.push({
                                selectProduct: selectProduct,
                                selectedOptionTemp: selectedOptionTempWithDefault,
                                quantityMax: quantityMax,
                            })
                            this.maxQntConfigurator = quantityMax
                        }
                        // }
                    }
                }
            })

            if (this.selectExtraWarranty) {
                this.selectedInOptions.push(this.otherWarranty)
            }

            await this.verifyQuantity()
        },
        getMinProduct() {
            return this.product.multiBatch ? this.product.multiBatch : 1
        },
        getStep() {
            return this.product.multiBatch ? this.product.multiBatch : 1
        },
        verifyQuantity() {
            const quantityMin = this.product.multiBatch ? this.product.multiBatch : 1
            const quantityMax = this.maxQntConfigurator

            if (this.quantity < quantityMin) {
                this.quantity = quantityMin
            }

            if (this.quantity >= quantityMax) {
                if (this.quantity > quantityMax) {
                    this.$root.$emit('popupMessageQuantityMaxProduct', {
                        products: this.maxQntConfiguratorProducts,
                        quantityMax: quantityMax
                    })
                }
                this.quantity = quantityMax
            }

            if (this.product.multiBatch) {
                let ost = this.quantity % this.product.multiBatch
                if (ost < this.product.multiBatch && ost != 0) {
                    this.quantity = quantityMin
                }
            }

            this.quantity = this.quantity <= 0 ? 1 : this.quantity

        },
        addWarranty(status) {
            this.selectExtraWarranty = status
            this.rebuildOptions()
        },
        getProductFull(isLeasing = false) {

            let tempTray = {}
            if (this.tray) {
                tempTray = {
                    id: this.tray.productId,
                    name: this.tray.name,
                    quantity: (typeof (this.tray.counter) === 'undefined' ? 1 : this.tray.counter)
                }
            }

            let product = {
                id: this.product.id,
                name: this.product.name,
                quantity: Number(this.quantity),
                option: [],
                isLeasing,
                price: this.priceFull,
                associated: this.checkAssociated(isLeasing),
                tray: tempTray,
            }

            let options = this.selectedInOptionsAddedWithDefault.slice()

            if (this.selectExtraWarranty) {
                options.push(this.otherWarranty)
            }

            options.forEach((option) => {
                let selectProduct = option.values.find((value) => {
                    return (value.selected === true)
                })
                if (typeof (selectProduct) === 'undefined') {
                    return false
                }
                let tempOption = {
                    label: option.label,
                    article: selectProduct.article,
                    installed: selectProduct.installed,
                    productId: selectProduct.id,
                    name: selectProduct.name,
                    value: selectProduct.value,
                    quantity: (typeof (option.counter) === 'undefined' ? 1 : option.counter)
                }
                product.option.push(tempOption)
            })
            return product

        },
        addProduct(type = 'add') {

            if (this.maxQntConfigurator < this.quantity) {
                this.$root.$emit('popupMessageQuantityMaxProduct', {
                    products: this.maxQntConfiguratorProducts,
                    quantityMax: this.maxQntConfigurator
                })
            } else if ((this.product.categoryMain == Categories.servers || this.product.categoryMain == Categories.storage)
                && !this.popupProductRaidShowOnce && !this.popupProductRaidShow && this.selectHardDisc) {

                this.popupProductRaidShowOnce = true
                this.popupProductRaidShow = true
            } else {
                if (type === 'download') {
                    this.downloadOffer()
                } else {
                    let product = this.getProductFull(false)
                    this.ADD_PRODUCT_TO_CART({product: product, frame: this.$route.path.indexOf('frame') > -1})
                }
            }
        },
        downloadOffer() {
            this.$root.$emit('popupDownloadOfferDelivery', {
                product: this.getProductFull(),
            })
        },
        checkAssociated(isLeasing) {
            let products = []
            this.product.associated.forEach((product) => {
                if (product.checked) {
                    let item = {
                        id: product.productId,
                        name: product.name,
                        quantity: Number(this.quantity),
                        option: [],
                        isLeasing,
                        price: product.price
                    }
                    products.push(item)
                }
            })
            return products
        },
        checkCategoryDisk(category) {
            return category === categories.sata_ssd ||
                    category === categories.sata_m2_ssd ||
                    category === categories.sas_hdd ||
                    category === categories.sas_ssd ||
                    category === categories.nvme_m2_ssd ||
                    category === categories.nvme_u2_ssd ||
                    category === categories.nvme_u3_ssd ||
                    category === categories.sata_hdd
        }
    },
    watch: {
        $route(to, from) {
            this.GET_LOADING_FROM_REQUEST(true);
            this.getProductInfo()
        }
    },
    computed: {
        isSoldOut() {
            return this.product.orderAvailable === 0
        },
        getPrices() {
            return {
                priceConfiguratorBase: this.priceConfiguratorBase,
                priceFull: this.priceFull,
                priceBase: this.product.price,
                priceConfigurator: this.priceConfigurator,
            }
        },
        priceConfiguratorBase() {
            let price = this.product.price - this.product.priceBase
            return parseFloat(price.toFixed(2))
        },
        priceConfigurator() {
            let price = 0

            if (this.selectedInOptions.length > 0) {
                this.selectedInOptions.forEach((option) => {
                    let selectOption = option.values.find(el => el.selected === true)
                    if (typeof (selectOption) === 'undefined' || selectOption.article == 0) {
                        return false
                    }
                    let countOption = (option.isNumberAllowed ? (option.counter > 0 ? option.counter : 0) : 1)

                    if (selectOption.types) {
                        const optionType = selectOption.types.find(type => type.selected === true)
                        price += (optionType.priceOption * countOption)
                    } else {
                        price += (selectOption.priceOption * countOption)
                    }
                })
            }
            return parseFloat(price.toFixed(2))
        },
        priceFull() {
            return this.product.priceBase + this.priceConfigurator
        },
        selectHardDisc() {
            let find = false
            this.selectedInOptions.forEach((option) => {
                if (find === false && this.checkCategoryDisk(option.categoryId)) {
                    find = true
                }
            })
            return find
        }
    },
    destroyed() {
        this.$root.$off('changeQuantityProduct')
    }
}
</script>
