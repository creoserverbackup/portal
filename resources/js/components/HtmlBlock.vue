<template>
    <div class="html-block" v-html="html">
    </div>
</template>

<script>

export default {
    name: "HtmlBlock",

    props: {
        hook: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            html: ''
        }
    },

    async created() {
        await this.loadHtmlBlock()
    },

    methods: {
        async loadHtmlBlock() {
            try {
                const {data} = await axios.get(`${process.env.MIX_APP_URL}/html-blocks/${this.hook}?frame=${this.$route.query.frame}`)
                this.html = data.html
            } catch (e) {
                console.log(e)
            }
        }
    }
}
</script>

<style scoped>

</style>
