<template>
    <layout-center>
        <chat-page-main/>
    </layout-center>

</template>

<script>
import LayoutCenter from "./LayoutCenter";
import ChatPageMain from "../../components/chat/chatPageMain";

export default {
    name: "Chat",
    components: {
        ChatPageMain,
        LayoutCenter
    }
}
</script>

<style scoped>

</style>