<template>
    <div class="modal middle" v-if="messages.length > 0"
         v-bind:class="{ 'modal-open': messages.length > 0}">
        <div class="modal-inner">
            <div class="modal-close" @click="closeMessage">
                <img src="/images/close.svg" alt="X">
            </div>
            <div class="modal-body scroll">
                <div class="modal_h2" v-for="message in messages">{{ message }}</div>
            </div>
            <div class="modal-form">
                <div class="two-columns button-modal">
                    <div class="two-columns_item">
                        <input type="submit" @click="closeMessage" value="Ok">
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            messages: [],
        }
    },
    mounted() {
        this.$root.$on('popupMessages', (data) => {
            this.messages.push(data)
        })
    },
    methods: {
        closeMessage() {
            this.messages = []
        },
    },
}
</script>
