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
                    Detail
                </a-button>
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
            <p>Detail</p>
        </template>
        <p
            style="margin-bottom: 10px;"
        >Time Line:</p>
        <a-timeline>
            <a-timeline-item
                v-for="item in handleTimeLine(historyChoosed)"
                :key="item.id"
                :color="item.color"
            >
                <p>{{item.content}}</p>
            </a-timeline-item>
        </a-timeline>
        <p>Locker Code: {{historyChoosed?.locker_code}}</p>
        <p>Status: {{handleStatus(historyChoosed?.status)}}</p>
        <p>Address: {{historyChoosed?.address}}</p>
        <p>Price: {{historyChoosed?.total_price}}</p>
    </a-modal>
</template>
<script>
import {mapActions, mapState} from "vuex";

const columns = [
    {
        title: 'Locker',
        dataIndex: 'locker_code',
        filters: [],
        filterMultiple: false,
        onFilter: (value, record) => record.locker_code.indexOf(value) === 0,
    },
    {
        title: 'Status',
        dataIndex: 'status',
        defaultSortOrder: 'descend',
        sorter: (a, b) => a.status - b.status,
    },
    {
        title: 'Address',
        dataIndex: 'address',
        sorter: (a, b) => a.address.length - b.address.length,
        sortDirections: ['descend', 'ascend'],
    },
    {
        title: 'Start Date',
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
        title: 'End Date',
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
        title: 'Price',
        dataIndex: 'total_price',
        defaultSortOrder: 'descend',
        sorter: (a, b) => a.total_price - b.total_price,
        sortDirections: ['descend', 'ascend'],
    },
    {
        title: 'Action',
        dataIndex: 'action',
        scopedSlots: {customRender: 'action'},
    },
];

import { defineComponent} from "vue";
import {HISTORY_STATUS} from "../constants/hisgoryConstant";

export default defineComponent({
    name: "HistoryApp",
    data() {
        return {
            columns,
            isLoaded: false,
            isModalVisible: false,
            historyChoosed: null,
        };
    },
    methods: {
        ...mapActions({
            loadHistoriesBooking: "moduleHistory/loadHistoriesBooking",
        }),
        handleColorStatus(status) {
            switch (status) {
                case HISTORY_STATUS.PENDING:
                    return 'yellow';
                case HISTORY_STATUS.APPROVED:
                    return 'green';
                case HISTORY_STATUS.CANCELED:
                    return 'red';
                case HISTORY_STATUS.EXPIRED:
                    return 'red';
                case HISTORY_STATUS.REJECTED:
                    return 'orange';
                default:
                    return 'green';
            }
        },
        handleStatus(status) {
            switch (status) {
                case HISTORY_STATUS.PENDING:
                    return 'Pending';
                case HISTORY_STATUS.APPROVED:
                    return 'Approved';
                case HISTORY_STATUS.CANCELED:
                    return 'Canceled';
                case HISTORY_STATUS.EXPIRED:
                    return 'Expired';
                case HISTORY_STATUS.REJECTED:
                    return 'Rejected';
                default:
                    return 'default';
            }
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
                case HISTORY_STATUS.CANCELED:
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
                    break;
                default:
                    break;
            }
            return timeline;
        }
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
