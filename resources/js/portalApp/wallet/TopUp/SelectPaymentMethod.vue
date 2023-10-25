<template>
    <a-card style="width: 100%">
        <template v-for="paymentMethod in paymentMethods">
            <a-card-grid :style="{
                width: calculateSlotWidth(paymentMethods) + '%',
                textAlign: 'center',
                backgroundColor: '#fafafa',
                cursor: 'pointer',
            }"
                :hoverable="true"
                @click="selectPaymentMethod(paymentMethod)"
            >
                <img :src="paymentMethod.image" :alt="paymentMethod.name" :style="{
                    width: '20%',
                    height: '100%',
                    objectFit: 'contain',
                }" />
            </a-card-grid>
        </template>
    </a-card>
</template>
<script>
import {defineComponent} from "vue";

export default defineComponent({
    name: "SelectPaymentMethod",
    data() {
        return {
            paymentMethods: [
                {
                    id: 1,
                    name: 'Paypal',
                    image: 'https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg',
                },
                {
                    id: 2,
                    name: 'Stripe',
                    image: 'https://stripe.com/img/v3/home/twitter.png',
                },
            ],
        }
    },
    methods: {
        selectPaymentMethod(paymentMethod) {
            this.$emit('selectPaymentMethod', paymentMethod);
        },
        calculateSlotWidth(row) {
            return 100 / row.length;
        },
    }
});
</script>
