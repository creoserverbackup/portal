<template>
  <div class="row">
    <payment-flow-step1-products :products="products"
                                 :orderId="orderId"
                                 :offerte="offerte"
                                 :creoNum="creoNum"
    />
    <div class="col-12 col-md-4 col col--2xl-3 pl-0">
      <div class="fs-25 c-primary mb-3">Order samenvatting</div>
      <div class="b-primary bg-white mb-2 p-2">
        <div>
          <span class="fs-18 c-primary">Aantal te bestellen artikelen:</span>
          <span class="fs-18 c-primary">{{ prices.productCount }}</span>
        </div>
        <div v-if="prices.personalDiscount">
          <div class="c-primary">
            <span class="fs-18 ">Uw CREO korting</span>
            <span class="fs-18">{{ prices.personalDiscount }}%</span>
          </div>
          <div class="fs-12 c-primary d-f-column">
            <span>korting bedrag {{
                checkPrice(prices.priceFullDiscountBefore * (prices.personalDiscount / 100))
              }}</span>
          </div>
        </div>
        <div>
          <span class="fs-18 c-primary">Uw kortingscode</span>
          <input class="cart-data__input mb-1" type="text"
                 v-bind:class="{ 'c-success': prices.adoptedCoupon}"
                 @input="checkCoupon" v-model="coupon"
                 placeholder="Typ hier uw kortingscode in..." :disabled="isDefault">
          <span class="fs-14 c-primary">kortings bedrag van: {{
              checkPrice(prices.priceDiscountCoupon)
            }}</span>
          <span v-if="prices.adoptedCoupon" class="c-success">Adopted</span>
          <!--                    coupon-->
        </div>
        <div class="mt-3">
                    <textarea type="text" placeholder="Typ hier uw extra opmerkingen..."
                              class="cart-data__textarea h-100px" v-model="description"
                              :disabled="isDefault" v-on:change="setOrderDescription"
                              :maxlength="300"
                    >
                    </textarea>
          <span v-if="description && description.length >= 300" class="c-red">Maximaal 300 tekens</span>
          <span v-else>&nbsp;</span>
        </div>
        <div class="fs-18 c-primary">
          <div class="d-flex-row-between l-h-18">
            <span>BTW tarief</span>
            <span>{{ prices.nds }}%</span>
          </div>
          <div class="d-flex-row-between l-h-18">
            <span>BTW bedrag:</span>
            <span>{{ checkPrice(prices.priceFullNDS) }}</span>
          </div>
          <div class="d-flex-row-between l-h-18">
            <span>Total exclusief btw:</span>
            <span>{{ checkPrice(prices.priceFullDiscountAfter) }}</span>
          </div>
          <div class="d-flex-row-between l-h-18">
            <span>Total inclusief btw:</span>
            <span>{{ checkPrice(prices.priceFullWithNDS) }}</span>
          </div>
        </div>
      </div>
      <div class="b-primary bg-primary c-white fs-20 pl-2 m-0 pr-2 d-flex-row-between">
        <span class="d-block">Totaal te betalen:</span>
        <span class="d-block">{{ checkPrice(prices.priceFullWithoutDelivery) }}</span>
      </div>
      <div class="row m-0 p-0 mt-2">
        <div class="col m-0 p-0 pr-1">

          <router-link to="/order-center-accept" v-if="offerte">
            <button class="cart-s1__btn">Terug</button>
          </router-link>
          <button class="cart-s1__btn" v-on:click="clearCart()" :disabled="isDefault" v-else>
            Annuleren
          </button>
        </div>
        <div class="col p-0 pl-0 m-0">
          <button class="cart-s1__btn" v-on:click="nextStep(2)" :disabled="isDefault">
            Volgende
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {setTheme} from '../../plugins/dark-mode'
import {checkPriceHelper} from "../../helper.js"
import PaymentFlowProductCard from "./PaymentFlowProductCard";
import PaymentFlowStep1Products from "./PaymentFlowStep1Products";
import {mapActions} from "vuex";

export default {
  name: 'PaymentFlowStep1',
  components: {
    PaymentFlowStep1Products,
    PaymentFlowProductCard
  },
  props: {
    products: {},
    orderId: {},
    orderDescription: '',
    offerte: '',
    creoNum: '',
    prices: {},
  },
  data() {
    return {
      description: '',
      coupon: '',
    }
  },
  mounted() {
    setTheme(this.GET_THEME)
    this.GET_LOADING_FROM_REQUEST(false)
  },
  methods: {
    ...mapActions([
      'GET_LOADING_FROM_REQUEST'
    ]),
    setCoupon() {
      if (this.prices.coupon != undefined && this.prices.coupon != '') {
        this.coupon = this.prices.coupon
      }
    },
    setDescription() {
      this.description = this.orderDescription
    },
    checkPrice(price) {
      return checkPriceHelper(price)
    },
    checkCoupon() {
      this.$emit('checkCoupon', this.coupon)
    },
    nextStep(step = 1) {
      this.$emit('setStep', step)
    },

    clearCart() {
      this.$root.$emit('deleteAllProductInCart')
    },
    setOrderDescription() {

      let description = this.description
      if (description.length > 300) {
        description = description.slice(0, 300)
      }

      axios.post('/cart/description', {
        description: this.description,
        orderId: this.orderId,
      }).then((response) => {
        this.$emit('getDeliveryInfo')
      }).catch((e) => {
        console.log(e)
      })
    },
  },
  watch: {
    prices: {
      deep: true,
      handler() {
        this.setCoupon()
      }
    },
    orderDescription(locale) {
      this.setDescription()
    }
  },
  computed: {
    isDefault() {
      return this.products.length == 0;
    }
  },
}
</script>