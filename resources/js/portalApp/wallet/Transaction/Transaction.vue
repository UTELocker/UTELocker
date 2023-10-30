<template>
    <a-space
        :size="5"
        direction="vertical"
        :style="{width: '100%'}"
    >
        <template v-if="!transactionId">
            <a-page-header
                title="Transaction"
                style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
                @back="() => $router.push({name: 'wallet.transactions'})"
            />
            <a-card>
                <a-result
                    status="info"
                    title="Transaction not found"
                >
                    <template #extra>
                        <a-button type="primary" @click="() => $router.push({name: 'wallet.transactions'})">
                            Back
                        </a-button>
                    </template>
                </a-result>
            </a-card>
        </template>
        <template v-else>
            <a-page-header
                title="Transaction"
                style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
                @back="() => $router.push({name: 'wallet.transactions'})"
            />
            <a-card>
                <a-result
                    :status="isDeposit ? 'success' : isWithdraw ? 'error' : 'info'"
                    :title="isDeposit ? 'Deposit' : isWithdraw ? 'Withdraw' : 'Transfer'"
                    :sub-title="transactionId"
                >
                    <template #extra>
                        <a-button type="primary" @click="() => $router.push({name: 'wallet.transactions'})">
                            Go to transaction
                        </a-button>
                        <a-button type="secondary" @click="() => $router.push({name: 'wallet.transactions'})">
                            Back
                        </a-button>
                    </template>
                </a-result>
            </a-card>
        </template>
    </a-space>
</template>
<script>
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'Transaction',
    components: {
    },
    data() {
        return {
            isDeposit: false,
            isWithdraw: false,
            isTransfer: false,
            transactionId: null,
        }
    },
    methods: {
    },
    created() {
        this.transactionId = this.$route.query.transactionId;
        this.isDeposit = this.$route.query.type === 'deposit';
        this.isWithdraw = this.$route.query.type === 'withdraw';
        this.isTransfer = this.$route.query.type === 'transfer';

        if (!this.transactionId) {
            this.$router.push({name: 'wallet.transactions'});
        }
    }
})
</script>
