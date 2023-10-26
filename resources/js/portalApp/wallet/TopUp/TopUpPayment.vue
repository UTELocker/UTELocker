<template>
    <a-card style="width: 100%; min-height: 100px">
        <template v-if="paymentMethodComponent">
            <component
                :is="paymentMethodComponent"
                :paymentMethodId="paymentMethodId"
            />
        </template>
    </a-card>
</template>
<script>
import {defineComponent} from "vue";
import {PAYMENT_METHOD_COMPONENTS} from "../components/PaymentMethods/mapPaymentMethod";

export default defineComponent({
    name: "TopUpPayment",
    props: {
        paymentMethodType: {
            type: String,
            required: true,
        },
        paymentMethodId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            paymentMethodComponent: null
        }
    },
    created() {
        this.paymentMethodComponent = PAYMENT_METHOD_COMPONENTS[this.paymentMethodType];
    }
});
</script>
