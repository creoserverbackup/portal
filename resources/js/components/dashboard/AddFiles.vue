<template>
    <div class="container add-files">
        <div class="row">
            <div class="col-md-4 p-0 m-0 btn-upload">
                <button class="add-files upload" v-on:click="addFiles()">{{ nameButton }}</button>
            </div>
            <div class="col-md-8 p-0 m-0">
                <button class="add-files upload2" v-on:click="addFiles()">...</button>
            </div>
        </div>
        <div class="large-12 medium-12 small-12 cell">
            <input type="file" id="files" ref="files" multiple v-on:change="handleFilesUpload()"
                   v-bind:accept="getAccept()"/>
        </div>
        <div class="row block_add_files" v-if="this.$parent.files">
            <div v-for="(file, key) in $parent.files" class="row_add_file">
                <span class="file_name">{{ file.name }}</span>
                <span class="remove-file" v-on:click="removeFile(key)">&#215;</span>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    props: {
        type: '',
    },
    computed: {
        nameButton() {
            return this.$parent.files.length > 0 ? 'Upload' : 'Browse'
        },
    },
    methods: {
        getAccept() {
            switch (this.type) {
                case 'rma':
                    return '.png, .jpg, .jpeg, .svg'
                case 'ticket':
                    return '.png, .jpg, .pdf, .txt'
                default :
                    return '.png, .jpg, .jpeg, .svg'
            }
        },
        addFiles() {
            this.$refs.files.click()
        },
        handleFilesUpload() {
            let uploadedFiles = this.$refs.files.files

            for (let i = 0; i < uploadedFiles.length; i++) {
                this.$parent.files.push(uploadedFiles[i])
            }
        },
        removeFile(key) {
            this.$parent.files.splice(key, 1)
        }
    }
}
</script>
