<template>
    <div class="ticket-new-file">
        <div class="d-flex h-30px">
            <div class="col-md-4 p-0 m-0">
                <button class="ticket-new-file__upload-btn" v-on:click="add()">{{ nameButton }}</button>
            </div>
            <div class="col-md-8 p-0 m-0">
                <button class="ticket-new-file__upload-point" v-on:click="add()">...</button>
            </div>
        </div>
        <div class="d-none">
            <input type="file" ref="files" multiple v-on:change="handleUpload()" v-bind:accept="getAccept()"/>
        </div>
        <div class="ticket-new-file__files mt-2 scroll" v-if="$parent.files">
            <div v-for="(file, key) in $parent.files" class="ticket-new-file__line">
                <span class="ticket-new-file__line-name">{{ file.name }}</span>
                <span class="ticket-new-file__line-remove" v-on:click="remove(key)">&#215;</span>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    name: "ticketNewFile",
    methods: {
        getAccept() {
            return '.png, .jpg, .pdf, .txt'
        },
        add() {
            this.$refs.files.click()
        },
        handleUpload() {
            let uploadedFiles = this.$refs.files.files

            for (let i = 0; i < uploadedFiles.length; i++) {
                this.$parent.files.push(uploadedFiles[i])
            }
        },
        remove(key) {
            this.$parent.files.splice(key, 1)
        }
    },
    computed: {
        nameButton() {
            return this.$parent.files.length > 0 ? 'Upload' : 'Browse'
        },
    },
}
</script>