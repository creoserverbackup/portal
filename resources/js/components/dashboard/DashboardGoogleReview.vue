<template>
    <div class="contact-center__detail">
        <div class="contact-center__detail-heading">{{ $t('contactCenterCaptionReview') }}</div>
        <div class="elfsight-app-5a166d4a-f70a-45f6-b821-b02c9c05c397"></div>
    </div>
</template>

<script>


import {loadJsFile} from "../../utils";

export default {
    name: "DashboardGoogleReview",

    mounted() {
        loadJsFile('https://apps.elfsight.com/p/platform.js', 'widget-google-review', () => {
        }, true)
    }
}
</script>


<style lang="scss">
@import "resources/sass/abstracts/variables";

.contact-center__detail {
    > div:last-child {
        height: 100%;
        width: 100%;

        > div {
            height: 100%;
            width: 100%;

            > div {
                height: 100%;
                width: 100%;
            }
        }
    }
}


.kzANES {
    //background-color: $secondary-color;
}

.fwgulZ {
    border: 1px solid black;
    padding: initial;
    //padding: 70px 70px !important;
    margin: 0px !important;
    height: 100%;
    width: 100%;
}

.cbcICF {
    display: block !important;
    width: 100%;
    justify-content: left !important;
}

.dGkqPI {
    color: black !important;
}

.dfUaPj {
    color: black !important;
}

</style>