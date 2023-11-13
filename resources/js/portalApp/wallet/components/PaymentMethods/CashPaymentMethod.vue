<template>
    <template v-if="isLoading">
        <a-skeleton active />
    </template>
    <template v-else v-for="config in paymentMethodConfig">
        <div class="mb-4">
            <HtmlRender :html="config" />
        </div>
    </template>
</template>
<script>
import {defineComponent} from "vue";
import paymentMethodMix from "../../../mixins/paymentMethodMix";
import HtmlRender from "../../../components/HtmlRender.vue";
export default defineComponent({
    name: "CashPaymentMethod",
    mixins: [paymentMethodMix],
    components: {
        HtmlRender,
    },
    props: {
        paymentMethodId: {
            type: Number,
            required: true,
        },
    },
    created() {
        this.getPaymentMethod(this.paymentMethodId);
    }
});
</script>
