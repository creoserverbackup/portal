<template>
    <div class="unsplash-area">
        <ProfileSwitcher :inputGroupData="inputGroupData"/>
        <div class="unsplash-area__search">
            <input class="unsplash-area__input" type="search" name="unsplash-query" placeholder=""
                   :disabled="!inputGroupData.checked" v-on:keyup.enter="searchUnsplash(unsplashSearch, 1)"
                   v-model="unsplashSearch">
            <button class="unsplash-area__submit" type="button" v-on:click="searchUnsplash(unsplashSearch, 1)"
                    :disabled="!inputGroupData.checked"></button>
        </div>

        <div class="unsplash-area__data">
            <div class="unsplash-area__result"
                 :class="{'unsplash-area__result--disabled': !inputGroupData.checked, 'unsplash-area__result--invisible': !unsplashSlider.loaded}">

            <span class="helper-btn btn btn--primary unsplash-area__prev"
                  :class="{disabled: sliderPage === 1}"
                  v-on:click="prevSlides">
                <i>&lt;</i>
            </span>

                <div id="unsplash-swiper" class="unsplash-area__swiper-container swiper-container">
                    <div class="swiper-wrapper">
                        <div v-for="image in unsplashSlider.images" class="unsplash-area__item swiper-slide"
                             :class="{select: selectImage === image.id}" v-on:click="selectBackground(image)">
                            <img :src="image.urls.thumb" class="unsplash-area__img" alt="background">
                        </div>
                    </div>
                </div>

                <span class="helper-btn btn btn--primary unsplash-area__next"
                      :class="{disabled: unsplashSlider.page === unsplashSlider.totalPages}"
                      v-on:click="nextPage">
                    <i>&gt;</i>
                </span>

            </div>

            <!--      <div v-if="unsplashSlider.notFound" class="unsplash-area__err">{{ $t('UnsplahTextNothingFound') }}</div>-->
        </div>


        <input type="hidden" name="unsplash-image" v-model="selectImage">

    </div>
</template>

<script>
import Unsplash, {toJson} from 'unsplash-js';
import Swiper from 'swiper';
import {mapActions} from 'vuex';
import ProfileSwitcher from "./profile/ProfileSwitcher";
import 'swiper/css/swiper.min.css'

const unsplash = new Unsplash({
    accessKey: 'uBf8XNUIBMt79dxy3CxhUMdCDPnJOJ-7wdwKwShxREM',
    secret: 'eqeMQ1015MvzhxN0fRY0CLESmpINBg62-snFHlMFnig'
});

let swiperInstance;

export default {
    components: {
        ProfileSwitcher
    },
    props: {
        inputGroupData: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            unsplashSearch: '',
            unsplashSlider: {
                loaded: false,
                notFound: false,
                page: 1,
                total: 4,
                totalPages: 1,
                images: [{
                    urls: {
                        thumb: '/images/components/unsplash/no-image.png'
                    }
                }, {
                    urls: {
                        thumb: '/images/components/unsplash/no-image.png'
                    }
                }, {
                    urls: {
                        thumb: '/images/components/unsplash/no-image.png'
                    }
                }, {
                    urls: {
                        thumb: '/images/components/unsplash/no-image.png'
                    }
                }]
            },
            selectImage: '',
            sliderPage: 1
        }
    },
    mounted() {
        swiperInstance = new Swiper('#unsplash-swiper', {
            slidesPerView: 4,
            spaceBetween: 10,
            preloadImages: false,
            lazyLoading: true,
            lazyLoadingInPrevNext: true,

            navigation: {
                prevEl: '.unsplash-area__prev'
            },

            breakpoints: {
                480: {},
                576: {},
                768: {},
                992: {},
                1200: {}
            }
        });

        unsplash.photos.listPhotos(1, 4, 'latest')
            .then(toJson)
            .then(resp => {
                this.unsplashSlider.loaded = true;
                this.unsplashSlider.tagName = '';
                this.unsplashSlider.images = resp;

                swiperInstance.update();
            });
    },
    computed: {
        statusCheckbox: function () {
            return this.inputGroupData.checked;
        }
    },
    watch: {
        unsplashSearch: function (string) {
            if (string.trim().length > 2) {
                this.searchUnsplash(string, 1);
            }
        },
        statusCheckbox: function (status) {
            if (!status) {
                this.SET_BACKGROUND_FROM_UNSPLASH('');
            }
        }
    },
    methods: {
        ...mapActions([
            'SET_BACKGROUND_FROM_UNSPLASH'
        ]),
        searchUnsplash(tagName, pageNumber) {
            unsplash.search.photos(tagName, pageNumber, 4, {orientation: "landscape"})
                .then(toJson)
                .then(resp => {

                    if (resp.results.length) {
                        this.unsplashSlider.notFound = false;
                        this.unsplashSlider.page = pageNumber;
                        this.unsplashSlider.total = resp.total;
                        this.unsplashSlider.totalPages = resp.total_pages;

                        if (this.unsplashSlider.tagName === tagName) {
                            resp.results.forEach((slide, i) => {
                                this.unsplashSlider.images.push(slide);
                            });
                        } else {
                            this.unsplashSlider.tagName = tagName;
                            this.unsplashSlider.images = resp.results;
                        }

                        setTimeout(() => {
                            swiperInstance.update();
                            swiperInstance.slideTo((4 * pageNumber - 4), 600);
                        });
                    } else {
                        this.unsplashSlider.notFound = true;
                    }

                });
        },
        selectBackground(image) {
            this.selectImage = image.id;

            this.SET_BACKGROUND_FROM_UNSPLASH(image.urls.small);
        },
        prevSlides() {
            if (this.sliderPage === 1) return false;

            this.sliderPage--;
            swiperInstance.slideTo((4 * (this.sliderPage) - 4), 600);
        },
        nextPage() {
            this.sliderPage++;

            if (this.sliderPage > this.unsplashSlider.page) {
                this.searchUnsplash(this.unsplashSlider.tagName, this.sliderPage);
            } else {
                swiperInstance.slideTo((4 * (this.sliderPage) - 4), 600);
            }
        },
    }
}
</script>
