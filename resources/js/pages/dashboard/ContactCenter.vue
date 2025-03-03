<template>
    <div class="contact-center">
        <header class="contact-center__header">
            <h1 class="contact-center__heading support-center__heading">
                {{ $t('contactCenterHeading') }}
                <small class="support-center__heading-desc">{{ $t('contactCenterSubheading') }}</small>
            </h1>
            <div class="contact-center__upcoming">
                <div class="contact-center__upcoming-caption">{{ $t('contactCenterUpcoming') }}</div>
                <time class="contact-center__upcoming-time"
                      :class="{'c-red': !isOnline}">&nbsp;{{ setTimeOnline }}</time>
            </div>
        </header>
        <section class="contact-center__helpers">
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <article class="contact-center__helper department-area">
                        <div class="department-area__heading">FAQ</div>
                        <span class="department-area__sticker">Tip!</span>
                        <p class="department-area__desc">Zoek  jouw technische vraag binnen onze FAQ</p>
                        <div class="department-area__icon">
                            <i class="icon-search"></i>
                        </div>
                        <router-link to="/faq" class="w-100 btn btn--primary btn--animated">FAQ</router-link>
                    </article>
                </div>
                <div class="col-sm-6 col-md-3">
                    <article class="contact-center__helper department-area">
                        <div class="department-area__heading">RMA Ticket</div>
                        <span class="department-area__sticker department-area__sticker--warning">Matig</span>
                        <p class="department-area__desc">Maak een supportticket aan voor jouw probleem met een product.</p>
                        <div class="department-area__icon">
                            <i class="icon-validating-ticket"></i>
                        </div>
                        <router-link to="/rma" class="w-100 btn btn--primary btn--animated">RMA aanmaken</router-link>
                    </article>
                </div>

                <div class="col-sm-6 col-md-3">
                    <article class="contact-center__helper department-area">
                        <div class="department-area__heading">Live chat</div>
                        <span :class="{'department-area__sticker--alert': isOnline}" class="department-area__sticker">
                            {{ isOnline ? 'Druk' : 'Tip!' }}
                        </span>
                        <p class="department-area__desc">Voor al je snelle vragen over producten, samenstellen, of de Customer Portal.</p>
                        <div class="department-area__icon">
                            <i class="icon-chat"></i>
                        </div>
                        <router-link :event="isOnline ? 'click' : ''" to="/live-support-chat"
                                     @click.native="checkButtonOnline" :class="{'disabled-gray': !isOnline}"
                                     class="w-100 btn btn--primary btn--animated">
                            Naar de chat
                        </router-link>
                    </article>
                </div>

                <div class="col-sm-6 col-md-3">
                    <article class="contact-center__helper department-area">
                        <div class="department-area__heading">Telefoon Support</div>
                        <span :class="{'department-area__sticker--success': isOnline}" class="department-area__sticker">
                            {{ isOnline ? 'Rustig' : 'Tip!' }}
                        </span>
                        <p class="department-area__desc">Heb je vragen over het een offerte, de specifieke producten of facturen? Bel dan met onze salesafdeling.</p>
                        <div class="department-area__icon">
                            <i class="icon-mobile-phone"></i>
                        </div>
                        <a href="tel:0858640777" @click="checkButtonOnline" :class="{'disabled-gray': !isOnline}"
                           class="w-100 btn btn--primary btn--animated">Creoserver bellen</a>
                    </article>
                </div>
            </div>
        </section>

        <section class="contact-center__details">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="contact-center__detail">
                        <div class="contact-center__detail-heading">{{ $t('contactCenterCaptionEmail') }}</div>
                        <nav class="contact-center__detail-nav">
                            <ul class="contact-center__detail-list">
                                <li class="contact-center__detail-item">
                                    <router-link to='/request'
                                                 class="btn btn--primary btn--animated contact-center__detail-link">
                                        {{ $t('contactCenterNavQuote') }}
                                    </router-link>
                                </li>
                                <li class="contact-center__detail-item">
                                    <router-link to='/request'
                                                 class="btn btn--primary btn--animated contact-center__detail-link">
                                        {{ $t('contactCenterNavResell') }}
                                    </router-link>
                                </li>
                                <li class="contact-center__detail-item">
                                    <router-link to='/request'
                                                 class="btn btn--primary btn--animated contact-center__detail-link">
                                        {{ $t('contactCenterNavProduct') }}
                                    </router-link>
                                </li>
                                <li class="contact-center__detail-item">
                                    <router-link :event="isOnline ? 'click' : ''" to="/live-support-chat"
                                                 @click.native="checkButtonOnline"
                                                 :class="{'disabled-gray': !isOnline}"
                                                 class="btn btn--primary btn--animated contact-center__detail-link">
                                        {{ $t('contactCenterNavCustomer') }}
                                    </router-link>
                                </li>
                                <li class="contact-center__detail-item">
                                    <router-link to='/request'
                                                 class="btn btn--primary btn--animated contact-center__detail-link">
                                        {{ $t('contactCenterNavComplain') }}
                                    </router-link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <dashboard-google-review/>
                </div>

                <div class="col-md-4">
                    <div class="contact-center__detail">
                        <div class="contact-center__detail-heading">{{ $t('contactCenterCaptionAddress') }}</div>
                        <address class="contact-center__detail-address">
                            <p>
                                <a href="https://www.google.ru/maps/search/Creo+Server/@52.4891169,6.1393652,17z/data=!3m1!4b1"
                                   target="_blank">
                                    <span>CreoServer</span>
                                    <span>Paxtonstraat 23</span>
                                    <span>8013 RP Zwolle</span>
                                    <span>Nederland</span>
                                </a>
                            </p>

                            <div>Telefoon: <a href="tel:+0858640777">085 864 0777</a></div>
                            <div>
                                <span>Sales Mail: <a href="mailto:sales@creoserver.com">sales@creoserver.com</a></span>
                                <span>Tech. Mail: <a href="mailto:info@creoserver.com">info@creoserver.com</a></span>
                            </div>
                        </address>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import {mapActions} from 'vuex';
import {DateTime} from "luxon";
import DashboardGoogleReview from "../../components/dashboard/DashboardGoogleReview";

export default {
    components: {DashboardGoogleReview},
    data() {
        return {
            now: DateTime.local().setZone('Europe/Amsterdam'),
            week: [
                {
                    online: {
                        start: {
                            hours: 9,
                            minutes: 0
                        },
                        finish: {
                            hours: 17,
                            minutes: 0
                        }
                    }
                },
                {
                    online: {
                        start: {
                            hours: 9,
                            minutes: 0
                        },
                        finish: {
                            hours: 17,
                            minutes: 0
                        }
                    }
                },
                {
                    online: {
                        start: {
                            hours: 9,
                            minutes: 0
                        },
                        finish: {
                            hours: 17,
                            minutes: 0
                        }
                    }
                },
                {
                    online: {
                        start: {
                            hours: 9,
                            minutes: 0
                        },
                        finish: {
                            hours: 17,
                            minutes: 0
                        }
                    }
                },
                {
                    online: {
                        start: {
                            hours: 9,
                            minutes: 0
                        },
                        finish: {
                            hours: 17,
                            minutes: 0
                        }
                    }
                },
                {
                    online: 'offline'
                },
                {
                    online: 'offline'
                }
            ],
            isOnline: null
        }
    },
    mounted() {
        this.GET_LOADING_FROM_REQUEST(false);

        setInterval(() => {
            this.now = DateTime.local().setZone('Europe/Amsterdam');
        }, 1000);

    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),

        getStatusSupport(online) {
            let status;

            if (typeof online === "string") {
                status = false;
            } else {
                status = (
                    this.now.toMillis() >= this.now.set({
                        hour: online.start.hours,
                        minutes: online.start.minutes
                    }).toMillis()
                    &&
                    this.now.toMillis() < this.now.set({
                        hour: online.finish.hours,
                        minutes: online.finish.minutes
                    }).toMillis()
                );
            }

            this.isOnline = !!status;

            return status;
        },

        checkButtonOnline(e) {
            if (!this.isOnline) {
                e.preventDefault();
            }
        }
    },
    computed: {
        setTimeOnline() {
            const thisDayOfWeek = DateTime.local().weekday;
            const thisDayCurrent = this.week[thisDayOfWeek - 1];

            let title;

            if (this.getStatusSupport(thisDayCurrent.online)) {
                let startTime = DateTime.local().set({
                    hour: thisDayCurrent.online.finish.hours,
                    minute: thisDayCurrent.online.finish.minutes,
                    second: 0
                });

                title = DateTime.fromMillis(startTime.diff(this.now).toObject().milliseconds).toFormat("HH'h':mm'm':ss's'");
            } else {
                title = 'Gesloten';
            }

            return title;
        }
    }
}
</script>
