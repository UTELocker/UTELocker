<template>
    <a-page-header title="Cấu hình người dùng" style="border: 1px solid rgb(235, 237, 240)">
        <div class="content">
            <div class="main">
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Mật khẩu ứng dụng</span>
                    </a-col>
                    <a-col :span="12">
                        <a-button type="primary" @click="handleClickEdit('password')">Cấu hình</a-button>
                    </a-col>
                </a-row>
                <a-divider />
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Mật khẩu cấp 2</span>
                    </a-col>
                    <a-col :span="12">
                        <a-button type="primary" @click="handleClickEdit('password_is2FA')">Cấu hình</a-button>
                    </a-col>
                </a-row>
                <a-divider />
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Xác thực đăng nhập OTP</span>
                    </a-col>
                    <a-col :span="12">
                        <a-switch
                            v-model:checked="is2FA"
                            checked-children="Bật"
                            un-checked-children="Tắt"
                            @change="changeIs2FA"
                            :loading="isloading"
                        />
                    </a-col>
                </a-row>
                <a-divider />
            </div>
        </div>
    </a-page-header>
    <a-modal
        :title="nameFiled === 'password' ? 'Cấu hình mật khẩu ứng dụng' : 'Cấu hình mật khẩu cấp 2'"
        :visible="showModal"
        @ok="submit"
        @cancel="showModal=false"
        :confirmLoading="isloading"
    >
        <a-form :form="form" layout="vertical">
            <a-form-item label="Mật khẩu cũ">
                <a-input-password v-model:value="this.form.oldPassword"
                    :maxlength="nameFiled === 'password' ? 1000 : 6"
                    :minlength="nameFiled === 'password' ? 8 : 6"
                >
                </a-input-password>
            </a-form-item>
            <a-form-item label="Mật khẩu mới">
                <a-input-password v-model:value="this.form.newPassword"
                    :maxlength="nameFiled === 'password' ? 1000 : 6"
                    :minlength="nameFiled === 'password' ? 8 : 6"
                >
                </a-input-password>
            </a-form-item>
            <a-form-item label="Nhập lại mật khẩu mới">
                <a-input-password v-model:value="this.form.confirmPassword"
                    :maxlength="nameFiled === 'password' ? 1000 : 6"
                    :minlength="nameFiled === 'password' ? 8 : 6"
                >
                </a-input-password>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script>
import {defineComponent} from "vue";
import {UserOutlined,SettingOutlined,EditOutlined} from '@ant-design/icons-vue';
import {mapState, mapActions} from "vuex";
import { Modal } from "ant-design-vue";

export default defineComponent({
    name: "userSettingsApp",
    components: {
        UserOutlined,
        SettingOutlined,
        EditOutlined,
    },
    computed: {
        ...mapState({
            user: (state) => state.moduleBase.user,
        }),
    },
    data() {
        return {
            isloading: false,
            showModal: false,
            is2FA: false,
        }
    },
    methods: {
        ...mapActions({
            updateUser: 'moduleBase/updateUser',
        }),
        submit() {
            this.isloading = true;
            this.updateUser({
                user: {
                    [this.nameFiled]: this.form.newPassword,
                    ['old_' + this.nameFiled]: this.form.oldPassword,
                    [this.nameFiled + '_confirmation']: this.form.confirmPassword,
                }
            }).then(() => {
                this.isloading = false;
                this.showModal = false;
                Modal.success({
                    title: 'Cập nhập thông tin thành công',
                    content: 'Thông tin của bạn đã được cập nhập',
                });
            }).catch((e) => {
                this.isEdit = false;
                this.isloading = false;
                Modal.error({
                    title: 'Cập nhập thông tin thất bại',
                    content: e.response?.data?.message ?? e.data.message,
                });
            });
        },
        changeIs2FA() {
            this.isloading = true;
            this.updateUser({
                user: {
                    is2FA: this.is2FA,
                }
            }).then(() => {
                this.isloading = false;
                this.showModal = false;
                Modal.success({
                    title: 'Cập nhập thông tin thành công',
                    content: 'Thông tin của bạn đã được cập nhập',
                });
            }).catch((e) => {
                this.isEdit = false;
                this.isloading = false;
                Modal.error({
                    title: 'Cập nhập thông tin thất bại',
                    content: e.response?.data?.message ?? e.data.message,
                });
            });
        },
    },
    created() {
        this.is2FA = this.user.is2FA
    }
});


</script>
