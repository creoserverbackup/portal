<template>
    <div>
        <p><span v-html="message"></span></p>
        <input type="email" v-model.trim="search" placeholder="" style="width: 350px"
               @keyup.enter="searchUser()">
        <button class="btn btn--primary" v-on:click="searchUser()">check</button>
        <div v-html="result"></div>
        <table v-if="isTable">
            <tr>
                <th v-for="item in tableNameField">{{ item }}</th>
            </tr>
            <tr v-for="item in tableDataUsers">
                <td>{{ item.id }}</td>
                <td>{{ item.username }}</td>
                <td>{{ item.customerName }}</td>
                <td>{{ item.email }}</td>
                <td>
                    <button class="btn btn--primary" type="button"
                            v-on:click="sendEmailChatFromSupport(item.id, item.email)">Start chat
                    </button>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>

export default {
    name: "chatForStaff",
    data() {
        return {
            tableNameField: [
                'id',
                'userName',
                'Name Company',
                'Email',
                // 'Cause',
                'Start chat',
            ],
            isTable: false,
            search: '',
            tableDataUsers: [],
            result: '',
            message: 'Enter email, name or Id user',
        }
    },
    methods: {
        searchUser() {
            this.isTable = false
            this.result = ''
            this.tableDataUsers = []
            if (this.search.length > 0) {
                axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/chat/user/search/${this.search}`).then((response) => {
                    if (response.data.length > 0) {
                        this.isTable = true
                        for (let i = 0; i < response.data.length; i++) {
                            this.tableDataUsers.push(response.data[i])
                        }
                    } else {
                        this.result = 'Not found'
                    }
                })
            } else {
                this.result = 'Invalid enter field'
            }
        },
        async sendEmailChatFromSupport($uid, $email) {

            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/mail/chat/start/uid/${$uid}/admin/${window.user}`).then((response) => {
                this.result = 'Email sent to ' + $email
                this.$emit('startChatWithUser', response.data.id, response.data.recipient, response.data.cause)
            })
        },
    },
}
</script>