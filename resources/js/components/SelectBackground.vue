<template>
    <div>
<!--        <div class="selected-background-dashboard" v-if="secondBackground" :class="{'selected-background-dashboard&#45;&#45;sorting': hasSorting}">-->
<!--&lt;!&ndash;            <div class="selected-background-dashboard__main"&ndash;&gt;-->
<!--&lt;!&ndash;                 v-if="background"&ndash;&gt;-->
<!--&lt;!&ndash;                 :style="getStyle"></div>&ndash;&gt;-->
<!--        </div>-->
        <div class="selected-background"
             v-if="background"
             :style="getStyle"></div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        props: {
            inputBackground: {
                type: String,
                required: false
            },
            secondBackground: {
                type: Boolean,
                required: false
            },
            hasSorting: {
                type: Boolean,
                required: false
            }
        },
        data() {
            return {
                background: (this.inputBackground === undefined ? '' : this.inputBackground)
            }
        },
        computed: {
            ...mapGetters([
                'GET_BACKGROUND'
            ]),
            changeBg: function() {
                return this.GET_BACKGROUND;
            },
            getStyle: function() {
                return `background-image: url("${this.background}")`;
            }
        },
        mounted() {
            if (this.background.length) return;

            this.background = this.GET_BACKGROUND;

        },
        watch: {
            changeBg: function(image) {
                this.background = image;
            }
        }
    }
</script>
