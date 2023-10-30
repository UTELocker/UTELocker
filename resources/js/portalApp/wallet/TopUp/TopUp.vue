<template>
    <a-space
        :size="20"
        direction="vertical"
        style="width: 100%;"
    >
        <a-page-header
            title="Top Up"
            style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
            @back="() => $router.push({name: 'wallet'})"
        />
        <a-card>
            <a-steps :current="current" :items="items"></a-steps>
            <div class="steps-content">
                <component
                    :is="TOP_UP_STEPS[current].component"
                    @selectPaymentMethod="selectPaymentMethod"
                    :paymentMethodType="paymentMethodType"
                    :paymentMethodId="paymentMethodId"
                />
            </div>
            <div class="steps-action">
                <a-button v-if="current > 0" style="margin-left: 8px;" @click="prev">Previous</a-button>
            </div>
        </a-card>
    </a-space>
</template>
<script>
import {defineComponent, ref} from "vue";
import {TOP_UP_STEPS} from "../../constants/walletConstant";

export default defineComponent({
    name: "TopUp",
    components: {},
    data() {
        return {
            amount: 0,
            loading: false,
            paymentMethodType: null,
            paymentMethodId: null,
            current: ref(0),
        }
    },
    setup() {
        const items = TOP_UP_STEPS.map(item => ({ key: item.title, title: item.title }));
        return {
            items,
            TOP_UP_STEPS,
        }
    },
    methods: {
        next() {
            this.current++;
        },
        prev() {
            this.current--;
        },
        selectPaymentMethod(paymentMethod) {
            this.paymentMethodType = paymentMethod.type;
            this.paymentMethodId = paymentMethod.id;
            this.next();
        },
    },
})
</script>
<style scoped>
.steps-content {
    margin-top: 16px;
    border: 1px dashed #e9e9e9;
    border-radius: 6px;
    background-color: #fafafa;
}

.steps-action {
    margin-top: 24px;
}

[data-theme='dark'] .steps-content {
    background-color: #2f2f2f;
    border: 1px dashed #404040;
}
</style>
