<template>
    <div>
        <div class="select-form mb-5">
            <div class="select-form__field">
                <label class="select-form__label">{{ $t('RequestOfferLabel1') }}</label>
                <div class="select-form__select-arrow">
                    <v-select
                            required
                            v-model="categorySelect"
                            :options="categories">
                    </v-select>
                </div>
            </div>
            <div class="select-form__field">
                <label class="select-form__label">{{ $t('RequestOfferLabel2') }}</label>
                <input type="text" v-model="title">
            </div>
        </div>

        <textarea v-model="description" cols="30" rows="17"></textarea>

        <!--            <editor-->
        <!--                v-model="description"-->
        <!--                api-key="no-api-key"-->
        <!--                :init="{-->
        <!--                height: 500,-->
        <!--                menubar: false,-->
        <!--                plugins: [-->
        <!--                    'autolink link image tinydrive',-->
        <!--                ],-->
        <!--                //toolbar_mode: 'scrolling',-->
        <!--                toolbar:-->
        <!--                    'fontselect | fontsizeselect | bold italic underline | forecolor |\-->
        <!--                    link ',-->
        <!--                images_upload_url: 'postAcceptor.php',-->
        <!--                //content_css: 'tinymce-iframe-night-mode.css',-->
        <!--            }"/>-->
        <div class="success">
            <button class="btn btn--secondary" @click.prevent="saveRequest">{{ $t('RequestOfferSaveRequest') }}
            </button>
        </div>
    </div>
</template>

<script>

import Vue from 'vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css';

export default {
    name: "requestForm",
    props: {
        categories: {
            type: Array,
        },
    },
    data() {
        return {
            categorySelect: null,
            title: '',
            description: '',
            requests: [],
        }
    },
    methods: {
        saveRequest() {
            if ((typeof this.categorySelect === 'undefined' || this.categorySelect === '' || this.categorySelect == null)
                || (typeof this.description === 'undefined' || this.description === '')
                || (typeof this.title === 'undefined' || this.title === '')
            ) {
                this.$root.$emit('popupMessages', 'Fill the form')
            } else {
                axios.post('/request/request', {
                    category: this.categorySelect.categoryId,
                    title: this.title,
                    description: this.description,
                }).then((response) => {
                    this.goToUserMessage(response.data.id)
                })

            }
        },
        getLink(id) {
            return {
                name: 'request',
                params: {
                    id: id
                }
            }
        },
        goToUserMessage(requestId) {
            return this.$router.push(this.getLink(requestId))
        },
    }
}
</script>
