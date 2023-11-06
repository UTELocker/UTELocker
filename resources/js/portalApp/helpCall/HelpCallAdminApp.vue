<template>
    <a-page-header
        style="border: 1px solid rgb(235, 237, 240) !important; margin-bottom: 16px"
        title="Hỗ trợ người dùng (Admin)"
        sub-title="Danh sách yêu cầu hỗ trợ người dùng"
    />
    <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="Quản lý yêu cầu hỗ trợ khách hàng">
            <help-call-admin-table />
        </a-tab-pane>
        <a-tab-pane key="2" tab="Quản lý cấu hình yêu cầu phổ biến">
            <help-call-std-table />
        </a-tab-pane>
  </a-tabs>
</template>

<script>
import { defineComponent, ref } from 'vue';
import HelpCallStdTable from './HelpCallStdTable.vue';
import HelpCallAdminTable from './HelpCallAdminTable.vue';

export default defineComponent({
    name: 'HelpCallAdminApp',
    components: {
        HelpCallStdTable,
        HelpCallAdminTable,
    },
    setup() {
        const activeKey = ref('1');
        return {
            activeKey,
        };
    },

})
</script>
