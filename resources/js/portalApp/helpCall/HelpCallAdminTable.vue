<template>

    <a-table
        :columns="columns"
        :data-source="dataSource"
        :loading="loading"
    >
    <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'type'">
            <span>
                {{ renderTypeText(record) }}
            </span>
        </template>
        <template v-if="column.dataIndex === 'status'">
            <span>
                <a-tag
                    :color="renderStatusColor(record.status)"
                >
                    {{ renderStatusText(record.status) }}
                </a-tag>
            </span>
        </template>
        <template v-if="column.key === 'operation'">
            <a-button
                type="link"
                @click="showHelpCall(record)"
            >
                Chi tiết
            </a-button>
        </template>
    </template>
</a-table>
    <help-call-detail-modal
        :open="open"
        :helpCall="recordSelected"
        :isSupporter="true"
        @updateHelpCall="updateHelpCall"
        @closeModal="closeModal"
    />
</template>
<script>
import { defineComponent } from 'vue';
import { get } from '../helpers/api';
import { API, HELP_CALL_STATUS_COLOR, HELP_CALL_STATUS_TEXT, HELP_CALL_TYPE_TEXT } from '../constants/helpCallConstant';
import HelpCallDetailModal from './HelpCallDetailModal.vue';

export default defineComponent({
  name: 'HelpCallAdminTable',
  components: {
    HelpCallDetailModal
  },
  setup() {
      const columns = [
            {
                title: 'STT',
                dataIndex: 'index',
                width: '5%',
                customRender: ({ text, record, index }) => index + 1,
            },
            {
                title: 'Loại',
                dataIndex: 'type',
                width: '10%',
                sorter: (a, b) => a.type - b.type,
            },
            {
                title: 'TÌnh trạng',
                dataIndex: 'status',
                filters: Object.entries(HELP_CALL_STATUS_TEXT).map(([key, value]) => ({
                        text: value,
                        value: key,
                    })),
                width: '10%',
                onFilter: (value, record) => record.status == value,
            },
            {
                title: 'Tiêu đề',
                dataIndex: 'title',
                with: '35%',
            },
            {
                title: 'Người yêu cầu',
                dataIndex: 'owner_name',
                with: '10%',
            },
            {
                title: 'Người hỗ trợ',
                dataIndex: 'supporter_name',
                with: '10%',
            },
            {
                title: 'Ngày tạo',
                dataIndex: 'log_created_at',
                with: '10%',
                sorter: (a, b) => {
                    const dateA = new Date(a.log_created_at);
                    const dateB = new Date(b.log_created_at);
                    return dateA - dateB;
                },
                defaultSortOrder: 'descend',
            },
            {
                title: 'Hành động',
                key: 'operation',
                with: '10%',
            },
        ];

      return {
          columns,
      };
    },
    data() {
        return {
            dataSource: [],
            loading: false,
            open: false,
            recordSelected: {},
        };
    },
    methods: {
        renderStatusText(status) {
            return HELP_CALL_STATUS_TEXT[status];
        },
        renderStatusColor(status) {
            return HELP_CALL_STATUS_COLOR[status];
        },
        renderTypeText(record) {
            if (record.std_problem_description !== null) {
                return record.std_problem_description;
            }
            return HELP_CALL_TYPE_TEXT[record.type];
        },
        showHelpCall(record) {
            this.open = true;
            this.recordSelected = record;
        },
        closeModal() {
            this.open = false;
        },
        updateHelpCall (helpCallUpdate) {
            this.dataSource = this.dataSource.map((helpCall) => {
                if (helpCall.id === helpCallUpdate.id) {
                    return {
                        ...helpCall,
                        status: helpCallUpdate.status,
                        supporter_name: helpCallUpdate.supporterName,
                    };
                }
                return helpCall;
            });
        }
    },
    created() {
        get(API.GET_HELP_CALL_ADMIN()).then((res) => {
            this.dataSource.push(...res.data.data);
        });
    },
});
</script>
