<template>
    <a-page-header
        style="border: 1px solid rgb(235, 237, 240) !important; margin-bottom: 16px"
        title="Hỗ trợ người dùng"
        sub-title="Tạo yêu cầu hỗ trợ"
    />
    <a-form
        :model="form"
        :layout="formLayout"
        :label-col="labelCol"
        :wrapper-col="wrapperCol"
        @finish="submit"
    >
        <a-form-item
            ref="helpCallstdProblemId"
            label="Yêu cầu hỗ trợ"
            name="helpCallstdProblemId"
            :rules="rules.helpCallstdProblemId"
        >
            <a-select
                v-model:value="form.helpCallstdProblemId"
                placeholder="Chọn yêu cầu"
                @change="
                    handleChangeHelpCallstdProblemId
                "
            >
                <template v-for="option in hepCallStandardProblem" :key="option.id">
                    <a-select-option
                        :value="option.id"
                        :type="option.type"
                    >
                        {{ option.description }}
                    </a-select-option>
                </template>
            </a-select>
        </a-form-item>
        <template v-if="form.helpCallstdProblemId == -1">
            <a-form-item
                ref="type"
                label="Loại yêu cầu"
                name="type"
                :rules="rules.type"
            >
                <a-select
                    v-model:value="form.type"
                    placeholder="Chọn loại yêu cầu"
                >
                    <template v-for="option in optionsType" :key="option.id">
                        <a-select-option
                            :value="option.value"
                        >
                            {{ option.label }}
                        </a-select-option>
                    </template>
                </a-select>
            </a-form-item>
        </template>
        <selected-detail
            :type="form.type"
            :form="form"
            @updateForm="updateFormValue"
            :key="'selected-detail'"
        ></selected-detail>
        <a-form-item
            ref="title"
            label="Tiêu đề"
            name="title"
            :rules="rules.title"
        >
            <a-input
                v-model:value="form.title"
                placeholder="Nhập tiêu đề"
            />
        </a-form-item>
        <a-form-item
            label="Nội dung"
            name="content"
            :rules="rules.content"
            ref="content"
        >
            <a-textarea
                v-model:value="form.content"
                placeholder="Nhập nội dung"
            />
        </a-form-item>
        <a-form-item
            label="File đính kèm"
            name="file"
            ref="file"
        >
            <a-upload-dragger
                v-model:fileList="fileList"
                name="file"
                :multiple="true"
                :beforeUpload="beforeUpload"
            >
                <p class="ant-upload-drag-icon">
                <inbox-outlined></inbox-outlined>
                </p>
                <p class="ant-upload-text">Click or drag file to this area to upload</p>
                <p class="ant-upload-hint">
                Support for a single or bulk upload. Strictly prohibit from uploading company data or other
                band files
                </p>
            </a-upload-dragger>
        </a-form-item>
        <a-form-item
            :wrapper-col="{ span: 24, offset: 0 }"
        >
            <a-button
                type="primary"
                html-type="submit"
                :loading="loading"
            >
                Tạo yêu cầu hỗ trợ
            </a-button>
        </a-form-item>
    </a-form>
</template>
<script>
import { defineComponent, reactive, ref, h } from 'vue';
import { notification, message } from 'ant-design-vue';
import { API, HELP_CALL_TYPE, HELP_CALL_TYPE_TEXT } from '../constants/helpCallConstant';
import { get, post } from '../helpers/api';
import SelectedDetail from './SelectedDetail.vue';
import { SmileOutlined } from '@ant-design/icons-vue';

export default defineComponent({
    name: 'HelpCallCreateApp',
    components: {
        SelectedDetail,
    },
    setup() {
        const rules = reactive({
            type: [
                {
                    required: true,
                    message: 'Vui lòng chọn loại yêu cầu',
                },
            ],
            title: [
                {
                    required: true,
                    message: 'Vui lòng nhập tiêu đề',
                },
            ],
            content: [
                {
                    required: true,
                    message: 'Vui lòng nhập nội dung',
                },
            ],
            helpCallstdProblemId: [
                {
                    required: true,
                    message: 'Vui long chọn yêu cầu hỗ trợ'
                }
            ]
        });
        const formLayout = 'vertical';
        const labelCol = { span: 4 };
        const wrapperCol = { span: 20 };
        const loading = ref(false);
        const defaultFileList = ref([]);
        const beforeUpload = (file) => {
            const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
            if (!isJpgOrPng) {
                message.error('Bạn chỉ có thể tải lên tệp JPG / PNG!');
            }
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isLt2M) {
                message.error('Hình ảnh phải nhỏ hơn 2MB!');
            }
            return false;
        };

        const optionsType = [];

        for (const [key, value] of Object.entries(HELP_CALL_TYPE)) {
            optionsType.push({
                label: HELP_CALL_TYPE_TEXT[value],
                value,
            });
        }

        optionsType.push({
            label: 'Khác',
            value: -1,
        });

        const hepCallStandardProblem = ref([]);

        get(API.GET_HELP_CALL_STD_PROBLEM()).then((res) => {
            hepCallStandardProblem.value.push(...res.data.data);
        });

        hepCallStandardProblem.value.push({
            id: -1,
            description: 'Khác',
        })

        return {
            rules,
            formLayout,
            labelCol,
            wrapperCol,
            loading,
            defaultFileList,
            beforeUpload,
            optionsType,
            hepCallStandardProblem
        };
    },
    data() {
        return {
            fileList: [],
            form: reactive({
                type: null,
                title: '',
                content: '',
                file: [],
                helpCallstdProblemId: null,
                bookingId: null,
                lockerSlotId: null,
                lockerId: null,
            })
        };
    },
    methods: {
        submit() {
            this.loading = true;
            const data = {
                ...this.form,
                attachment: this.fileList,
            }
            console.log(data);
            post(
                API.POST_HELP_CALL(),
                data,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((res) => {
                this.loading = false;
                if (res.data.status === 'success') {
                    notification.open({
                        message: 'Thành công',
                        description: res.data.data,
                        icon: () => h(SmileOutlined, { style: 'color: #108ee9' }),
                    });
                    this.$router.push({ name: 'help-call' });
                } else {
                    message.error(res.data.message);
                    notification.open({
                        message: 'Lỗi',
                        description: res.data.message,
                        icon: () => h(SmileOutlined, { style: 'color: #108ee9' }),
                    });
                }
            }).catch((err) => {
                this.loading = false;
                notification.open({
                    message: 'Lỗi',
                    description: err.message,
                    icon: () => h(SmileOutlined, { style: 'color: #108ee9' }),
                });
            });
        },
        updateFormValue({ key, value }) {
            this.form[key] = value;
        },
        handleChangeHelpCallstdProblemId(value,option) {
            this.form.type = null
            if (value == -1) {
                return
            }
            this.form.type = option.type
        }
    },
});
</script>
