<template>
    <div class="row max-h-160 m-0 p-0 w-100" v-if="prices">

        <p class="fw-500 w-100">
            <span class="c-red">Letop!:</span> In deze stap kunt u een <span
                class="td-underline">ander aflever adres</span>
            invoeren. U kunt dit doen door de gewenste invul velden te
            selecteren, en de overgenomen standaard adresgegevens van de factuur te vervangen naar het gewenste aflever
            adres.
        </p>

        <div class="col-4 p-0 m-0 b-primary">
            <ul class="d-grid h-100">
                <li class="p-4-10" v-for="item in paymentEdit"
                    :class="[
                                {'c-white': checkboxes.includes(item.id)},
                                {'bg-success': checkboxes.includes(item.id)},
                                {'bg-secondary': item.id === 'quickly'},
                                {'c-white': item.id === 'quickly'},
                        ]">
                    <label class="d-flex m-0" :for="item.id">
                        <input class="w-auto mr-2 mt-1"
                               v-bind:disabled="checkSelectType(item.id)"
                               type="checkbox" v-model="checkboxes" :id="item.id" :value="item.id">
                        {{ getTextType(item.id) }}</label>
                </li>
            </ul>
        </div>
        <div class="col-4 pl-2 pr-2 payment-flow__steps_item__step-3__bottom__disclaimer__container">
            <div class="p-2-10 b-primary max-h-160 o-auto scroll payment-flow__steps_item__step-3__bottom__disclaimer">
                <div class="c-primary mb-1">Shipment disclaimer</div>
                <div>Bij CreoServer doen wij onze uiterste best om uw order zo snel
                    mogelijk bij u te krijgen. Echter kunnen wij nooit garantie geven op lever datum of lever tijd
                    van uw order. Dit kan te maken hebben met het transport bedrijf waar we mee werken, Of door
                    andere onvoorziende omstandigheden buiten onze macht. Wanneer u de order komt afhalen neemt u
                    dan alstublieft legetimatie mee. wij kunnen hier om vragen. Als u de order niet zelf afhaalt,
                    laat dit ons dan weten. Zonder deze kennis geven wij GEEN producten mee aan derden.
                </div>
            </div>
        </div>
        <div class="col-4 p-0 m-0 d-flex-column-between h-100 min-h-160">
            <div class="bg-primary p-2 c-white fs-14">
                <div class="d-flex pt-1">
                    <div class="min-w-45">Transport keuze:</div>
                    <div v-html="titleDelivery"></div>
                </div>
                <div class="d-flex pt-1">
                    <div class="min-w-45">Extra transport kosten:</div>
                    <div>{{ checkPrice(titlePriceDelivery) }}</div>
                </div>
                <div class="d-flex pt-1">
                    <div class="min-w-45">Totaal bedrag product(en):</div>
                    <div>{{ checkPrice(prices.priceFullWithoutDelivery) }} incl. btw</div>
                </div>
                <div class="fs-20 mt-3 pt-3 bt-white">Totaal door u te voldoen: {{
                        checkPrice((+prices.priceFullWithoutDelivery + (+titlePriceDelivery * ((100 + prices.nds) / 100))).toFixed(2))
                    }} incl. btw
                </div>
            </div>
            <div class="mt-1px">
                <div class="d-flex-row-between w-100 payment-flow__steps_item__step-3__bottom__inputs">
                    <input class="c-white bg-primary b-none" type="button" v-if="!offerte"
                           v-on:click="clearCart()" value="Annuleren">
                    <input class="c-white bg-primary b-none ml-1px mr-1px" type="button" value="Vorige"
                           v-on:click="nextStep(2)">
                    <input class="c-white bg-primary b-none" v-if="selectDelivery"
                           type="button" v-on:click="nextStep(4)" value="Volgende">
                    <input class="c-white bg-gray b-none" v-else disabled type="button" value="Volgende">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {checkPriceHelper} from "../../helper";

export default {
    name: "PaymentFlowStep3Bottom",
    props: {
        extraPrice: '',
        types: '',
        deliveryInfo: '',
        titleDelivery: '',
        selectDelivery: '',
        titlePriceDelivery: '',
        prices: {
            type: Object,
        },
        offerte: '',
    },
    data() {
        return {
            checkboxes: [],
            paymentEdit: [
                {
                    id: 'quickly',
                    checked: false,
                },
                {
                    id: 'weekday',
                    checked: false,
                },
                {
                    id: 'weekend',
                    checked: false,
                },
                {
                    id: 'neighbour',
                    checked: false,
                },
            ],
        }
    },
    methods: {
        setStartCheckBox() {
            this.checkboxes = []
            for (let key in this.deliveryInfo) {
                this.paymentEdit.forEach(edit => {
                    if (edit.id == key && this.deliveryInfo[key] == 1) {
                        this.checkboxes.push(edit.id)
                    }
                })
            }
        },
        checkSelectType(name) {
            let result = false
            for (let number in this.types) {
                if (this.types[number].selected && this.types[number].type == 3 && name != 'quickly') {
                    result = true
                }
            }
            return result
        },
        checkTypes() {
            for (let number in this.types) {
                if (this.types[number].selected && this.types[number].type == 3) {
                    this.checkboxes = []
                }
            }
        },
        checkPrice(price) {
            return checkPriceHelper(price)
        },
        clearCart() {
            this.$emit('clearCart')
        },
        nextStep(step) {
            this.$emit('nextStep', step)
        },
        getTextType(type) {
            let arr = new Map([
                ['weekday', 'Ik ben tijdens alle werkdagen beschikbaar'],
                ['weekend', 'Ik ben ook in de weekenden bereikbaar'],
                ['neighbour', 'Mijn leveringen mogen niet bij de buren worden afgeleverd.'],
                ['quickly', 'Dit is een spoedopdracht in schriftelijk overleg met Creoserver. Extra kosten bedragen €' + this.extraPrice],
            ])
            return arr.get(type)
        },
        setCheckboxes() {
            this.$emit('setCheckboxes', this.checkboxes)
        }
    },
    watch: {
        checkboxes: {
            deep: true,
            handler() {
                this.setCheckboxes()
            }
        },
        types: {
            deep: true,
            handler() {
                this.checkTypes()
            }
        },
    }
}
</script>

<style scoped>

</style>
