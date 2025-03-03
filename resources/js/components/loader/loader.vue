<template>
    <div class="canvas-loader">
        <div class="loader">
            <div class="atom-spinner">
                <div class="spinner-inner">
                    <div class="spinner-line"></div>
                    <div class="spinner-line"></div>
                    <div class="spinner-line"></div>
                    <div class="spinner-circle"></div>
                </div>
            </div>
            <div class="ta-c" id="loader-msg" style="white-space: pre;">{{ msg }}</div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "loader",
    data() {
        return {
            loadingMsg: [],
            msg: "L O A D I N G . . .",
            isLoading: false,
            opacity: .9
        }
    },
};
</script>

<style lang="scss">

@import "resources/sass/abstracts/variables";

.canvas-loader {
    //position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 150;
    -webkit-animation: fadein .5s;
    /* Safari, Chrome and Opera > 12.1 */
    /* Firefox < 16 */
    /* Internet Explorer */
    /* Opera < 12.1 */
    animation: fadein .5s;
    position: relative;
}

.canvas-loader .loader {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}

.canvas-loader .loader #loader-msg {
    font-size: 18px;
    font-family: "Roboto", sans-serif;
    color: #777;
    font-weight: normal;
    padding-top: 10px;
    text-align: center;
}

.canvas-loader .loader .atom-spinner, .canvas-loader .loader .atom-spinner * {
    box-sizing: border-box;
}

.canvas-loader .loader .atom-spinner {
    height: 80px;
    width: 80px;
    overflow: hidden;
    margin: auto;
}

.canvas-loader .loader .atom-spinner .spinner-inner {
    position: relative;
    display: block;
    height: 100%;
    width: 100%;
}

.canvas-loader .loader .atom-spinner .spinner-circle {
    display: block;
    position: absolute;
    width: calc(80px * 0.18);
    height: calc(80px * 0.18);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.canvas-loader .loader .atom-spinner .spinner-line {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    border-left-width: calc(80px / 25);
    border-top-width: calc(80px / 25);
    border-left-style: solid;
    border-top-style: solid;
    border-top-color: transparent;
}

.canvas-loader .loader .atom-spinner .spinner-line:nth-child(1) {
    -webkit-animation: atom-spinner-animation-1 1s linear infinite;
    animation: atom-spinner-animation-1 1s linear infinite;
    transform: rotateZ(120deg) rotateX(66deg) rotateZ(0deg);
}

.canvas-loader .loader .atom-spinner .spinner-line:nth-child(2) {
    -webkit-animation: atom-spinner-animation-2 1s linear infinite;
    animation: atom-spinner-animation-2 1s linear infinite;
    transform: rotateZ(240deg) rotateX(66deg) rotateZ(0deg);
}

.canvas-loader .loader .atom-spinner .spinner-line:nth-child(3) {
    -webkit-animation: atom-spinner-animation-3 1s linear infinite;
    animation: atom-spinner-animation-3 1s linear infinite;
    transform: rotateZ(360deg) rotateX(66deg) rotateZ(0deg);
}

@-webkit-keyframes atom-spinner-animation-1 {
    100% {
        transform: rotateZ(120deg) rotateX(66deg) rotateZ(360deg);
    }
}

@keyframes atom-spinner-animation-1 {
    100% {
        transform: rotateZ(120deg) rotateX(66deg) rotateZ(360deg);
    }
}

@-webkit-keyframes atom-spinner-animation-2 {
    100% {
        transform: rotateZ(240deg) rotateX(66deg) rotateZ(360deg);
    }
}

@keyframes atom-spinner-animation-2 {
    100% {
        transform: rotateZ(240deg) rotateX(66deg) rotateZ(360deg);
    }
}

@-webkit-keyframes atom-spinner-animation-3 {
    100% {
        transform: rotateZ(360deg) rotateX(66deg) rotateZ(360deg);
    }
}

@keyframes atom-spinner-animation-3 {
    100% {
        transform: rotateZ(360deg) rotateX(66deg) rotateZ(360deg);
    }
}

@keyframes fadein {
    from {
        opacity: 0;
    }
    to {
        opacity: .9;
    }
}

/* Firefox < 16 */

/* Safari, Chrome and Opera > 12.1 */
@-webkit-keyframes fadein {
    from {
        opacity: 0;
    }
    to {
        opacity: .9;
    }
}

/* Internet Explorer */

/* Opera < 12.1 */

@keyframes fadeout {
    from {
        opacity: .9;
    }
    to {
        opacity: 0;
    }
}

/* Firefox < 16 */

/* Safari, Chrome and Opera > 12.1 */
@-webkit-keyframes fadeout {
    from {
        opacity: .9;
    }
    to {
        opacity: 0;
    }
}

/* Internet Explorer */

/* Opera < 12.1 */

.atom-spinner .spinner-circle {
    background: #286aa3;
}

.atom-spinner .spinner-line {
    border-left-color: #ee731b;
}


</style>
