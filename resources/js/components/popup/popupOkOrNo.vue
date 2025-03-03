<template>
    <div class="modal middle popup-ok-or-no" v-if="messages.length > 0"
         v-bind:class="{ 'modal-open': messages.length > 0}">
        <div class="modal-inner">
            <div class="modal-close" @click="closeMessage">
                <img src="/images/close.svg" alt="X">
            </div>
            <div class="modal-body">
                <div class="popup-ok-or-no__message" v-for="message in messages">{{ message }}</div>
                <div class="modal-form">
                    <div class="two-columns button-modal">
                        <div class="two-columns_item popup-ok-or-no__two-columns_item">
                            <button class="popup-ok-or-no__btn popup-ok-or-no__btn-cancel mr-3" @click="messages = [];">
                                Annuleren
                            </button>
                            <button class="popup-ok-or-no__btn popup-ok-or-no__btn-ok" @click="answerSuccess">Ok
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-overlay" @click="closeMessage"></div>
    </div>
</template>

<script>
export default {
    name: "popupOkOrNo",
    data() {
        return {
            messages: [],
        }
    },
    mounted() {

        this.$root.$on('popupOkOrNo', (data) => {
            this.messages.push(data.message)
            this.satisfactory = data.callback
        });

    },
    methods: {
        closeMessage() {
            this.messages = []
        },
        answerSuccess() {
            this.messages = []
            this.satisfactory()
        },
    },
}
</script>

<style scoped>

</style>