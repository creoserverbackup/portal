<template>
    <div class="container-fluid">

        <selectBackground/>

        <div class="promo-area">
            <div class="promo-area__img-wrap">
                <!-- <img class="promo-area__img img-responsive" src="/images/promo-1.png" alt=""> -->
                <img class="promo-area__img img-responsive" src="/images/endsetupimage.svg" alt="" width="180"
                     height="180">
                <h1 class="promo-area__heading" v-text="$t('StepFourHeading1')"></h1>

            </div>
            <div class="promo-area__invite" v-html="$t('StepFourInvite')"></div>
            <p class="promo-area__desc-important" v-html="$t('StepFourTextImportant')"></p>
            <p class="promo-area__desc" v-html="$t('StepFourText1')"></p>
            <p class="promo-area__desc" v-html="$t('StepFourText2')"></p>

            <div class="promo-area__creo-team">
                <p class="promo-area__desc" v-html="$t('StepFourHeading2')"></p>

                <ul class="promo-area__creo-team-list">
                    <li class="promo-area__creo-team-item" v-for="people in team">
                        <figure class="promo-area__creo-member">
                            <a href="#" class="promo-area__creo-member-link">
                                <div class="promo-area__creo-member-img-wrap">
                                    <img class="promo-area__creo-member-img"
                                         :src="`/images/${people.img}`"
                                         :alt="people.name">
                                </div>
                                <figcaption class="promo-area__creo-member-details">
                                    <div class="promo-area__creo-member-name">{{ people.name }}</div>
                                    <div class="promo-area__creo-member-position">{{ people.position }}</div>
                                </figcaption>
                            </a>
                        </figure>

                    </li>
                </ul>
            </div>

            <small class="promo-area__desc-summary" v-html="$t('StepFourCloseButton')"></small>
        </div>

    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import {setTheme} from '../../plugins/dark-mode';

export default {
    data() {
        return {
            team: [
                {
                    img: 'team-1.jpg',
                    name: 'Oleg',
                    position: 'Manager'
                },
                {
                    img: 'team-2.jpg',
                    name: 'Omid',
                    position: 'Developer'
                },
                {
                    img: 'team-3.jpg',
                    name: 'Lena',
                    position: 'Designer'
                }
            ]
        }
    },
    computed: {
        ...mapGetters([
            'GET_THEME'
        ])
    },
    mounted() {
        setTheme(this.GET_THEME);
    }
}
</script>
