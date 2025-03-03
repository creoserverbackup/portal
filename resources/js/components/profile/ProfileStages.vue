<template>
    <ul class="stages">
        <li class="stages__stage"
            :class="{active: inputStatus}">
            <div class="stages__stage-step">1</div>
            <span class="stages__stage-desc">{{ $t('SetupProcess1') }}</span>

        </li>
        <li class="stages__stage">
            <div class="stages__stage-step">2</div>
            <span class="stages__stage-desc">{{ $t('SetupProcess2') }}</span>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            inputStatus: {
                type: Boolean,
                required: false
            }
        }
    }
</script>
