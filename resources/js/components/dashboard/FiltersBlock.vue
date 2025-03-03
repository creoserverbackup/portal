<template>
    <div v-if="filters" :class="{open: isOpen}" class="filter">

        <div class="filter__col">
            <div v-for="(filter, index) in filters" class="filter__field">
                <div class="radio-label">
                    <!--                    v-bind:checked="[filter.value == categoryId ? 'checked' : '']"-->
                    <input type="radio"
                           name="filters-category" :value="filter.value" v-model="categoryId"
                           v-on:change="checkCategory(filter)" class="radio-label__input" :id="'r' + index">
                    <label :for="'r' + index" class="radio-label__main">{{ filter.name }}</label>
                </div>
            </div>
        </div>

        <div class="filter__col" v-if="visibleFilters">
            <div class="filter__sections">
                <div v-for="(filter, index) in visibleFilters.blocks" class="filter__section"
                     :class="{'filter__section--rows': filter.bigColumn}" v-if="!filter.notVisible">
                    <!--                                        <span v-if="filter.name">{{ filter.name }}</span>-->
                    <div class="filter__field" v-for="(option, subindex) in filter.option">
                        <div class="checkbox-label" v-if="!filter.isRadio">

                            <input type="checkbox" v-model="option.checked" :id="'ff' + index + subindex"
                                   class="checkbox-label__input">
                            <label :for="'ff' + index + subindex" class="checkbox-label__main">{{ option.name }}</label>
                        </div>
                        <div class="radio-label" v-else>
                            <input type="radio" v-bind:name="filter.name" v-model="filter.value"
                                   :id="'ff' + index + subindex" :value="option.name"
                                   v-on:change="checkRadio(filter.categoryId, option)"
                                   class="radio-label__input">
                            <label :for="'ff' + index + subindex" class="radio-label__main">{{ option.name }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter__col">
<!--            <div class="filter__field">-->
<!--                <div class="checkbox-label">-->
<!--                    <input type="checkbox" v-model="isDiscount" class="checkbox-label__input" id="sf1">-->
<!--                    <label class="checkbox-label__main" for="sf1">text</label>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="filter__field filter__field&#45;&#45;rating">-->
<!--                <div class="checkbox-label">-->
<!--                    <input type="checkbox" v-model="setRating" class="checkbox-label__input" id="sf2">-->
<!--                    <label class="checkbox-label__main" for="sf2">text2</label>-->
<!--                </div>-->
<!--                <div :class="{active: setRating}" class="rating">-->
<!--                    <label class="rating__label">-->
<!--                        <input type="radio" value="5" name="star" class="rating__radio">-->
<!--                    </label>-->
<!--                    <label class="rating__label">-->
<!--                        <input type="radio" value="4" name="star" class="rating__radio">-->
<!--                    </label>-->
<!--                    <label class="rating__label selected">-->
<!--                        <input type="radio" value="3" name="star" class="rating__radio">-->
<!--                    </label>-->
<!--                    <label class="rating__label">-->
<!--                        <input type="radio" value="2" name="star" class="rating__radio">-->
<!--                    </label>-->
<!--                    <label class="rating__label">-->
<!--                        <input type="radio" value="1" name="star" class="rating__radio">-->
<!--                    </label>-->
<!--                </div>-->
<!--            </div>-->
            <div class="filter__prices">
                <input class="filter__price" type="number" placeholder="€ min." min="0" v-model.trim="minPrice">
                <input class="filter__price" type="number" placeholder="€ max." min="0" v-model.trim="maxPrice">
            </div>
            <div class="filter__btns">
                <button v-on:click="clearFilter" class="filter__btn btn btn--secondary btn--animated">verwijder
                    selectie
                </button>
                <button v-on:click="submit" class="filter__btn btn btn--secondary btn--animated">toepassen</button>
            </div>
        </div>
    </div>
</template>

<script>

import {default as filtersInFile} from '../../data/temp/filters'

export default {
    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
    },
    data() {
        return {
            clear: false,
            visibleFilters: '',
            minPrice: this.$route.query.minPrice,
            maxPrice: this.$route.query.maxPrice,
            filters: filtersInFile,
            isDiscount: false,
            setRating: false,
            categoryId: this.$route.query.categoryId,
            selectTypeProduct: '',
            stackRadio: [],
            serverDell: [
                {
                    name: 'G10 (Dell)',
                    checked: false
                },
                {
                    name: 'G11 (Dell)',
                    checked: false
                },
                {
                    name: 'G12 (Dell)',
                    checked: false
                },
                {
                    name: 'G13 (Dell)',
                    checked: false
                },
                {
                    name: 'G14 (Dell)',
                    checked: false
                },
            ],
            serverHP: [
                {
                    name: 'G6 (HP)',
                    checked: false
                },
                {
                    name: 'G7 (HP)',
                    checked: false
                },
                {
                    name: 'G8 (HP)',
                    checked: false
                },
                {
                    name: 'G9 (HP)',
                    checked: false
                },
                {
                    name: 'G10 (HP)',
                    checked: false
                },
            ],
        }
    },
    mounted() {
        this.checkOldSelectInFilter()
    },
    methods: {
        checkOldSelectInFilter() {
            this.filters.forEach(filter => {
                if (filter.value == this.$route.query.categoryId) {
                    filter.checked = true
                    this.checkCategory(filter)
                }
            });
        },
        clearFilter() {
            this.selectTypeProduct = ''
            this.clear = true
            this.visibleFilters.blocks.forEach(block => {
                if (block.notVisible === false && typeof block.categoryId == 'undefined') {
                    block.notVisible = true
                }
                block.value = '';
                block.option.forEach(option => {
                    option.checked = false;
                    option.value = '';
                });
            })

            this.categoryId = '';
            this.serverDell.forEach(value => {
                value.checked = false
            });

            this.serverHP.forEach(value => {
                value.checked = false
            });
        },
        checkCategory(selectFilter) {
            this.stackRadio = []
            this.selectTypeProduct = ''
            this.filters.forEach(filter => {
                filter.checked = (filter.value === selectFilter.value);
                if (filter.value === selectFilter.value) {
                    this.visibleFilters = filter
                    if (!this.clear) {
                        this.setVisibleFilters()
                    }
                }
            });
        },
        setVisibleFilters() {
            let answer = this.$route.query

            if (this.visibleFilters.categoryId == answer.categoryId) {
                for (let key in answer) {
                    this.visibleFilters.blocks.forEach(block => {
                        if (block.name == key) {
                            if (block.isRadio) {
                                block.value = answer[key]
                            } else {
                                block.option.forEach(option => {
                                    if (option.name == answer[key]) {
                                        option.checked = true;
                                    }
                                    if (Array.isArray(answer[key])) {
                                        option.checked = answer[key].includes(option.name)
                                    }
                                });
                            }
                        }
                    })
                }
            }
        },
        checkRadio(category, option) {
            let name = option.name
            if (category == 'undefined') {
                return false;
            }

            if (category === 1) {
                if (name === 'Dell') {
                    this.visibleFilters.blocks.forEach(block => {
                        if (block.name == 'version_type') {
                            block.option = this.serverDell
                            block.notVisible = false
                        }
                    })
                }
                if (name === 'HP') {
                    this.visibleFilters.blocks.forEach(block => {
                        if (block.name == 'version_type') {
                            block.option = this.serverHP
                            block.notVisible = false
                        }
                    })
                }
            }

            if (category === 27) {
                if (name == 'HP') {
                    this.visibleFilters.blocks.forEach(block => {
                        if (block.name == 'label HP') {
                            block.notVisible = false
                        }
                    })
                } else {
                    this.visibleFilters.blocks.forEach(block => {
                        if (block.name == 'label HP') {
                            block.notVisible = true
                        }
                    })
                }
            }

            if (category === 9) {
                if (typeof option.typeProduct !== undefined) {
                    this.selectTypeProduct = option.typeProduct
                }

                this.visibleFilters.blocks.forEach(block => {
                    if (block.type == name || block.name == 'parts') {
                        block.notVisible = false
                    } else {
                        block.notVisible = true
                    }
                })
            }
        },
        submit() {
            let options = ''
            let redirect = false;

            if (!(typeof (this.minPrice) === undefined || this.minPrice === undefined || this.minPrice === '')) {
                options += 'minPrice=' + this.minPrice + '&'
                redirect = true
            }

            if (!(typeof (this.maxPrice) === undefined || this.maxPrice === undefined || this.maxPrice === '')) {
                options += 'maxPrice=' + this.maxPrice + '&'
                redirect = true
            }

            let wordSearch = typeof (this.$route.query.search) === undefined || this.$route.query.search === undefined ? '' : this.$route.query.search

            this.filters.forEach(filter => {
                if (filter.checked) {
                    options += 'category_id=' + filter.categoryId + '&'
                    this.visibleFilters.blocks.forEach(value => {
                        if (value.isRadio && value.value !== '' && value.value !== undefined) {
                            options += encodeURI(value.name) + '=' + encodeURI(value.value) + '&'
                        } else {
                            value.option.forEach(feature => {
                                if (feature.checked) {
                                    options += encodeURI(value.name) + '=' + encodeURI(feature.name) + '&'
                                }
                            })
                        }
                    });
                    redirect = true
                }
            });

            if (redirect) {
                if (this.selectTypeProduct !== '') {
                    options += 'type_product=' + this.selectTypeProduct + '&'
                }
                window.location.href = process.env.MIX_WEBSHOP_URL + "/accounts/#/search?search=" + wordSearch + '&' + options
            }
        }
    }
}
</script>

