<template>
    <div class="contact-aside">
        <div class="mb-30">
            <div class="h4 fw-bold contact-aside__title contact-aside__title--align">Bedrijfsinformatie</div>
            <div>
                CreoServer BV <br/>
                Paxtonstraat 23,<br/>
                8013 RP Zwolle
            </div>
            <br>
            <div>
                BTW. NL 859562761B01 <br/>
                KvK: 73532142
            </div>
        </div>
        <div class="mb-30">
            <div class="h4 fw-bold">Contact Gegevens</div>

            <div class="d-flex mt-10">
                <div class="fw-bold w-25">Mail:</div>
                <a rel="nofollow" href="mailto:info@creoserver.com" class="c-secondary td-n">Info@creoserver.com</a>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25"></div>
                <a rel="nofollow" href="mailto:sales@creoserver.com" class="c-secondary td-n">Sales@creoserver.com</a>
            </div>


            <div class="d-flex mt-25">
                <div class="fw-bold w-25">Tel</div>
                <a class="td-n" rel="nofollow" href="tel:+310858640777">+31 (0) 85 864 0777 </a>
            </div>

            <div class="d-flex mt-25">
                <div class="fw-bold w-25">Optie 1:</div>
                <div>Inkoop & Verkoop</div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">Optie 2:</div>
                <div>Technische Support</div>
            </div>
            <div class="d-flex">
                <div class="fw-bold w-25">Optie 3:</div>
                <div>Administratie</div>
            </div>
        </div>
        <div>
            <div class="h4 fw-bold">Openingstijden</div>
            <TimeTable class="contact-aside__timetable"/>
        </div>

    </div>
</template>

<script>

import TimeTable from "../dashboard/TimeTable";

export default {
    name: "contactFormAside",
    components: {
        TimeTable
    },
}
</script>

<style scoped>

</style>