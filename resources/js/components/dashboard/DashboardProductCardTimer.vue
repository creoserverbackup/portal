<template>
    <div class="product__stick-line-timer">
        Tijd over <span>{{ weekday }}</span>
    </div>

</template>

<script>
export default {
    name: "DashboardProductCardTimer",
    props: {
        product: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            now: Math.trunc((new Date()).getTime() / 1000),
            reload: false,
        }
    },
    mounted() {
        setInterval(() => {
            this.now = Math.trunc((new Date()).getTime() / 1000)
        }, 1000);

        this.reload = this.product.finishSale > this.now
    },
    computed: {
        modifiedDate() {
            return Math.trunc(Date.parse(new Date(this.product.finishSale * 1000)) / 1000)
        },
        seconds() {
            return (this.modifiedDate - this.now) % 60
        },
        minutes() {
            return Math.trunc((this.modifiedDate - this.now) / 60) % 60
        },
        hours() {
            return Math.trunc((this.modifiedDate - this.now) / 60 / 60) % 24;
        },
        days() {
            return Math.trunc((this.modifiedDate - this.now) / 60 / 60 / 24);
        },
        weekday() {
            let title = ''

            if (this.days > 0) {
                title = this.days + 'd:'
            }

            if (this.hours > 0) {
                title = title + this.hours + 'h:'
            }

            title = title + this.minutes + 'm:'
            title = title + this.seconds + 's'

            if ((this.modifiedDate - this.now) < 0 && this.reload) {
                location.reload();
            }

            return title
        }
    }
}
</script>