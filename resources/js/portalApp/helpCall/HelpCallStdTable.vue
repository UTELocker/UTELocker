<template>
    <a-button type="primary" @click="showModal">Thêm mới</a-button>
    <a-table
        :columns="columns"
        :data-source="dataSource"
        :loading="loading"
    >
        <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex === 'type'">
                {{ renderType(record.type) }}
            </template>
            <template v-if="column.key === 'operation'">
                <a-button type="link" @click="() => handleEdit(record)">Sửa</a-button>
                <a-button type="danger" @click="() => handleDelete(record)">Xóa</a-button>
            </template>
        </template>
    </a-table>

    <a-modal
        :title="
            idSelected === ''
                ? 'Thêm mới yêu cầu phổ biến'
                : 'Sửa yêu cầu phổ biến'
                "
        :visible="visible"
        @cancel="handleCancel"
        :footer="null"
    >
        <a-form
            ref="formRef"
            :model="formState"
            :rules="rules"
            :label-col="{span: 6}"
            :wrapper-col="{span: 18}"
            @finish="() => {
                if (idSelected === '') {
                    submitCreate();
                } else {
                    submitUpdate();
                }
            }"
        >
            <a-form-item ref="typeSelected" label="Loại yêu cầu" name="typeSelected">
                <a-select
                    v-model:value="formState.typeSelected"
                    :size="size"
                    style="width: 100%;"
                    :options="optionsType"
                    :required="true"
                    placeholder="Chọn loại yêu cầu"
                ></a-select>
            </a-form-item>
            <a-form-item ref="description" label="Mô tả" name="description">
                <a-input v-model:value="formState.description" placeholder="Nhập mô tả"/>
            </a-form-item>
            <div style="text-align: right">
                <a-button @click="handleCancel" :style="{marginRight: '8px'}">
                    Hủy
                </a-button>
                <a-button type="primary" html-type="submit">Lưu</a-button>
            </div>
        </a-form>
    </a-modal>

</template>
<script>
import { defineComponent, createVNode } from 'vue';
import { get, post, del, put } from '../helpers/api';
import { API, HELP_CALL_TYPE, HELP_CALL_TYPE_TEXT } from '../constants/helpCallConstant';
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';

export default defineComponent({
  name: 'HelpCallStdTable',
  components: {},
  setup() {
    const filters = [];

    for (const value of Object.entries(HELP_CALL_TYPE)) {
        filters.push({
            text: HELP_CALL_TYPE_TEXT[value],
            value,
        });
    }

    const columns = [
        {
            title: 'STT',
            width: '5%',
            customRender: ({ text, record, index }) => index + 1,
        },
        {
            title: 'Loại',
            dataIndex: 'type',
            filters: filters,
            width: '20%',
            onFilter: (value, record) => record.type.indexOf(value) === 0,
        },
        {
            title: 'Mô tả',
            dataIndex: 'description',
            width: '60%',
        },
        {
            title: 'Hành động',
            key: 'operation',
            with: '15%',
        },
    ];

    const optionsType = [];

    for (const value of Object.entries(HELP_CALL_TYPE)) {
        optionsType.push({
            label: HELP_CALL_TYPE_TEXT[value],
            value,
        });
    }

    const rules = {
        description: [
            {
                required: true,
                message: 'Vui lòng nhập mô tả',
            },
        ],
        typeSelected: [
            {
                required: true,
                message: 'Vui lòng chọn loại yêu cầu',
            },
        ],
    };

    return {
        columns,
        optionsType,
        rules
    };
    },
    data() {
        return {
            visible: false,
            dataSource: [],
            idSelected: '',
            type: '',
            formState: {
                description: '',
                typeSelected: null,
            },
        };
    },
    methods: {
        showModal() {
            this.visible = true;
            this.idSelected = '';
            this.typeSelected = '';
            this.description = '';
        },
        handleCancel() {
            this.visible = false;
        },
        submitCreate(){
            post(API.POST_HELP_CALL_STD_PROBLEM(), {
                type: this.formState.typeSelected,
                description: this.formState.description,
            }).then((res) => {
                this.visible = false;
                Modal.success({
                    title: 'Thành công',
                    content: 'Thêm mới thành công',
                });
                this.dataSource.push(res.data.data);
            }).catch((err) => {
                this.visible = false;
                Modal.error({
                    title: 'Lỗi',
                    content: err.response.data.message || 'Có lỗi xảy ra',
                })
            });
        },
        submitUpdate(){
            put(API.PUT_HELP_CALL_STD_PROBLEM(this.idSelected), {
                type: this.formState.typeSelected,
                description: this.formState.description,
            }).then((res) => {
                this.visible = false;
                Modal.success({
                    title: 'Thành công',
                    content: 'Thêm mới thành công',
                });
                this.dataSource = this.dataSource.map((item) => {
                    if (item.id === this.idSelected) {
                        return {
                            ...item,
                            type: this.formState.typeSelected,
                            description: this.formState.description,
                        };
                    }
                    return item;
                });
            }).catch((err) => {
                this.visible = false;
                Modal.error({
                    title: 'Lỗi',
                    content: err.response.data.message || 'Có lỗi xảy ra',
                })
            });
        },
        renderType(type) {
            return HELP_CALL_TYPE_TEXT[type];
        },
        handleDelete(record) {
            Modal.confirm({
                title: 'Xác nhận',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'Bạn có chắc chắn muốn xóa?',
                okText: 'Xóa',
                cancelText: 'Hủy',
                onOk: () => {
                    del(API.DELETE_HELP_CALL_STD_PROBLEM(record.id)).then((res) => {
                        Modal.success({
                            title: 'Thành công',
                            content: 'Xóa thành công',
                        });
                        this.dataSource = this.dataSource.filter((item) => item.id !== record.id);
                    }).catch((err) => {
                        Modal.error({
                            title: 'Lỗi',
                            content: err.response.data.message || 'Có lỗi xảy ra',
                        })
                    });
                },
            });
        },
        handleEdit(record) {
            this.idSelected = record.id;
            this.typeSelected = this.renderType(record.type);
            this.type = record.type;
            this.description = record.description;
            this.visible = true;
        },
    },
    created() {
        get(API.GET_HELP_CALL_STD_PROBLEM()).then((res) => {
            this.dataSource.push(...res.data.data);
        });
    },
});
</script>
