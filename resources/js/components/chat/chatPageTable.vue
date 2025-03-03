<template>
    <div class="chat-page-table">
        <h4>Recente berichtenlijst</h4>
        <div class="pt-3 pb-3 pl-2 mt-2 position-relative" v-for="(chat, key) in chats"
             v-bind:class="[
                     chat.unread > 0 ? 'pulse-secondary b-secondary' : 'bb-gray',
                     key == 0 && chat.unread == 0 ? 'bt-gray' : '',
             ]"
        >
            <small class="chat-page-table__notification" v-if="chat.unread">
                {{ chat.unread }}
            </small>
            <div class="fs-16 c-secondary fw-bold">#{{ chat.id }}
                {{ getCause(chat.cause) }}

                <span v-if="chat.message_user == 0 && chat.message_admin > 0 && chat.unread == chat.message_admin">
                    Nieuwe binnenkomende chat
                </span>
            </div>
            <div class="chat-page-table__container d-flex-row-between fs-14 c-gray mb-3 fs-italic">
                <div class="d-flex w-80 chat-page-table__container__wrapper">
                    <div class="mr-4 w-25 chat-page-table__container__item">
                        <span class="fw-bold mr-2">Medewerker:</span> {{ chat.recipient }}
                    </div>
                    <div class="mr-4 w-20 chat-page-table__container__item">
                        <span class="fw-bold mr-2">Bericht gelezen:</span>{{ chat.read }}
                    </div>
                    <div class="mr-4 w-40 chat-page-table__container__item">
                        <span class="fw-bold mr-2">Laatste berichtdatum:</span>{{ chat.date }}
                    </div>
                </div>
                <div class="chat-page-table__buttons">
                    <div class="d-flex-row-between">
                        <button class="btn btn--primary mr-4" type="button"
                                @click="chatClose(chat.id)">{{ $t('DashboardChatClose') }}
                        </button>

                        <button class="btn btn--primary" type="button" v-on:click="startChatWithUser(chat.id, chat.uidWithWhomChat, chat.cause)">Verder met deze chat
                        </button>
                    </div>
                </div>
            </div>
            <!--            <div class="fs-14 mb-2 d-flex">-->
            <!--                <div class="mr-5">Last message:</div>-->
            <!--                <div>{{ chat.message }}</div>-->
            <!--            </div>-->
        </div>
    </div>
</template>

<script>

export default {
    name: "chatPageTable",
    props: {
        chats: {},
    },
    mounted() {
        this.$root.$on('chatMessageEvent', (data) => {
            this.$emit('getTableUnreadMessages');
        })
    },
    methods: {
        getCause(cause) {
            let arr = new Map([
                [1, 'Product vraag'],
                [2, 'Prijs opvragen'],
                [3, 'Factuur vraag'],
                [4, 'Technische vraag'],
                [5, 'Voorraad vraag'],
                [6, 'Transport vraag'],
                [7, 'Anders'],
            ])

            return arr.get(cause)
        },
        startChatWithUser(chatId, recipient, cause) {
            this.$emit('startChatWithUser', chatId, recipient, cause);
        },
        chatClose(chatId) {
            this.$emit('chatClose', chatId)
        },
    },
    destroyed() {
        this.$root.$off('chatMessageEvent')
    }
}
</script>

<style lang="scss">
@import "resources/sass/abstracts/variables";

.chat-page-table {

    & img {
        width: auto;
    }

    @media (max-width: 684px) {
        &__container {
            display: block;

            &__wrapper {
                display: block!important;
            }
            &__item {
                display: block;
                width: 100%
            }
        }


        &__buttons  {
            margin-top: 20px;
            button {
                padding: 5px 10px;
                font-size: 14px;
            }
        }
    }




    &__notification {
        width: 25px;
        height: 25px;
        display: inline-block;
        border-radius: 50%;
        background: $secondary;
        color: white;
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
        line-height: 25px;
        position: absolute;
        right: 0px;
        top: 3px;
    }


}

</style>
