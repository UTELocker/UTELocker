<template>
    <a-form
        :model="formState"
        layout="vertical"
    >
        <a-form-item label="Amount">
            <a-input-number
                v-model:value="formState.amount"
                size="large"
                :min="0"
                :max="1000000000"
                :step="10000"
                :precision="0"
                :formatter="value => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                :parser="value => value.replace(/\$\s?|(,*)/g, '')"
                :style="{width: '100%'}"
            >
                <template #prefix>
                    <span class="ant-input-suffix">VND</span>
                </template>
            </a-input-number>
        </a-form-item>
        <a-form-item label="Currency">
            <a-select
                v-model:value="formState.currency"
                size="large"
                :style="{width: '100%'}"
            >
                <a-select-option value="VND">VND</a-select-option>
            </a-select>
        </a-form-item>
        <a-form-item>
            <a-button
                type="primary"
                :loading="isLoading"
                @click="submit"
            >
                Top Up
            </a-button>
        </a-form-item>
    </a-form>
</template>
<script>
import {defineComponent, reactive} from "vue";
import depositMix from "../../../mixins/depositMix";

export default defineComponent({
    name: "VNPayPaymentMethod",
    mixins: [depositMix],
    props: {
        paymentMethodId: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            formState: reactive({
                amount: 10000,
                currency: 'VND',
            })
        }
    },
    methods: {
        submit() {
            this.deposit({
                amount: this.formState.amount,
                currency: this.formState.currency,
                payment_method_id: this.paymentMethodId,
            })
        }
    }
});
</script>
