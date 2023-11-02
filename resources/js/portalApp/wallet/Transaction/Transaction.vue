<template>
    <a-space
        :size="20"
        direction="vertical"
        :style="{width: '100%'}"
    >
        <a-page-header
            title="Lịch sử giao dịch"
            style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
            @back="() => $router.push({name: 'wallet'})"
        />
        <a-card
            :style="{width: '100%'}"
        >
            <a-row
                :gutter="15"
                :style="{width: '100%'}"
            >
            </a-row>
        </a-card>
        <a-table
            :columns="columns"
            :dataSource="transactions"
            :loading="loading"
            :pagination="pagination"
            @change="handleTableChange"
            :rowKey="record => record.id"
        >
            <template #bodyCell="{ column, text }">
                <template v-if="column.key === 'amount' || column.key === 'balance' || column.key === 'promotion_balance'">
                    {{ formatCurrency(text) }}
                </template>
                <template v-else-if="column.key === 'type'">
                    <span>
                        <a-tag
                            v-if="text === 'Deposit'"
                            color="green"
                        >
                            Nạp tiền
                        </a-tag>
                        <a-tag
                            v-else-if="text === 'Withdraw'"
                            color="red"
                        >
                            Rút tiền
                        </a-tag>
                        <a-tag
                            v-else-if="text === 'Transfer'"
                            color="blue"
                        >
                            Chuyển tiền
                        </a-tag>
                        <a-tag
                            v-else-if="text === 'Payment'"
                            color="purple"
                        >
                            Thanh toán
                        </a-tag>
                    </span>
                </template>
            </template>
        </a-table>
    </a-space>
</template>
<script>
import { defineComponent } from 'vue'
import walletMix from "../../mixins/walletMix";
export default defineComponent({
    name: 'Transaction',
    mixins: [walletMix],
    data() {
        return {
            loading: false,
            transactions: [],
            pagination: {
                current: 1,
                total: 0,
                perPage: 20,
                orderBy: 'time',
                order: 'desc',
            },
        }
    },
    setup() {
        return {
            columns: [
                {
                    title: 'Thời gian',
                    dataIndex: 'time',
                    key: 'time',
                },
                {
                    title: 'Loại giao dịch',
                    dataIndex: 'type',
                    key: 'type',
                },
                {
                    title: 'Mã giao dịch',
                    dataIndex: 'reference',
                    key: 'reference',
                },
                {
                    title: 'Trạng thái',
                    dataIndex: 'status',
                    key: 'status',
                },
                {
                    title: 'Nội dung',
                    dataIndex: 'content',
                    key: 'content',
                },
                {
                    title: 'Số tiền',
                    dataIndex: 'amount',
                    key: 'amount',
                },
                {
                    title: 'Số dư',
                    dataIndex: 'balance',
                    key: 'balance',
                },
                {
                    title: 'Ví điểm thưởng',
                    dataIndex: 'promotion_balance',
                }
            ],
        }
    },
    methods: {
        handleTableChange(page, perPage, order, orderBy) {
            this.getTransactions(page, perPage, order, orderBy);
        },
    },
    created() {
        this.getTransactions(
            this.pagination.current,
            this.pagination.perPage,
            this.pagination.orderBy,
            this.pagination.order,
        );
    }
})
</script>
