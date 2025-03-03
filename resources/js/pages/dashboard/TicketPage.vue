<template>
    <layout-center>
        <ticket-page-main/>
    </layout-center>
</template>

<script>
import LayoutCenter from "./LayoutCenter";
import TicketPageMain from "../../components/ticket/ticketPageMain";

export default {
    components: {
        TicketPageMain,
        LayoutCenter,
    },
}
</script>