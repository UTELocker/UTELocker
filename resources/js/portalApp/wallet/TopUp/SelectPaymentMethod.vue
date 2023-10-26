<template>
    <a-card style="width: 100%; min-height: 100px">
        <template v-if="isLoading">
            <a-skeleton active />
        </template>
        <template v-else v-for="paymentMethod in paymentMethods">
            <a-card-grid :style="{
                width: calculateSlotWidth(paymentMethods) + '%',
                textAlign: 'center',
                backgroundColor: '#fafafa',
                cursor: 'pointer',
            }"
                :hoverable="true"
                @click="selectPaymentMethod(paymentMethod)"
            >
                <img :src="getPaymentMethodImage(paymentMethod.type)" :alt="paymentMethod.name" :style="{
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
import {get} from "../../helpers/api";
import {PAYMENT_METHOD_IMAGES, WALLET_API} from "../../constants/walletConstant";

export default defineComponent({
    name: "SelectPaymentMethod",
    data() {
        return {
            isLoading: false,
            paymentMethods: [],
        }
    },
    methods: {
        selectPaymentMethod(paymentMethod) {
            this.$emit('selectPaymentMethod', paymentMethod);
        },
        calculateSlotWidth(row) {
            return 100 / row.length;
        },
        getPaymentMethodImage(type) {
            return PAYMENT_METHOD_IMAGES[type];
        },
    },
    created() {
        this.isLoading = true;
        get(WALLET_API.GET_PAYMENT_METHODS())
            .then(response => {
                this.paymentMethods = response.data.data;
            })
            .finally(() => {
                this.isLoading = false;
            });
    }
});
</script>
