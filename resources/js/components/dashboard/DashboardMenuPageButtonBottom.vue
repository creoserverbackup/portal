<template>
    <nav class="dashboard__nav dashboard-nav">
        <ul class="dashboard-nav__list d-flex justify-content-sm-between">
            <li class="dashboard-nav__item shake shake--delay-1"  v-on:click="showChatModal">
                <a class="dashboard-nav__link bg-primary c-white">
                    <svg xmlns="http://www.w3.org/2000/svg" id="i-phone-3" height="24" width="24"
                         class="ml-2 mr-2 fill-white" viewBox="0 0 24 24">
                        <path d="M20.478 3.516A11.913 11.913 0 0011.994 0 11.913 11.913 0 003.51 3.516C-.874 7.9-1.178 14.918 2.754 19.652c-.385.776-1.004 1.68-1.923 2.13a1.125 1.125 0 00.325 2.12c.228.037.553.073.949.073 1.06 0 2.623-.249 4.221-1.4a11.998 11.998 0 0014.157-2.09 11.913 11.913 0 003.516-8.485c0-3.207-1.253-6.216-3.521-8.484zm-.97 15.999A10.627 10.627 0 016.61 21.169a.682.682 0 00-.832.106.222.222 0 00-.055.04c-1.376 1.066-2.725 1.29-3.618 1.29H2.1c1.03-.751 1.68-1.868 2.06-2.735.06-.147.07-.3.035-.442a.712.712 0 00-.167-.385C.314 14.847.506 8.453 4.474 4.485 8.62.34 15.364.34 19.504 4.485c4.15 4.146 4.15 10.884.005 15.03z"></path><path d="M11.994 12.842a.842.842 0 100-1.684.842.842 0 000 1.684zM16.337 12.842a.842.842 0 100-1.684.842.842 0 000 1.684zM7.65 12.842a.842.842 0 100-1.684.842.842 0 000 1.684z"></path></svg>
<!--                    <span class="dashboard-nav__text">{{ $t('navbarChat') }}</span>-->
                </a>
            </li>
            <li class="dashboard-nav__item shake shake--delay-5 m-0">
                <a href="tel:+310858640777" class="dashboard-nav__link bg-primary c-white">
                    <svg xmlns="http://www.w3.org/2000/svg" id="i-phone-3" height="24" width="24"
                         class="ml-4 mr-4 fill-white" viewBox="0 0 24 24"><path d="M6.492 2.25c-.392 0-.78.14-1.101.398l-.047.024-.024.023L2.977 5.11 3 5.133a2.35 2.35 0 00-.633 2.531c.003.006-.003.018 0 .024.636 1.819 2.262 5.332 5.438 8.507 3.187 3.188 6.747 4.75 8.508 5.438h.023a2.692 2.692 0 002.602-.516l2.367-2.367c.62-.621.62-1.7 0-2.32l-3.047-3.047-.024-.047c-.62-.621-1.722-.621-2.343 0l-1.5 1.5a12.131 12.131 0 01-3.07-2.11c-1.228-1.171-1.855-2.519-2.087-3.046l1.5-1.5c.63-.63.642-1.679-.023-2.297l.023-.024-.07-.07-3-3.094-.023-.023-.047-.024a1.767 1.767 0 00-1.102-.398zm0 1.5c.056 0 .112.026.164.07l3 3.07.07.07c-.005-.005.044.074-.046.165L7.805 9l-.352.328.164.469s.862 2.305 2.672 4.031l.164.14c1.743 1.592 3.797 2.462 3.797 2.462l.469.21 2.226-2.226c.13-.129.106-.129.235 0l3.07 3.07c.129.13.129.082 0 .211l-2.297 2.297c-.346.296-.712.358-1.148.211-1.7-.668-5.001-2.118-7.946-5.062-2.967-2.968-4.517-6.334-5.086-7.97-.114-.304-.032-.755.235-.984l.047-.046 2.273-2.32a.263.263 0 01.164-.071z"></path></svg>
<!--                    <span class="dashboard-nav__text">{{ $t('navbarPhone') }}</span>-->
                </a>
            </li>
            <li class="dashboard-nav__item shake shake--delay-9 m-0">
                <a href="mailto:info@creoserver.com" class="dashboard-nav__link bg-primary c-white">
                    <svg xmlns="http://www.w3.org/2000/svg" id="i-mail-3" height="24" width="24"
                         class="ml-2 mr-2 fill-white" viewBox="0 0 24 24"><path d="M23.49 3.83H.5a.51.51 0 00-.5.51v15.32c0 .282.229.51.51.51h22.98a.51.51 0 00.51-.51V4.34a.51.51 0 00-.51-.51zM22.256 4.85l-6.788 6.788L12 15.108l-3.469-3.47-6.788-6.787h20.514zM1.02 5.573L7.448 12l-6.427 6.427V5.573zm.722 13.576l6.427-6.427 3.469 3.469c.178.178.456.266.722 0l3.469-3.469 6.427 6.427H1.743zm21.236-.722L16.552 12l6.427-6.427v12.854z"></path></svg>
<!--                    <span class="dashboard-nav__text">{{ $t('navbarRecallRights') }}</span>-->
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "DashboardMenuPageButtonBottom",
    methods: {
        ...mapActions([
            'SET_SHOW_CHAT_MODAL',
        ]),
        showChatModal()
        {
         this.SET_SHOW_CHAT_MODAL(true)
        }
    }
}
</script>

<style scoped>

</style>