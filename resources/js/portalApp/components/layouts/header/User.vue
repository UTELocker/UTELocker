<template>
    <a-dropdown :trigger="['click']">
        <a class="ant-dropdown-link" @click.prevent v-if="!this.isMobile">
            <a-avatar :size="40" :style="{
                float: 'left',
                marginRight: '10px',
            }"
            >
                <template #icon>
                    <UserOutlined/>
                </template>
            </a-avatar>
        </a>
        <a class="ant-dropdown-link" @click.prevent v-else>
            <p>
                {{this.user.name}}
            </p>
        </a>
        <template #overlay>
        <a-menu>
            <a-menu-item key="0">
                <a-button type="link" @click="this.$router.push({name:'user'})">Thông tin tài khoản</a-button>
            </a-menu-item>
            <a-menu-item key="1">
                <a-button type="link" @click="confirmLogout">Đăng xuất</a-button>
            </a-menu-item>
        </a-menu>
        </template>
    </a-dropdown>
</template>
<script>
import {defineComponent} from "vue";
import {UserOutlined, LogoutOutlined} from '@ant-design/icons-vue';
import {mapState} from "vuex";
import {post} from "../../../helpers/api";
import {LOGOUT_URL} from "../../../constants/bookingConstant";
export default defineComponent({
    name: "User",
    components: {
        UserOutlined,
        LogoutOutlined,
    },
    props: {
        isMobile: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapState({
            user: (state) => state.moduleBase.user,
        }),
    },
    methods: {
        confirmLogout() {
            this.$confirm({
                title: 'Bạn có chắc chắn muốn đăng xuất?',
                content: 'Sau khi đăng xuất, bạn sẽ không thể sử dụng các chức năng của hệ thống.',
                okText: 'OK',
                cancelText: 'Cancel',
                onOk: () => {
                    this.logout();
                },
                onCancel() {},
            });
        },
        logout() {
            post(LOGOUT_URL).then(() => {
                location.reload();
            });
        },
    },
    setup(props) {},
});
</script>
<style scoped>
.container {
    position: absolute;
    bottom: 0;
    left: 0;
    z-index: 9;
    width: 100%;
}
.ant-card-bordered {
    border-radius: 0;
}

</style>
