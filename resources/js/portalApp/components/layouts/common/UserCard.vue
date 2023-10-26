<template>
    <div class="container">
        <a-card :style="{
            width: '100%',
            height: '100%',
            paddingTop: '5px',
            paddingBottom: '5px',
            borderRadius: '0',
        }">
            <a-row>
                <a-col :span="20">
                    <a-avatar :size="64" :style="{
                        float: 'left',
                        marginRight: '10px',
                    }"
                    >
                        <template #icon>
                            <UserOutlined />
                        </template>
                    </a-avatar>
                    <a-col :span="24"
                        :style="{
                            display: 'flex',
                            alignItems: 'center',
                            height: '100%'
                        }"
                    >
                        <div>
                            <div>
                                <span style="font-weight: bold; font-size: 16px;">{{user.name}}</span>
                            </div>
                            <div>
                                <span style="font-size: 12px;">{{user.email}}</span>
                            </div>
                        </div>
                    </a-col>
                </a-col>
                <a-col :span="4"
                    :style="{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                    }"
                >
                    <div :style="{
                            float: 'right',
                        }"
                    >
                        <a-tooltip placement="top" title="Logout">
                            <a-button
                                @click="confirmLogout"
                            >
                                <logout-outlined />
                            </a-button>
                        </a-tooltip>
                    </div>
                </a-col>
            </a-row>
        </a-card>
    </div>
</template>
<script>
import {defineComponent} from "vue";
import {UserOutlined, LogoutOutlined} from '@ant-design/icons-vue';
import {mapState} from "vuex";
import {post} from "../../../helpers/api";
import {LOGOUT_URL} from "../../../constants/bookingConstant";
export default defineComponent({
    name: "UserCard",
    components: {
        UserOutlined,
        LogoutOutlined,
    },
    computed: {
        ...mapState({
            user: state => state.moduleBase.user,
        }),
    },
    methods: {
        confirmLogout() {
            this.$confirm({
                title: 'Do you want to logout?',
                content: 'When clicked the OK button, this dialog will be closed after 1 second',
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
