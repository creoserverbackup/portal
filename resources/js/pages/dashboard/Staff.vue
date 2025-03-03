<template>
    <div class="staff">
        <img class="staff__img" :src="profile.image"/>
        <div class="staff__info">
            <div class="staff__head">
                <h2 class="staff__name">{{ profile.name }}</h2>
                <span class="staff__role">{{ profile.nameRole }}</span>
                <div class="staff__separator"></div>
            </div>
            <p class="staff__text">{{ profile.description }}</p>
            <p class="staff__contact">
                Rond de tafel met {{ profile.name }} ? neem
                <a class="staff__link" :href="profile.link">direct contact</a>
                met hem op.
            </p>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name: "Staff",
    data () {
        return {
            profile: {},
        }
    },
    mounted () {
        this.getInfoStaffForPage()
        // TEST FUNCTION PRELOADER
        let letThis = this
        letThis.GET_LOADING_FROM_REQUEST(false)
    },
    methods: {
        ...mapActions([
            'GET_LOADING_FROM_REQUEST',
        ]),
        getInfoStaffForPage () {
            axios.get(`${process.env.MIX_CREO_WORK_FLOW}/api/public/staff/${this.$route.params.id}`).then((response) => {
                this.profile = response.data
            })
        }
    }
}
</script>
