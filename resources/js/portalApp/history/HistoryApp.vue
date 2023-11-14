<template>
    <a-table
        :columns="columns"
        :data-source="this.historiesBooking"
        :loading="isLoaded"
    >
        <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex  === 'status'">
                <span>
                  <a-tag
                      :color="handleColorStatus(record.status)"
                  >
                    {{handleStatus(record.status) }}
                  </a-tag>
                </span>
            </template>
            <template v-else-if="column.dataIndex === 'action'">
                <span>
                  <a-button
                      type="primary"
                      @click="handleShowDetail(record)"
                >
                    Chi tiết
                </a-button>
                </span>
            </template>
            <template v-else-if="column.dataIndex === 'price'">
                <span>
                  {{ formatNumber(record) }}
                </span>
            </template>
        </template>
    </a-table>
    <a-modal
        :visible="isModalVisible"
        @ok="handleOk"
        @cancel="handleCancel"
    >
        <template #title>
            <p>Chi tiết</p>
        </template>
        <p
            style="margin-bottom: 10px;"
        >Lịch sử hoạt động:</p>
        <a-timeline>
            <a-timeline-item
                v-for="item in handleTimeLine(historyChoosed)"
                :key="item.id"
                :color="item.color"
            >
                <p>{{item.content}}</p>
            </a-timeline-item>
        </a-timeline>
        <p>Mã Tủ: {{historyChoosed?.locker_code}}</p>
        <p>Trạng thái: {{handleStatus(historyChoosed?.status)}}</p>
        <p>Địa điểm: {{historyChoosed?.address}}</p>
        <p>Tổng tiền: {{historyChoosed?.price}}</p>
        <p>Mã giao dịch: {{historyChoosed?.transaction_reference}}</p>
    </a-modal>
</template>
<script>
import {mapActions, mapState} from "vuex";
import {HISTORY_STATUS, HISTORY_STATUS_TEXT} from "../constants/historyConstant";

const filterStatus = Object.values(HISTORY_STATUS).map((status) => {
    return {
        text: HISTORY_STATUS_TEXT[status],
        value: status,
    };
});

const columns = [
    {
        title: 'Locker',
        dataIndex: 'locker_code',
        filters: [],
        filterMultiple: false,
        onFilter: (value, record) => record.locker_code.indexOf(value) === 0,
    },
    {
        title: 'Trạng thái',
        dataIndex: 'status',
        filters: filterStatus,
        onFilter: (value, record) => {
            return record.status === value;
        },
    },
    {
        title: 'Địa điểm',
        dataIndex: 'address',
        sorter: (a, b) => a.address.length - b.address.length,
        sortDirections: ['descend', 'ascend'],
    },
    {
        title: 'Thời gian bắt đầu',
        dataIndex: 'start_date',
        defaultSortOrder: 'descend',
        sorter: (a, b) => {
            const dateA = new Date(a.start_date);
            const dateB = new Date(b.start_date);
            return dateA - dateB;
        },
        sortDirections: ['descend', 'ascend'],
    },
    {
        title: 'Thời gian kết thúc',
        dataIndex: 'end_date',
        defaultSortOrder: 'descend',
        sorter: (a, b) => {
            const dateA = new Date(a.end_date);
            const dateB = new Date(b.end_date);
            return dateA - dateB;
        },
        sortDirections: ['descend', 'ascend'],
    },
    {
        title: 'Tổng tiền',
        dataIndex: 'price',
        defaultSortOrder: 'descend',
        sorter: (a, b) => a.price - b.price,
        sortDirections: ['descend', 'ascend'],
    },
    {
        title: 'Thao tác',
        dataIndex: 'action',
        scopedSlots: {customRender: 'action'},
    },
];

import { defineComponent} from "vue";

export default defineComponent({
    name: "HistoryApp",
    data() {
        return {
            columns,
            isLoaded: false,
            isModalVisible: false,
            historyChoosed: null,
            filterStatus: [],
        };
    },
    methods: {
        ...mapActions({
            loadHistoriesBooking: "moduleHistory/loadHistoriesBooking",
        }),
        handleColorStatus(status) {
            switch (status) {
                case HISTORY_STATUS.PENDING:
                    return 'purple';
                case HISTORY_STATUS.APPROVED:
                    return 'blue';
                case HISTORY_STATUS.CANCELLED:
                    return 'orange';
                case HISTORY_STATUS.EXPIRED:
                    return 'yellow';
                case HISTORY_STATUS.REJECTED:
                    return 'red';
                case HISTORY_STATUS.LOCKED:
                    return 'red';
                default:
                    return 'green';
            }
        },
        handleStatus(status) {
            return HISTORY_STATUS_TEXT[status];
        },
        handleShowDetail(record) {
            this.historyChoosed = record;
            this.isModalVisible = true;
        },
        handleOk() {
            this.isModalVisible = false;
        },
        handleCancel() {
            this.isModalVisible = false;
        },
        handleTimeLine(history){
            const timeline = [];
            timeline.push({
                id: 1,
                content: 'Request' + ' ' + new Date(history.created_at).toLocaleDateString("en-US"),
                color: 'blue',
            });
            switch (history.status){
                case HISTORY_STATUS.APPROVED:
                    timeline.push({
                        id: 2,
                        content: 'Start booking' + ' ' + history.start_date,
                        color: 'green',
                    });
                    timeline.push({
                        id: 2,
                        content: 'End booking' + ' ' + history.end_date,
                        color: 'green',
                    });
                    break;
                case HISTORY_STATUS.CANCELLED:
                    if (new Date(history.updated_at) < new Date(history.start_date)) {
                        timeline.push({
                            id: 2,
                            content: 'Cancel' + ' ' + new Date(history.updated_at),
                            color: 'red',
                        });
                    } else {
                        timeline.push({
                            id: 2,
                            content: 'Start booking' + ' ' + history.start_date,
                            color: 'red',
                        });
                        timeline.push({
                            id: 2,
                            content: 'Cancel' + ' ' + new Date(history.updated_at),
                            color: 'red',
                        });
                    }
                    const cancelDate = new Date(history.updated_at);
                    timeline.push({
                        id: 2,
                        content: 'Cancel' + ' ' + cancelDate.toLocaleDateString("vi-VN") + ' ' + cancelDate.toLocaleTimeString("vi-VN"),
                        color: 'red',
                    });
                    break;
                case HISTORY_STATUS.EXPIRED:
                    timeline.push({
                        id: 2,
                        content: 'Start booking' + ' ' + history.start_date,
                        color: 'red',
                    });
                    timeline.push({
                        id: 2,
                        content: 'End booking' + ' ' + history.end_date,
                        color: 'red',
                    });
                    const expiredDate = new Date(history.updated_at);
                    timeline.push({
                        id: 2,
                        content: 'Expired' + ' ' + expiredDate.toLocaleDateString("vi-VN") + ' ' + expiredDate.toLocaleTimeString("vi-VN"),
                        color: 'red',
                    });
                    break;
                default:
                    break;
            }
            return timeline;
        },
        formatNumber(record) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(record.price);
        },
    },
    computed: {
        ...mapState({
            historiesBooking: (state) => state.moduleHistory.historiesBooking,
            lockersHistories: (state) => state.moduleHistory.lockersHistories,
        }),
    },
    created() {
        this.isLoaded = true;
        this.loadHistoriesBooking().then(() => {
            this.isLoaded = false;
            this.columns = this.columns.map((column) => {
                if (column.dataIndex === 'locker_code') {
                    column.filters = this.lockersHistories.map((locker) => {
                        return {
                            text: locker,
                            value: locker,
                        };
                    });
                }
                return column;
            });
        });
    }
});
</script>
