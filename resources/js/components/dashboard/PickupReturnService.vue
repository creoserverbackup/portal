<template>
    <div>
        <div class="product-main__garantie" v-html="textService"></div>
        <div class="row return-block">
            <div class="col-3 return-block__item" v-on:click="setWarranty(false)"
                 :class="{checked: selectArticle == 0}">
                <img src="images/jaar_0.png" alt="warranty">
                <div class="return-block__item-text">Geen</div>
                <div class="return-block__item-checkbox"></div>
            </div>
            <template v-for="(item, key) in otherWarranty">

                <div class="col-3 return-block__item" v-on:click="setWarranty(key, item)"
                     :class="{checked: item.selected}">
                    <!--                    <img v-bind:src="'images/jaar_'+ item.id + '.png'">-->
                    <img v-bind:src="item.image" alt="warranty">
                    <div class="return-block__item-text">€{{ item.priceOption }} excl.</div>
                    <div class="return-block__item-checkbox"></div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        otherWarranty: {},
        textService: ''
    },
    data() {
        return {
            selectArticle: 0,
        }
    },
    methods: {
        deleteExtraWarranty() {
            this.setWarranty(false)
        },
        setWarranty(number = 0) {
            this.otherWarranty.forEach((value, key) => {
                value.selected = number === key
            })
            this.$emit('addWarranty', number !== false)

        },
        checkEditWarranty() {
            let select = 0
            this.otherWarranty.forEach((value) => {
                if (value.selected) {
                    select = value.article
                }
            })
            this.selectArticle = select
        },
    },
    watch: {
        otherWarranty: {
            deep: true,
            handler(value) {
                this.checkEditWarranty()
            }
        },
    },

}
</script>
