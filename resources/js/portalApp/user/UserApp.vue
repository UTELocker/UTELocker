<template>
    <a-space direction="vertical" style="width: 100%">
        <a-page-header title="Ảnh Đại diện" style="border: 1px solid rgb(235, 237, 240)">
            <template #extra>
                <a-button type="ghost" @click="this.$router.push({name:'user.settings'})">
                    <SettingOutlined />
                </a-button>
            </template>
            <div class="content">
            <div class="main">
                <a-row>
                    <a-col :span="24" :style="{display: 'flex', alignItems: 'center', height: '100%', justifyContent: 'center'}">
                        <a-avatar :size="90">
                            <template #icon>
                                <UserOutlined />
                            </template>
                        </a-avatar>

                    </a-col>
                </a-row>
            </div>
            </div>
        </a-page-header>

        <a-page-header title="Thông tin người dùng" style="border: 1px solid rgb(235, 237, 240)">
            <template #extra>
                <a-button type="primary" @click="this.isEdit=true" v-if="!isEdit">Cập nhập thông tin</a-button>
            </template>
            <div class="content">
            <div class="main">
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Tên người dùng</span>
                    </a-col>
                    <a-col :span="12">
                        <span v-if="!isEdit">{{this.dataUser.name}}</span>
                        <a-input v-model:value="this.dataUser.name" v-if="isEdit" :loading="isloading">
                            <template #addonAfter>
                                <edit-outlined />
                            </template>
                        </a-input>
                    </a-col>
                </a-row>
                <a-divider />
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Số điện thoại</span>
                    </a-col>
                    <a-col :span="12">
                        <span v-if="!isEdit">{{this.dataUser.mobile}}</span>
                        <a-input v-model:value="this.dataUser.mobile" v-if="isEdit" disabled :loading="isloading">
                            <template #addonAfter>
                                <edit-outlined />
                            </template>
                        </a-input>
                    </a-col>
                </a-row>
                <a-divider />
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Email</span>
                    </a-col>
                    <a-col :span="12">
                        <span v-if="!isEdit">{{this.dataUser.email}}</span>
                        <a-input v-model:value="this.dataUser.email" v-if="isEdit" disabled :loading="isloading">
                            <template #addonAfter>
                                <edit-outlined />
                            </template>
                        </a-input>
                    </a-col>
                </a-row>
                <a-divider />
                <a-row>
                    <a-col :span="12">
                        <span style="font-weight: bold; font-size: 16px;">Giới tính</span>
                    </a-col>
                    <a-col :span="12">
                        <span v-if="!isEdit">{{ renderLabelGender(this.dataUser.gender) }} </span>
                        <a-select
                            v-model:value="this.dataUser.gender"
                            style="width: 100%"
                            :options="[
                                { label: 'Nam', value: 0 },
                                { label: 'Nữ', value: 1 },
                                { label: 'Khác', value: 2 },
                            ]"
                            v-if="isEdit"
                            :loading="isloading"
                        >
                            <template #suffixIcon><edit-outlined /></template>
                        </a-select>
                    </a-col>
                </a-row>
                <a-divider />
                <a-button type="primary" @click="submit" v-if="isEdit" :loading="isloading">Cập nhập thông tin</a-button>
            </div>
            </div>
        </a-page-header>
    </a-space>
</template>
<script>
import {defineComponent} from "vue";
import {UserOutlined,SettingOutlined,EditOutlined} from '@ant-design/icons-vue';
import {mapState, mapActions} from "vuex";
import {USER_GENDER} from "../constants/userConstant";
import { Modal } from "ant-design-vue";

export default defineComponent({
    name: "userApp",
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
            isEdit: false,
            dataUser: {},
            isloading: false,
        }
    },
    methods: {
        ...mapActions({
            updateUser: 'moduleBase/updateUser',
        }),
        renderLabelGender(gender) {
            switch(gender) {
                case USER_GENDER.FEMALE:
                    return 'Nữ';
                case USER_GENDER.MALE:
                    return 'Nam';
                default:
                    return 'Khác';
            }
        },
        submit() {
            this.isloading = true;
            this.updateUser({
                user: this.dataUser
            }).then(() => {
                this.isEdit = false;
                this.isloading = false;
                Modal.success({
                    title: 'Cập nhập thông tin thành công',
                    content: 'Thông tin của bạn đã được cập nhập',
                });
            }).catch(() => {
                this.isEdit = false;
                this.isloading = false;
                Modal.error({
                    title: 'Cập nhập thông tin thất bại',
                    content: 'Vui lòng thử lại sau',
                });
            });
        }
    },
    created() {
        this.dataUser = {
            name: this.user.name,
            mobile: this.user.mobile,
            email: this.user.email,
            gender: this.user.gender,
        };
    }
});


</script>
