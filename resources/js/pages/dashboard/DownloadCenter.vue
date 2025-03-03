<template>
    <div class="download-center scroll">
        <h1>{{ $t('navSectionDownload') }}</h1>
        <div class="bg-primary">
            <div class="position-relative download-center__search">
                <input type="text" class="download-center__input" @keyup.enter="getFiles" v-model.trim="searchWord"
                       @input="getFiles" placeholder="Zoek een specifieke download ...">
                <button class="search-form__submit download-center__submit" v-on:click="getFiles()">
                    <i class="icon-search"></i>
                </button>
            </div>
        </div>
        <div class="main-table">
        <table class="fs-14 main-table__table">
            <tr class="b-primary-2">
                <th class="w-7">Type</th>
                <th class="w-15">Titel</th>
                <th class="w-30">Beschrijving</th>
                <th class="">Groote</th>
                <th class="">Categorie</th>
                <th class="">Datum</th>
                <th class="">Download</th>
            </tr>
            <tbody>
            <tr v-for="file in files">
                <td>
                    <img class="icon" v-bind:src="'/images/icon/' + file.type + '.png'" alt="">
                </td>
                <td>
                    <div class="c-primary fw-bold fs-16">{{ file.name }}</div>
                    <div class="d-flex fs-10">
                        <div class="icon-files"><img src="images/icon-files.png"></div>
                        <div class="ml-1 mr-1">{{ file.countFiles }} file(s)</div>
                        <div class="icon-files"><img src="images/arrowcircledown.png"></div>
                        <div class="ml-1">{{ file.unload }} downloads</div>
                    </div>
                </td>
                <td>{{ file.description }}</td>
                <td class="fw-bold">{{ formatSizeUnits(file.size) }}</td>
                <td class="c-primary">{{ file.category }}</td>
                <td class="fw-bold">{{ getDate(file.time) }}</td>
                <td>
                    <button class="w-100 b-none c-white bg-primary hover-bg-secondary"
                            v-on:click="downloadFile(file.disk_name, file.name + '.' + file.type)"
                            type="button">Download
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
        <div class="bg-gray pt-2">
            <div class="ta-r c-white mr-3 mt-1">{{ $t('CountFilesForUpload') }} {{ countFiles }}</div>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import {DateTime} from 'luxon'

export default {
    data() {
        return {
            searchWord: null,
            files: [],
            countFiles: '',
        }
    },
    mounted() {
        this.getFiles()
        this.GET_LOADING_FROM_REQUEST(false)
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST'
        ]),
        getFiles() {
            let search = this.searchWord ?? ''
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/download-center?search=${search}`).then((response) => {
                this.files = response.data
                this.countFiles = response.data.length
            })
        },
        getDate(date) {
            return DateTime.fromSeconds(+date).toFormat('MMMM dd, yyyy')
        },
        formatSizeUnits(bytes) {
            if (bytes >= 1073741824) {
                bytes = (bytes / 1073741824).toFixed(2) + ' GB';
            } else if (bytes >= 1048576) {
                bytes = (bytes / 1048576).toFixed(2) + ' MB';
            } else if (bytes >= 1024) {
                bytes = (bytes / 1024).toFixed(2) + ' KB';
            } else if (bytes > 1) {
                bytes = bytes + ' bytes';
            } else if (bytes == 1) {
                bytes = bytes + ' byte';
            } else {
                bytes = '0 byte';
            }
            return bytes;
        },
        async downloadFile(disk_name, name) {
            this.GET_LOADING_FROM_REQUEST(true);
            try {
                let data = await axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/download-center/${disk_name}`,
                        {responseType: 'blob'});
                const url = window.URL.createObjectURL(new Blob([data.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', name);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                this.GET_LOADING_FROM_REQUEST(false);
                this.getFiles()
                return true;
            } catch (e) {
                this.GET_LOADING_FROM_REQUEST(false);
                return e;
            }
        },
    }
}
</script>


<style lang="scss">

@import "resources/sass/abstracts/variables";

.download-center {

   // overflow-x: auto;

    &__search {
        padding: 10px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        max-width: 50%;
        @media (max-width: 576px) {
            max-width: 100%;
        }
    }
    &__input {

    }
    td {
        padding: 1rem 0.5rem;
    }

    &__submit {
        border: 1px solid #777777;
        border-left: 0;
        background: none;
        right: 0;
        top: 0;
        bottom: 0;
        width: 4rem;
        position: relative;
        background: #fff;
    }

    table {
        tr {
            background: none;
        }

        tr:hover td {
            background: none;
        }

        tbody tr:nth-child(odd) {
            background: #fff;
        }

        /* Четные строки */
        tbody tr:nth-child(even) {
            background: #F7F7F7;
        }

        .icon {
            width: 64px;
            height: 64px;
            background-position: 0 0;
            background-repeat: no-repeat;
        }

        .icon-files {
            position: relative;
            width: 16px;
            height: 16px;
            display: inline-flex;
        }

        .icon-files img {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            max-width: 100%;
            max-height: 100%;
        }
    }
}


</style>
