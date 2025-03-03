<template>
    <div>
        <vue-html2pdf
                :show-layout="false"
                :preview-modal="true"
                :paginate-elements-by-height="1400"
                filename="hee hee"
                :pdf-quality="2"
                pdf-format="a4"
                pdf-orientation="portrait"
                pdf-content-width="100px"
                ref="html2Pdf"
        >
            <section slot="pdf-content">
                <h1>1111</h1>
            </section>
        </vue-html2pdf>

        <button class="btn btn--primary" v-on:click="generateReport">generate</button>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';

    export default {
        mounted() {
            this.GET_LOADING_FROM_REQUEST(false);
        },
        methods: {
            ...mapActions([
                'GET_LOADING_FROM_REQUEST'
            ]),
            generateReport () {
                this.$refs.html2Pdf.generatePdf()
            }
        }
    }
</script>