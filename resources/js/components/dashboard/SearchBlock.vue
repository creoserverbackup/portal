<template>
    <div class="dashboard-roof__search-form search-form ml-0">
        <div class="search-form__field">
            <input type="text" class="search-form__input" @keyup.enter="trigger" v-model="searchWord"
                   v-on:input="search('input')"
                   v-on:change="search('input')"
                   name="search-query" placeholder="Zoek een specifiek product...">
            <button class="search-form__submit" ref="sendWord" v-on:click="search('button')">
                <i class="icon-search"></i>
            </button>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            searchWord: this.$route.query.search,
            time: null,
        }
    },
    mounted() {
    },
    methods: {
        search(type) {
            if (typeof this.searchWord !== 'undefined') {
                if (this.isPageSearch) {
                    this.searchInput()
                } else if (type == 'button') {
                    window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/search?search=" + this.searchWord;
                } else {
                    this.searchInput()
                }
            }
        },
        trigger() {
            this.$refs.sendWord.click()
        },
        searchInput() {
            clearTimeout(this.time);
            this.time = setTimeout(() => this.startSearch(), 400);
        },
        startSearch() {
            this.$root.$emit('startSearch', this.searchWord)
        },
    },
    computed: {
        isPageSearch() {
            return this.$route.path == '/search' || this.$route.path == '/catalog'
            || this.$route.path == '/frame/search' || this.$route.path == '/frame/catalog'
        }
    },
    watch: {
        '$route.path': function (route) {
            if (route != '/search') {
                this.searchWord = ''
            }
        }

    }
}
</script>
