<template>
    <layout-center>
        <ticket-new/>
    </layout-center>
</template>

<script>
import LayoutCenter from "./LayoutCenter";
import TicketNew from "../../components/ticket/ticketNew";

export default {
    components: {
        TicketNew,
        LayoutCenter,
    },
}
</script>
