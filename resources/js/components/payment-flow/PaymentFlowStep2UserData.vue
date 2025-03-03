<template>
    <div class="col-4">
        <div class="position-relative p-10 b-secondary">
            <div class="fs-24 mb-40 c-g" v-if="resultSave">{{ resultSave }}</div>
            <div class="fs-24 c-secondary mb-40" v-else>Bedrijfs gegevens</div>
            <span class="payment-flow__step2-links-div" type="button"
                  v-on:click="openEditBlockUser"><span>wijzigen</span></span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Creo klantnummer: </span>
                <span class="c-gray">{{ user.customerId }} </span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Bedrijfsnaam: </span>
                <span class="c-gray">{{ user.customerName }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Contact persoon: </span>
                <span class="c-gray">{{ user.username }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Adres: </span>
                <span class="c-gray">{{ user.address }}</span>
                <span class="ml-1 fs-16 c-gray">{{ user.house }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Postcode: </span>
                <span class="c-gray">{{ user.postcode }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Land: </span>
                <span class="c-gray w-40">{{ user.country }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Woonplaats: </span>
                <span class="c-gray">{{ user.region }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Email: </span>
                <span class="c-gray">{{ user.email }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                <span class="c-black">Email Invoice: </span>
                <span class="c-gray">{{ user.emailInvoice }}</span>
            </span>
            <span class="fs-16 d-block mb-20">
                    <span class="c-black">Telefoon nummer: </span>
                    <span class="c-gray">{{ user.phone }}</span>
                </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentFlowStep2UserData",
    props: {
        resultSave: '',
        user: '',
    },
    methods: {
        openEditBlockUser() {
            this.$emit('openEditBlockUser');
        }
    }
}
</script>

<style scoped>

</style>