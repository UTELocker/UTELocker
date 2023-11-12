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
            :rowKey="record => record.id"
        >
        <template
            #customFilterDropdown="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }"
            >
            <div style="padding: 8px">
                <a-input
                    ref="searchInput"
                    :placeholder="`Search ${column.dataIndex}`"
                    :value="selectedKeys[0]"
                    style="width: 188px; margin-bottom: 8px; display: block"
                    @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
                    @pressEnter="handleSearch(selectedKeys, confirm, column.dataIndex)"
                />
                <a-button
                    type="primary"
                    size="small"
                    style="width: 90px; margin-right: 8px"
                    @click="handleSearch(selectedKeys, confirm, column.dataIndex)"
                >
                <template #icon><SearchOutlined /></template>
                    Search
                </a-button>
                <a-button size="small" style="width: 90px" @click="handleReset(clearFilters)">
                    Reset
                </a-button>
            </div>
            </template>
            <template #bodyCell="{ column, text }">
                <template v-if="column.key === 'amount' || column.key === 'balance' || column.key === 'promotion_balance'">
                    {{ formatCurrency(text) }}
                </template>
                <template v-else-if="column.key === 'type'">
                    <span>
                        <a-tag
                            :color="handleTypeColor(text)"
                        >
                            {{ handleTypeLabel(text) }}
                        </a-tag>
                    </span>
                </template>
                <span v-if="state.searchText && state.searchedColumn === column.dataIndex">
                    <template
                        v-for="(fragment, i) in text
                            .toString()
                            .split(new RegExp(`(?<=${state.searchText})|(?=${state.searchText})`, 'i'))"
                    >
                        <mark
                            v-if="fragment.toLowerCase() === state.searchText.toLowerCase()"
                            :key="i"
                            class="highlight"
                        >
                            {{ fragment }}
                        </mark>
                        <template v-else>{{ fragment }}</template>
                    </template>
                </span>

                <template v-else-if="column.key == 'status'">
                    <a-tag
                        :color="handleStatusColor(text)"
                    >
                        {{ handleStatusLabel(text) }}
                    </a-tag>
                </template>
            </template>
        </a-table>
    </a-space>
</template>
<script>
import { defineComponent } from 'vue'
import walletMix from "../../mixins/walletMix";
import { SearchOutlined } from '@ant-design/icons-vue';
import { ref, reactive } from 'vue';
import {
    TRANSACTION_STATUS,
    TRANSACTION_STATUS_COLOR,
    TRANSACTION_STATUS_LABELS,
    TRANSACTION_TYPES,
    TRANSACTION_TYPES_COLOR,
    TRANSACTION_TYPES_LABELS
} from "../../constants/walletConstant";

export default defineComponent({
    name: 'Transaction',
    mixins: [walletMix],
    data() {
        return {
            loading: false,
            transactions: [],
        }
    },
    setup() {

        const searchInput = ref(null);
        const state = reactive({
            searchText: '',
            searchedColumn: '',
        });

        return {
            columns: [
                {
                    title: 'Thời gian',
                    dataIndex: 'time',
                    key: 'time',
                    sorter: (a, b) => {
                        const dateA = new Date(a.time);
                        const dateB = new Date(b.time);
                        return dateA - dateB;
                    },
                },
                {
                    title: 'Loại giao dịch',
                    dataIndex: 'type',
                    key: 'type',
                    onFilter: (value, record) => record.type === value,
                    filters: [
                        {
                            text: 'Nạp tiền',
                            value: TRANSACTION_TYPES.DEPOSIT,
                        },
                        {
                            text: 'Rút tiền',
                            value: TRANSACTION_TYPES.WITHDRAW,
                        },
                        {
                            text: 'Chuyển tiền',
                            value: TRANSACTION_TYPES.TRANSFER,
                        },
                        {
                            text: 'Thanh toán',
                            value: TRANSACTION_TYPES.PAYMENT,
                        },
                        {
                            text: 'Hoàn tiền',
                            value: TRANSACTION_TYPES.REFUND,
                        },
                        {
                            text: 'Điểm thưởng',
                            value: TRANSACTION_TYPES.PROMOTION,
                        },
                    ],
                },
                {
                    title: 'Mã giao dịch',
                    dataIndex: 'reference',
                    key: 'reference',
                    customFilterDropdown: true,
                    onFilter: (value, record) => record.reference.toString().toLowerCase().includes(value.toLowerCase()),
                    onFilterDropdownOpenChange: visible => {
                    if (visible) {
                        setTimeout(() => {
                        searchInput.value.focus();
                        }, 100);
                    }
                    },
                },
                {
                    title: 'Trạng thái',
                    dataIndex: 'status',
                    onFilter: (value, record) => record.status === value,
                    key: 'status',
                    filters: [
                        {
                            text: 'Thành công',
                            value: TRANSACTION_STATUS.SUCCESS,
                        },
                        {
                            text: 'Thất bại',
                            value: TRANSACTION_STATUS.FAIL,
                        },
                        {
                            text: 'Đang xử lý',
                            value: TRANSACTION_STATUS.PROCESSING,
                        },
                    ],
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
                    sorter (a, b) {
                        return a.amount - b.amount
                    },
                },
                {
                    title: 'Số dư',
                    dataIndex: 'balance',
                    key: 'balance',
                    sorter: (a, b) => {
                        return a.balance - b.balance
                    },
                },
                {
                    title: 'Ví điểm thưởng',
                    dataIndex: 'promotion_balance',
                    key: 'promotion_balance',
                    sorter: (a, b) => {
                        return a.promotion_balance - b.promotion_balance
                    },
                }
            ],
            searchInput,
            state
        }
    },
    methods: {
        handleSearch(selectedKeys, confirm, dataIndex) {
            confirm();
            this.state.searchText = selectedKeys[0];
            this.state.searchedColumn = dataIndex;
        },
        handleReset(clearFilters) {
            clearFilters({ confirm: true });
            this.state.searchText = '';
        },
        handleStatusColor(status) {
            return TRANSACTION_STATUS_COLOR[status];
        },
        handleStatusLabel(status) {
            return TRANSACTION_STATUS_LABELS[status];
        },
        handleTypeColor(type) {
            return TRANSACTION_TYPES_COLOR[type];
        },
        handleTypeLabel(type) {
            return TRANSACTION_TYPES_LABELS[type];
        },
    },
    created() {
        this.getTransactions();
    }
})
</script>
