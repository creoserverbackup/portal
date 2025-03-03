<template>
    <div class="timetable">

        <div class="timetable__heading">{{ setTitle }}</div>

        <dl class="timetable__datalist">
            <div class="timetable__day" v-for="(day, index) in workTime"
                 :class="{active: getDayOfWeekNow(index), online: getStatusSupport(day.online)}">
                <dt class="timetable__description-list">{{ getDayWeek(++index) }}</dt>
                <dd class="timetable__data-definition">
                    <span>{{ getTextOnline(day.online) }}</span>
                </dd>
            </div>

        </dl>

    </div>
</template>

<script>
import {DateTime} from 'luxon'

export default {
    data() {
        return {
            now: DateTime.local().setZone('Europe/Amsterdam'),
            week: [
                {
                    name: this.$t('TimeTableMonday'),
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
                    name: this.$t('TimeTableTuesday'),
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
                    name: this.$t('TimeTableWednesday'),
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
                    name: this.$t('TimeTableThursday'),
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
                    name: this.$t('TimeTableFriday'),
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
                    name: this.$t('TimeTableSaturday'),
                    online: this.$t('TimeTableSupportAppointment')
                },
                {
                    name: this.$t('TimeTableSunday'),
                    online: this.$t('TimeTableSupportClosed')
                }
            ],
            workTime: {},
            saturday: {},
            sunday: {},
        }
    },
    computed: {
        setTitle() {
            const thisDayOfWeek = DateTime.local().weekday
            const thisDayCurrent = this.week[thisDayOfWeek - 1]

            let title

            if (this.getStatusSupport(thisDayCurrent.online)) {
                title = this.$t('TimeTableTitleOnline')
            } else {
                title = this.$t('TimeTableTitleOffline')
            }

            return title
        }
    },
    methods: {
        getTextOnline(online) {
            if (typeof online === 'string') {
                return online
            } else {
                let time = DateTime.local()

                return `${time.set({
                    hour: online.start.hours,
                    minute: online.start.minutes
                }).toFormat('H:mm')} - ${time.set({
                    hour: online.finish.hours,
                    minute: online.finish.minutes
                }).toFormat('H:mm')}`
            }
        },
        getDayOfWeekNow(day) {
            return (DateTime.local().weekday - 1 === day)
        },
        getStatusSupport(online) {
            let status

            if (typeof online === 'string') {
                status = false
            } else {
                status = (this.now.toMillis() >= this.now.set({
                            hour: online.start.hours,
                            minutes: online.start.minutes
                        }).toMillis() &&
                        this.now.toMillis() < this.now.set({
                            hour: online.finish.hours,
                            minutes: online.finish.minutes
                        }).toMillis())
            }

            return status
        },
        getWorkTime() {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/work-time`).then((response) => {

                this.workTime = response.data.workTime
                this.saturday = response.data.saturday
                this.sunday = response.data.sunday
            })
        },
        getDayWeek(day) {
            let arr = new Map([
                [1, 'Maandag'],
                [2, 'Dinsdag'],
                [3, 'Woensdag'],
                [4, 'Donderdag'],
                [5, 'Vrijdag'],
                [6, 'Saturday'],
                [7, 'Sunday'],
            ])
            return arr.get(day)
        },
    },
    mounted() {
        setInterval(() => {
            this.now = DateTime.local().setZone('Europe/Amsterdam')
        }, 1000)
        this.getWorkTime()
    }
}
</script>
