<template>
    <a-dropdown
        :placement="'bottomRight'"
        :arrow="{ pointAtCenter: true }"
        :trigger="['click']"
    >
        <a-badge :count="this.notificationUnreadCount" style="cursor: pointer;">
            <a-avatar shape="square" size="large" >
                <bell-outlined />
            </a-avatar>
        </a-badge>
        <template #overlay>
            <a-menu>
                <a-menu-item
                    v-for="notification in notifications"
                    :key="notification.id"
                    @click="handleClick(notification)"
                    style="padding: 0;"
                >
                    <a-card
                        style="
                            width: 300px;
                            margin: 0;
                            padding: 0;
                            border-bottom: 1px solid #e3e3e3;
                            border-radius: 0 !important;
                        "
                        :style="{ backgroundColor: isRead(notification) ? '#ffffff' : '#c7c7c7' }"
                        :bordered="false"
                    >
                        <a-card-meta
                            :title="handleTypeNotification(notification)"
                            :description="shortContent(notification)"
                        >
                            <template #avatar>
                                <a-avatar
                                    :shape="'square'"
                                    :size="'large'"
                                    :style="{ backgroundColor: '#d8d8d8' }"
                                >
                                    <icon-notification :type="notification.type" />
                                </a-avatar>
                            </template>
                        </a-card-meta>
                    </a-card>
                </a-menu-item>
            </a-menu>
        </template>
    </a-dropdown>
    <a-alert
        :message="handleTypeNotification(newNotification)"
        :description="newNotification.content"
        type="info"
        show-icon
        style="
            position: fixed;
            bottom: 0;
            right: 0;
            margin: 16px;
            z-index: 9999;
        "
        v-if="isShowAlert"
    />
    <a-modal
        :visible="isShowModal"
        :footer="null"
        :width="800"
        :style="{ top: '20px' }"
        @cancel="isShowModal = false"
        :closable="false"
    >
        <template #title>
            <icon-notification :type="notificationChoose.type" />
            <p>
                {{handleTypeNotification(notificationChoose)}}
            </p>
        </template>
        <p>{{notificationChoose.content}}</p>
        <template #footer>
            <a-button
                type="primary"
                @click="isShowModal = false"
            >
                Close
            </a-button>
        </template>
    </a-modal>
</template>
<script>
import {defineComponent} from "vue";
import {mapActions, mapState} from "vuex";
import {NOTIFICATION_TYPE, NOTIFICATION_STATUS} from "../../../constants/notificationConstant";
import IconNotification from "./IconNotification.vue";
import {BellOutlined} from "@ant-design/icons-vue";
import {Modal} from "ant-design-vue";
export default defineComponent({
    name: "Notification",
    components: {
        BellOutlined,
        IconNotification,
    },
    props: ['isMobile'],
    data() {
        return {
            isShowAlert: false,
            isShowModal: false,
            notificationChoose: null,
        }
    },
    computed: {
        ...mapState({
            notifications: (state) => state.moduleNotification.notifications,
            notificationUnreadCount: (state) => state.moduleNotification.notificationUnreadCount,
            newNotification: (state) => state.moduleNotification.newNotification,
            user: (state) => state.moduleBase.user,
        }),
    },
    methods: {
        ...mapActions({
            loadNotifications: 'moduleNotification/loadNotifications',
            addNewNotification: 'moduleNotification/addNewNotification',
            increaseNotificationUnreadCount: 'moduleNotification/increaseNotificationUnreadCount',
            markNotificationAsRead: 'moduleNotification/markNotificationAsRead',
        }),
        handleTypeNotification(notification) {
            switch (notification.type) {
                case NOTIFICATION_TYPE.PAYMENT:
                    return 'Payment';
                case NOTIFICATION_TYPE.BOOKING:
                    return 'Booking';
                case NOTIFICATION_TYPE.SUPER_ADMIN:
                    return 'Super Admin';
                case NOTIFICATION_TYPE.SITE_GROUP:
                    return 'Site Group';
                case NOTIFICATION_TYPE.LOCKER_SYSTEM:
                    return 'Locker System';
                case NOTIFICATION_TYPE.REPORT:
                    return 'Report';
                default:
                    return 'Notification';
            }
        },
        shortContent(notification) {
            let content = notification.content;
            if (content.length > 50) {
                return content.substring(0, 50) + '...';
            }
            return content;
        },
        listenPusher(notification){
            this.addNewNotification({
                notification: notification,
            });
            this.increaseNotificationUnreadCount();
            this.isShowAlert = true;
            setTimeout(() => {
                this.isShowAlert = false;
            }, 5000);
        },
        isRead(notification) {
            return notification.status === NOTIFICATION_STATUS.READ;
        },
        handleClick(notification) {
            this.notificationChoose = notification;
            this.markNotificationAsRead({
                notificationChoose: notification,
            }).catch((error) => {
                Modal.error({
                    title: 'Error',
                    content: error.response.data.message,
                });
            });
            this.isShowModal = true;
        }
    },
    created() {
        this.loadNotifications();
        const typeChanel = Object.values(NOTIFICATION_TYPE);
        typeChanel.forEach((type) => {
            window.Echo.channel('notification.' + type + '.' + this.user.client_id + '.' + this.user.id)
                .listen('NotificationProcessed', (e) => {
                    this.listenPusher(e.notification);
                });
        });
    }
});
</script>
