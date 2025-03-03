<template>
    <div class="rma">
        <rma-page-title/>
        <div class="row row-column">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-6 m-0">
                        <label class="rma__form-label">{{ $t('DashboardRMASelectOrder') }}</label>
                        <select class="rma-form__select rma__select" v-on:change="getPic()" v-model="orderId">
                            <option v-for="option in orders" v-bind:value="option.orderId">Order №
                                {{ option.creoNum }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 m-0">
                        <label class="rma__form-label">{{ $t('DashboardRMASelectOrder2') }}</label>
                        <input type="text" placeholder="om wat voor product gaat het? ..."
                               v-model.trim="orderInput">

                    </div>
                </div>
                <label class="rma__form-label">{{ $t('DashboardRMAEditorLabel') }}:</label>
                <editor v-model.trim="description" class="editor-field"
                        placeholder="Probleem ornschrijving ..."
                        api-key="uobtsf4soqomzvfpm9v9nwlalevpiqjb73fbmnvic06wsjro"
                        :init="{
                    height: 200,
                    menubar: false,
                    plugins: [
                        'autolink link image tinydrive',
                    ],
                    toolbar: this.editorControl,

                    images_upload_url: 'postAcceptor.php',
                    // content_css: 'style/content.css',
                    content_css: '',
                    //content_css: 'tinymce-iframe-night-mode.css'

                }"/>
                <div class="row">
                    <div class="col-md-5">
                        <label class="rma__form-label">{{ $t('DashboardRMALabel2') }}:</label>
                        <div class="rma__data-files">
                            <add-files :type="'rma'"/>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label class="rma__form-label">{{ $t('DashboardRMALabel3') }}:</label>
                        <textarea v-model="serialNumbers" cols="30" rows="10" placeholder="Serienummers ..."
                                  class="rma-textarea"></textarea>
                    </div>
                </div>

                <div class="rma__block-checkbox">
                    <label class="rma__block-checkbox-label">
                        <input type="checkbox" v-model="isSubscribe" class="rma__block-checkbox-item">
                        <span>{{ $t('DashboardRMALabel6') }}</span>
                    </label>
                </div>

                <div class="rma__block-checkbox">
                    <label class="rma__block-checkbox-label">
                        <input type="checkbox" v-model="replacement" class="rma__block-checkbox-item">
                        <span>{{ $t('DashboardRMALabel7') }}</span>
                    </label>
                </div>
                <button class="rma__btn btn btn--secondary" type="button" v-on:click="saveRMA()">{{
                        $t('DashboardRMAButtonSave')
                    }}
                </button>

                <div class="rma__block-letop">
                    <small class="rma__block-letop-title">{{ $t('DashboardRMALabel4') }}</small>
                    <p class="rma__block-letop-text">{{ $t('DashboardRMALabel5') }}</p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="rma__pdf-label">
                    <label class="rma__form-label mt-auto">{{ $t('DashboardRMALabel8') }}</label>
                </div>
                <div class="rma__pdf-file">
                    <div class="rma__pdf-file-label" v-if="pic == ''">Er kon geen voorbeeld worden geladen.</div>
                    <img @click="redirectionPDF()" v-else class="rma__pdf-file-image" :src="pic">
                </div>

                <!--                    <div v-html="textRma"></div>-->
                <div>Klik op het document om de details te bekijken</div>
            </div>
        </div>
    </div>
</template>

<script>
import RmaPageTitle from "./rmaPageTitle";
import {mapActions, mapGetters} from "vuex";
import AddFiles from "../dashboard/AddFiles";
import {getMessageError} from "../../utils";

export default {
    name: "rmaPage",
    components: {
        AddFiles,
        RmaPageTitle
    },
    data() {
        return {
            editorControl: 'fontsizeselect | bold italic underline | forecolor | link',
            rmaId: '',
            isSubscribe: false,
            replacement: false,
            files: [],
            orders: [],
            serialNumbers: '',
            orderInput: '',
            description: '',
            orderId: '',
            pic: '',
            textRma: ''
        }
    },
    mounted() {
        this.getOrders()
        if (!this.isMobile()) this.editorControl = 'fontselect | ' + this.editorControl
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
        ]),
        ...mapGetters(['isMobile']),
        getOrders() {
            axios.get('/order/rma').then((response) => {
                this.orders = response.data.orders
                this.textRma = response.data.text
                this.GET_LOADING_FROM_REQUEST(false)
                // if (this.orders.length == 0) {
                //     this.$root.$emit('popupMessages', 'Orders not found')
                // }
            })
        },
        redirectionPDF() {
            this.orders.forEach((order, i) => {
                if (order.orderId == this.orderId) {
                    window.open(order.url);
                }
            });
        },
        getPic() {
            this.orders.forEach((order, i) => {
                if (order.orderId == this.orderId) {
                    axios.get(order.preview).then((data) => {
                        if (data.data != undefined && data.data != '') {
                            this.pic = 'data:image/jpg;base64,' + data.data;
                        }
                    }).catch(function (e) {
                        console.log(e)
                    })
                }
            });
        },
        saveRMA() {
            if (((typeof this.orderId === 'undefined' || this.orderId === '') && this.orderInput === '') ||
                    (typeof this.description === 'undefined' || this.description === '')) {

                this.$root.$emit('popupMessages', 'Fill the form')

            } else {
                this.GET_LOADING_FROM_REQUEST(true);
                let formData = new FormData()
                for (let i = 0; i < this.files.length; i++) {
                    let file = this.files[i]
                    formData.append('files[' + i + ']', file)
                }
                formData.append('orderId', this.orderId)
                formData.append('orderInput', this.orderInput)
                formData.append('serialNumbers', this.serialNumbers)
                formData.append('isSubscribe', this.isSubscribe)
                formData.append('replacement', this.replacement)
                formData.append('description', this.description)
                // formData.append('rmaId', this.rmaId)
                axios.post('/order/rma',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                ).then((response) => {
                    this.GET_LOADING_FROM_REQUEST(false);
                    // this.rmaId = response.data.id
                    this.$root.$emit('popupMessages', 'RMA aangemaakt')
                    this.clearForm()

                }).catch((e) => {
                    this.$root.$emit('popupMessages', getMessageError(e))

                    // this.$root.$emit('popupMessages', 'Error ' + e.response.data.message)
                    this.GET_LOADING_FROM_REQUEST(false);
                    console.log(e)
                })
            }
        },
        clearForm() {
            this.serialNumbers = ''
            this.orderInput = ''
            this.description = ''
            this.orderId = ''
            this.pic = ''
            this.textRma = ''
            this.files = []
            this.isSubscribe = false
            this.replacement = false
        }
    }
}
</script>

<style scoped>

</style>
