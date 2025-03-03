<template>
    <div class="row faq-page">
        <div class="col-11 m-0 faq-page__main-block">
                <div class="mb-5 fs-30 c-primary">CreoServer FAQ</div>
            <div class="row m-0 p-0">
                <div class="faq-page__types col-12 col-xl-3 bg-secondary faq-page__col-2xl-2 fs-20 c-white p-5">
                    <div class="faq-page__type c-pointer d-flex" v-for="select in typeQuestion">
                        <span class="mr-2" v-if="select.id === selectIdType">&#x3E</span>
                        <div class="" v-on:click="selectTypeFAQ(select.id)">{{ select.name }}</div>
                    </div>
                </div>
                <div class="col-12 col-xl-9 faq-page__col-2xl-10">
                    <div class="position-relative">
                        <input type="text" class="faq-page__search-input" @keyup.enter="search"
                               v-model.trim="searchFaq"
                               @input="search"
                               placeholder="search FAQ">
                        <button class="search-form__submit top-0" v-on:click="search()">
                            <i class="icon-search"></i>
                        </button>
                    </div>
                    <faq-accordion v-if="questionAnswer"
                                   v-for="value in questionAnswer"
                                   v-bind:key="value.id"
                                   :answer="value.answer"
                                   :question="value.question"
                    >
                    </faq-accordion>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import FaqAccordion from "../../components/faq/faqAccordion";

export default {
    components: {
        FaqAccordion
    },
    data() {
        return {
            selectIdType: 1,
            support: false,
            questionAnswer: [],
            message: '',
            searchFaq: '',
            typeQuestion: {},

            typeSave: '',
            answerSave: '',
            questionSave: '',
            idSave: '',
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false)
        this.getFAQType()
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        selectTypeFAQ(id) {
            this.selectIdType = id
            this.typeQuestion.forEach((item) => {
                if (item.id == id) {
                    this.questionAnswer = item.options
                }
            })
        },
        getFAQType() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/faq`).then((response) => {
                this.typeQuestion = response.data.data
                this.selectTypeFAQ(1)
            })
        },
        search() {
            if (this.searchFaq.length > 0) {
                axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/faq/type/${this.searchFaq}`).then((response) => {
                    this.questionAnswer = response.data
                })
            } else {
                this.selectTypeFAQ(this.selectIdType)
            }
        },
    }
}
</script>

<style lang="scss">
@import "resources/sass/abstracts/variables";

.faq-page {
    &__main-block {
        max-width: 1140px;
        flex: 90%;
    }

    &__types {
        min-height: 500px;

        @media (max-width: $xlg) {
            min-height: auto;
            margin-bottom: 20px;
        }
    }

    &__type {
        :hover {
            border-bottom: white;
            text-decoration: underline;
            content: '\x3E';
        }
    }

    &__search-input {
        color: $colorSearchInput;
        border-color: $secondary-color;
        &:focus, &:active, &:hover {
            border-color: $secondary-color;
        }
    }

    & .search-form__submit {
        height: 100%;
        border-color: $secondary-color;
    }


    //@media(min-width: 1400px) {
    //    &__col {
    //        &--2xl-2 {
    //            flex: 0 0 16.66666667%;
    //            max-width: 16.66666667%;
    //        }
    //
    //        &--2xl-10 {
    //            flex: 0 0 83.33333333%;
    //            max-width: 83.33333333%;
    //        }
    //    }
    //}
}

//.block-support {
//    margin-top: 20px;
//
//    & button {
//        margin-bottom: 0;
//    }
//}


</style>
