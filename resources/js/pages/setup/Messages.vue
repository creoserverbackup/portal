<template>
    <div class="modal" :class="{'modal-open': messageStack.length > 0}">
        <div class="modal-inner">
            <div class="modal-body">
                <template v-for="message in messageStack">
                    <p class="modal_h2">{{ message }}</p>
                </template>
                <div class="buttons-row">
                    <button class="red" @click="messageStack = []">Ok</button>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
</template>

<script>

export default {
    props: {
        messages: {},
    },
    data() {
        return {
            messageStack: []
        }
    },
    computed: {},
    watch: {
        messages: {
            deep: true,
            handler(value) {
                let stack = []
                this.messages.forEach((item) => {
                    if (Array.isArray(item)) {
                        item.forEach((value) => {
                            stack.push(value)
                        })
                    } else {
                        stack.push(item)
                    }
                });

                this.messageStack = stack
            }
        },
    },
    mounted() {
    },
    methods: {},

}
</script>
