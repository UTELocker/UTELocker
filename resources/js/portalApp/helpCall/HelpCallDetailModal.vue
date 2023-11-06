<template>
    <a-modal
        v-bind:open="open"
        title="Chi tiết yêu cầu hỗ trợ"
        :width="1000"
        @cancel="this.$emit('closeModal')"
        :loading="loading"

    >
        <template #footer>
            <a-button
                key="back"
                @click="this.$emit('closeModal')"
            >
                Đóng
            </a-button>
        </template>
        <h1>{{ helpCallDetail.title }}</h1>
        <a-row>
            <a-col :span="18">
                <a-space direction="vertical" style="width: 100%">
                    <a-page-header
                        :title="helpCallDetail.owner_name"
                        :style="{ border: '1px solid rgb(235, 237, 240)' }"
                        :sub-title="'Yêu cầu ngày ' + helpCallDetail.log_created_at"
                        :avatar="{ src: 'https://avatars1.githubusercontent.com/u/8186664?s=460&v=4' }"
                    >
                    </a-page-header>
                    <div>
                        {{ helpCallDetail.content }}
                    </div>
                    <a-upload
                        v-model:file-list="fileList"
                        action="https://www.mocky.io/v2/5cc8019d300000980a055e76"
                        list-type="picture-card"
                    >
                    </a-upload>
                    <help-call-comments :helpCall="helpCallDetail" :key="helpCallDetail.id" />
                </a-space>
            </a-col>
            <a-col :span="6" style="padding-left: 10px;">
                <a-space direction="vertical" style="width: 100%">
                    <div>
                        <h3>Trạng thái</h3>
                        <a-tag
                            :color="renderStatusColor(helpCallDetail.status)"
                            v-if="!isSupporter"
                        >
                            {{ renderStatusText(helpCallDetail.status) }}
                        </a-tag>
                        <a-select
                            :value="renderStatusText(helpCallDetail.status)"
                            style="width: 100%"
                            v-if="isSupporter"
                            @change="helpCallDetail.status = $event"
                        >
                            <a-select-option
                                v-for="status in optionsStatus"
                                :key="status.value"
                                :value="status.value"
                            >
                                {{ status.label }}
                            </a-select-option>
                        </a-select>
                    </div>
                    <div>
                        <h3>Loại</h3>
                        <span>
                            {{ renderTypeText(helpCallDetail) }}
                        </span>
                    </div>
                    <div v-if="helpCallDetail.src">
                        <h3>Thông tin chi tiêt</h3>
                        <a-row v-if="helpCallDetail.src.address">
                            <h4>Địa chỉ</h4>
                            <span>: {{ helpCallDetail.src.address }}</span>
                        </a-row>
                        <a-row v-if="helpCallDetail.src.locker_code">
                            <h4>Tủ</h4>
                            <span>: {{ helpCallDetail.src.locker_code }}</span>
                        </a-row>
                        <a-row v-if="helpCallDetail.src.slotCode">
                            <h4>Ngăn tủ</h4>
                            <span>: {{ helpCallDetail.src.slotCode }}</span>
                        </a-row>
                        <a-row v-if="helpCallDetail.src.start_date">
                            <h4>Đơn đặt ngày</h4>
                            <span>: {{ helpCallDetail.src.start_date }} - {{ helpCallDetail.src.end_date }}</span>
                        </a-row>
                    </div>
                    <div>
                        <h3>Người hỗ trợ</h3>
                        <span v-if="!isSupporter">
                            {{ renderSupporter() }}
                        </span>
                        <a-select
                            v-model:value="helpCallDetail.supporter_id"
                            style="width: 100%"
                            v-if="isSupporter"
                        >
                            <a-select-option
                                v-for="admin in listAdmin"
                                :key="admin.id"
                                :value="admin.id"
                                placeholder="Chọn người hỗ trợ"
                            >
                                {{ admin.name }}
                            </a-select-option>
                        </a-select>
                    </div>
                    <a-button
                        type="primary"
                        @click="updateHelpCall"
                        :loading="loading"
                        v-if="isSupporter"
                    >
                        Cập nhật
                    </a-button>
                </a-space>
            </a-col>
        </a-row>
    </a-modal>
</template>
<script>
import { defineComponent } from 'vue';
import { get, put } from '../helpers/api';
import { API, HELP_CALL_STATUS_COLOR, HELP_CALL_STATUS_TEXT, HELP_CALL_TYPE_TEXT, HELP_CALL_FOLDERS } from '../constants/helpCallConstant';
import { API as API_USER } from '../constants/userConstant';
import HelpCallComments from './HelpCallComments.vue';
import { notification } from 'ant-design-vue';

export default defineComponent({
    name: 'HelpCallDetailModal',
    components: {
        HelpCallComments,
    },
    props: {
        open: {
            type: Boolean,
            default: false,
        },
        helpCall: {
            type: Object,
            default: () => {},
        },
        isSupporter: {
            type: Boolean,
            default: false,
        },
    },
    setup() {
        const optionsStatus = [];

        for (const [key, value] of Object.entries(HELP_CALL_STATUS_TEXT)) {
            optionsStatus.push({
                label: value,
                value: key,
            });
        }

        return {
            optionsStatus,
        };
    },
    data() {
        return {
            helpCallDetail: {},
            loading: false,
            listAdmin: [],
            previewVisible: false,
            previewImage: '',
            previewTitle: '',
            fileList: [],
        };
    },
    watch: {
        helpCall: {
            handler() {
                this.loading = true;
                get(API.GET_SHOW_HELP_CALL(this.helpCall.id))
                    .then((res) => {
                        this.loading = false;
                        this.helpCallDetail = res.data.data;
                        this.fileList = this.helpCallDetail.attachment.split(',');
                        this.fileList = this.fileList.map((file) => {
                            return {
                                uid: file,
                                name: file,
                                status: 'done',
                                url: `${HELP_CALL_FOLDERS}${file}`,
                            };
                        });
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
            deep: true,
        },
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
        renderSupporter() {
            return this.helpCallDetail.supporter_name || 'Chưa có';
        },
        updateHelpCall() {
            this.loading = true;
            put(API.PUT_UPDATE_HELP_CALL(this.helpCallDetail.id), {
                status: this.helpCallDetail.status,
                supporterId: this.helpCallDetail.supporter_id,
            })
                .then((res) => {
                    this.loading = false;
                    notification.success({
                        message: 'Cập nhật thành công',
                    });
                    this.$emit('updateHelpCall', {
                        id : this.helpCallDetail.id,
                        status: this.helpCallDetail.status,
                        supporterName: this.listAdmin.find((admin) => admin.id === this.helpCallDetail.supporter_id)?.name,
                    });
                })
                .catch((e) => {
                    console.log(e);
                    this.loading = false;
                    notification.error({
                        message: e.message || 'Cập nhật thất bại',
                    });
                });
        },
    },
    created() {
        get(API_USER.GET_ADMINS()).then((res) => {
            this.listAdmin = res.data.data;
        });
    },
});
</script>
