<template>
    <div class="faq-data-accordion col-12 p-0 m-0 mt-3" :class="{active:isAccordion}">
        <div class="faq-data-accordion__question" @click="showOrHideAccordion()">
            <label class="faq-data-accordion__question-q">Q.</label>
            <label class="fs-14 w-100" v-html="question"></label>
            <div class="faq-data-accordion__question-icon"></div>
        </div>
        <div class="faq-data-accordion__answer">
            <label class="mr-15 fs-35">A.</label>
            <span class="fs-14" v-html="answer"></span>
        </div>
        <slot/>
    </div>
</template>

<script>
export default {
    name: "faqAccordion",
    data() {
        return {
            isAccordion: false,
        }
    },
    props: ['answer', 'question'],
    methods: {
        showOrHideAccordion() {
            this.isAccordion = !this.isAccordion;
        },
    },
}
</script>

<style lang="scss">
@import "resources/sass/abstracts/variables";

.faq-data-accordion {

    &.active {
        .faq-data-accordion__answer {
            display: flex;
        }

        .faq-data-accordion__question-icon:after {
            content: '-';
        }
    }

    &__question {
        background-color: #f0e9e9;
        display: flex;
        align-items: center;
        border-left: 5px solid $secondary-color;
        color: $secondary-color;
        padding: 0 10px;
        justify-content: space-between;

        &-q {
            font-size: 35px;
            margin-bottom: 0;
            margin-right: 15px;
        }

        &-icon {
            cursor: pointer;

            &:after {
                content: '+';
                font-size: 30px;
                font-weight: bold;
            }
        }
    }

    &__answer {
        border-left: 5px solid #959da5;
        padding-left: 10px;
        color: #959da5;
        display: none;
    }
}

</style>