<template>
    <div class="pagination-block">
        <div class="pagination-block-left">
            <div class="pagination-block-left-title">{{ $t('selectCountProductOnPage') }}</div>
            <select v-model="countProduct" v-on:change="changeCount()">
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
                <option value="240">240</option>
                <option value="500">500</option>
                <option value="1000">Alles</option>
            </select>
        </div>
        <div class="pagination-block-center">
            <div class="pagination-block-center-title">{{ $t('totalProductOnPage') }}
                <span class="pagination-block-left-result">{{ totalProductInSite }}</span></div>
        </div>
        <div class="pagination-block-right">
            <nav class="pagination-component">
                <ul class="pagination jcc">
                    <li class="page-item show-more" v-if="(pagination.current_page > 2)">
                        <a class="page-link" @click.prevent="changePage(1)"><span class="revert-arrow">&lt;</span>
                            Vorige</a>
                    </li>
                    <li class="page-item" v-for="(page,index) in pages" :key="page" @click.prevent="changePage(page)"
                        :class="isCurrentPage(page) ? 'active' : ''">
                        <a style="cursor:pointer" class="page-link">{{ page }}
                            <span v-if="isCurrentPage(page)" class="sr-only"></span>
                        </a>
                    </li>

                    <li class="page-item more-item" v-if="!(pagination.current_page >= pagination.lastPage)">
                        <a class="page-link" @click.prevent="changePage(pagination.current_page + 5)"><i>...</i></a>
                    </li>
                    <li class="page-item show-more" v-if="!(pagination.current_page >= pagination.lastPage)">
                        <a class="page-link" @click.prevent="changePage(pagination.lastPage)">Volgende
                            <span class="more-arrow">&gt;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pagination: {},
        offset: '',
        countProductOnPage: '',
        totalProductOnPage: '',
        totalProductInSite: '',
    },
    data() {
        return {
            countProduct: 30
        }
    },
    methods: {
        isCurrentPage(page) {
            return this.pagination.current_page === page
        },
        changePage(page) {
            if (page > this.pagination.lastPage) {
                page = this.pagination.lastPage;
            }
            this.pagination.current_page = page;
            this.$emit('paginate');
            this.$root.$emit('paginate');
        },
        changeCount() {
            this.$emit('changeCountProducts', this.countProduct);
        }
    },
    watch: {
        countProductOnPage: {
            handler() {
                this.countProduct = this.countProductOnPage
            }, deep: true
        }
    },
    computed: {
        pages() {
            let pages = []

            let from = this.pagination.current_page - Math.floor(this.offset / 2)

            if (from < 1) {
                from = 1
            }

            let to = from + this.offset - 1

            if (to > this.pagination.lastPage) {
                to = this.pagination.lastPage
            }

            while (from <= to) {
                pages.push(from)
                from++
            }

            return pages
        }
    }
}
</script>
