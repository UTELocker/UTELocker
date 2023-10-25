<template>
    <a-popover v-model:open="visible" title="" trigger="click" placement="bottomRight" overlay-class-name="popover-menu">
        <template #content
            :style="{
                width: '400px',
            }"
        >
            <div style="display: flex; justify-content: space-between; align-items: center; marginBottom: 10px">
                <h3 style="margin: 0;">Notifications</h3>
                <a-button
                    type="link"
                    size="small"
                    @click=markAllAsRead()
                >
                    Mark all as read
                </a-button>
            </div>
            <a-space
                :direction="'horizontal'"
            >
                <template v-for="type in listTypeNotification">
                    <a-button
                        :type="isTypeNotificationChoose(type) ? 'primary' : 'default'"
                        size="small"
                        @click="typeNotificationChoose = type"
                    >
                        {{
                            handleTypeNotification({
                                type: type,
                            })
                        }}
                    </a-button>
                </template>
            </a-space>
            <a-list
                item-layout="horizontal"
                :data-source="getListNotification()"
                :style="{
                    width: '100%',
                    height: '50vh',
                    overflow: 'auto',
                    marginTop: '10px',
                }"
            >
                <template #renderItem="{ item }">
                    <a-list-item :key="item.id" v-if="filterNotification(item)" @click=handleClick(item)>
                        <template #extra>
                            <a-badge status="processing" v-if="!isRead(item)"/>
                        </template>
                        <a-list-item-meta
                            :description="'Created at: ' + handleDate(item)"
                            style="align-items: center !important;"
                        >
                        <template #title>
                            <a href="/">{{ shortContent(item) }}</a>
                        </template>
                        <template #avatar>
                            <component :is="handleIconNotification(item)" style="margin-right: 8px" />
                        </template>
                        </a-list-item-meta>
                    </a-list-item>
                </template>
            </a-list>
        </template>
        <a-badge
            :count="this.notificationUnreadCount"
            style="cursor: pointer;"
            @click="visible = !visible"
            :class="isMobile ? 'nav-phone-badge' : ''"
        >
            <a-avatar shape="square" size="large" >
                <bell-outlined />
            </a-avatar>
        </a-badge>
    </a-popover>
    <a-modal
        v-model:open="isShowModal"
        :footer="null"
        :width="800"
        :style="{ top: '20px' }"
        @cancel="isShowModal = false"
        :closable="false"
    >
        <template #title>
            <component :is="handleIconNotification(notificationChoose.type)" style="margin-right: 8px" />
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
import {NOTIFICATION_TYPE, NOTIFICATION_STATUS, NOTIFICATION_TYPE_LABEL_CHOOSE} from "../../../constants/notificationConstant";
import {BellOutlined} from "@ant-design/icons-vue";
import {Modal} from "ant-design-vue";
import {
    CarryOutTwoTone,
    DollarTwoTone,
    HddTwoTone,
    SettingTwoTone,
    SlidersTwoTone,
    WarningTwoTone,
} from "@ant-design/icons-vue";
import { notification } from 'ant-design-vue';

export default defineComponent({
    name: "Notification",
    components: {
        BellOutlined,
        CarryOutTwoTone,
        DollarTwoTone,
        HddTwoTone,
        SettingTwoTone,
        SlidersTwoTone,
        WarningTwoTone,
    },
    props: ['isMobile'],
    data() {
        return {
            visible: false,
            isShowModal: false,
            notificationChoose: null,
            typeNotificationChoose: NOTIFICATION_TYPE_LABEL_CHOOSE.ALL,
            listTypeNotification: Object.values(NOTIFICATION_TYPE_LABEL_CHOOSE),
        }
    },
    computed: {
        ...mapState({
            notifications: (state) => state.moduleNotification.notifications,
            notificationsPayment: (state) => state.moduleNotification.notificationsPayment,
            notificationsBooking: (state) => state.moduleNotification.notificationsBooking,
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
            markAllNotificationAsRead: 'moduleNotification/markAllNotificationAsRead',
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
                    return 'All';
            }
        },
        handleIconNotification(notification) {
            switch (notification.type) {
                case NOTIFICATION_TYPE.PAYMENT:
                    return DollarTwoTone;
                case NOTIFICATION_TYPE.BOOKING:
                    return CarryOutTwoTone;
                case NOTIFICATION_TYPE.SUPER_ADMIN:
                    return SettingTwoTone;
                case NOTIFICATION_TYPE.SITE_GROUP:
                    return SlidersTwoTone;
                case NOTIFICATION_TYPE.LOCKER_SYSTEM:
                    return HddTwoTone;
                case NOTIFICATION_TYPE.REPORT:
                    return WarningTwoTone;
                default:
                    return BellOutlined;
            }
        },
        shortContent(notification) {
            let content = notification.content;
            if (content.length > 50) {
                return content.substring(0, 50) + '...';
            }
            return content;
        },
        listenPusher(item) {
            this.addNewNotification({
                notification: item,
            });
            this.increaseNotificationUnreadCount();
            notification['info']({
                message: this.handleTypeNotification(item),
                description: item.content,
            });
        },
        isRead(notification) {
            return notification.status === NOTIFICATION_STATUS.READ;
        },
        handleClick(notification) {
            switch (notification.type) {
                case NOTIFICATION_TYPE.PAYMENT:
                    return false;
                case NOTIFICATION_TYPE.BOOKING:
                    return false;
                case NOTIFICATION_TYPE.LOCKER_SYSTEM:
                    return false;
                case NOTIFICATION_TYPE.SITE_GROUP:
                case NOTIFICATION_TYPE.SUPER_ADMIN:
                case NOTIFICATION_TYPE.REPORT:
                default:
                    this.notificationChoose = notification;
                    this.markNotificationAsRead({
                        notificationChoose: notification,
                    }).then(() => {
                        this.isShowModal = true;
                        this.visible = false;
                    }).
                    catch((error) => {
                        Modal.error({
                            title: 'Error',
                            content: error.response.data.message,
                        });
                    });
            }
        },
        handleDate(notification) {
            return notification.created_at.substring(8, 10)
            + '/' + notification.created_at.substring(5, 7)
            + ' ' + notification.created_at.substring(11, 16);
        },
        isTypeNotificationChoose(type) {
            return this.typeNotificationChoose === type;
        },
        filterNotification(notification) {
            if (this.typeNotificationChoose === NOTIFICATION_TYPE_LABEL_CHOOSE.ALL) {
                return true;
            }
            return notification.type === this.typeNotificationChoose;
        },
        getListNotification() {
            switch (this.typeNotificationChoose) {
                case NOTIFICATION_TYPE_LABEL_CHOOSE.ALL:
                    return this.notifications;
                case NOTIFICATION_TYPE_LABEL_CHOOSE.PAYMENT:
                    return this.notificationsPayment;
                case NOTIFICATION_TYPE_LABEL_CHOOSE.BOOKING:
                    return this.notificationsBooking;
                default:
                    return this.notifications;
            }
        },
        markAllAsRead() {
            this.markAllNotificationAsRead().catch((error) => {
                Modal.error({
                    title: 'Error',
                    content: error.response.data.message,
                });
            });
        },
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

<style scoped>
.ant-list-item:hover {
  background-color: #f5f5f5 !important;
}
.ant-list-item {
  padding: 5px !important;
}
</style>
