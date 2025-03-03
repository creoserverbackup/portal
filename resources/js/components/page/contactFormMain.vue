<template>

    <div class="contact-form mt-3">
        <h1 class="mb-10">Service & Contact</h1>
        <div class="row mb-15 d-flex">
            <div class="col-12 col-md-7 col-2xl-7 mb-30">
                <contact-form-form/>
<!--                <form>-->
<!--                    <div class="h4 fw-bold">Contact formulier</div>-->
<!--                    <div>-->
<!--                        <label class="">Uw naam</label>-->
<!--                        <input type="text" v-model="name">-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <label>Uw e-mail adres</label>-->
<!--                        <input type="text" v-model="email">-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <label>Uw telefoonummer</label>-->
<!--                        <input type="text" v-model="phone">-->
<!--                    </div>-->
<!--                    <div class="d-f-column">-->
<!--                        <label>Afdeling</label>-->
<!--                        <div>-->
<!--                            <v-select required-->
<!--                                      :reduce="(department) => department.value"-->
<!--                                      v-model="department"-->
<!--                                      :options="departments">-->
<!--                            </v-select>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <label>Uw bericht / vraag</label>-->
<!--                        <textarea autocomplete="off" minlength="30" maxlength="600" v-model="text"-->
<!--                                  style="min-height: 222px; height: 222px;">-->
<!--                                        </textarea>-->

<!--                        <div class="row">-->
<!--                            <div class="col-12 ms-auto" @click="startSend">-->
<!--                                <button type="button" class="btn btn&#45;&#45;t-50 btn&#45;&#45;primary w-100">Verstuur bericht</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
            </div>
<!--            <vue-recaptcha-->
<!--                    ref="recaptcha"-->
<!--                    size="invisible"-->
<!--                    :loadRecaptchaScript="true"-->
<!--                    :sitekey="siteKey"-->
<!--                    @verify="register"-->
<!--                    @expired="onCaptchaExpired"-->
<!--            />-->
            <div class="col-12 col-md-5 col-lg-4 col-3xl-3 ms-auto mb-30">
                <contact-form-aside class="h-100 d-flex flex-column justify-content-between"/>
            </div>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2429.448702098057!2d6.139365215806313!3d52.489116879808336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c87e7774ff02a7%3A0x7a37a6861fe9bc99!2sCreoServer.com!5e0!3m2!1sen!2sby!4v1651668293453!5m2!1sen!2sby"
                width="100%" height="490" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>


</template>

<script>

import ContactFormAside from "./contactFormAside";
import ContactFormForm from "./contactFormForm";
export default {
    name: "contactFormMain",
    components: {
        ContactFormForm,
        ContactFormAside
    },
}
</script>

<style scoped>

</style>