import {get, put} from "../../helpers/api";
import {API, NOTIFICATION_STATUS, NOTIFICATION_TYPE} from "../../constants/notificationConstant";

const namespaced = true;

const state = {
    notifications: [],
    notificationsPayment: [],
    notificationsBooking: [],
    newNotification: null,
    notificationUnreadCount: 0,
}

const getters = {
    notifications: state => state.notifications,
    newNotification: state => state.newNotification,
    notificationUnreadCount: state => state.notificationUnreadCount,
    notificationsPayment: state => state.notificationsPayment,
    notificationsBooking: state => state.notificationsBooking,
}

const mutations = {
    setNotifications(state, notifications) {
        state.notifications = notifications;
    },
    setNewNotification(state, notification) {
        state.newNotification = notification;
    },
    setNotificationUnreadCount(state, count) {
        state.notificationUnreadCount = count;
    },
    setNotificationsPayment(state, notifications) {
        state.notificationsPayment = notifications;
    },
    setNotificationsBooking(state, notifications) {
        state.notificationsBooking = notifications;
    },
}

const actions = {
    loadNotifications({ commit }) {
        return new Promise((resolve, reject) => {
            let notificationUnreadCount = 0;
            const notificationsBooking = [];
            const notificationsPayment = [];
            get(API.GET_NOTIFICATIONS()).then(response => {
                const data = response.data.data.data;
                const notifications = data.map(notification => {
                    if (notification.status === NOTIFICATION_STATUS.UNREAD) {
                        notificationUnreadCount++;
                    }
                    const notificationData = {
                        id: notification.id,
                        content: notification.content,
                        status: notification.status,
                        type: notification.type,
                        created_at: notification.created_at,
                        parent_id: notification.parent_id,
                        parent_table: notification.parent_table,
                    }
                    if (notificationData.type === NOTIFICATION_TYPE.BOOKING) {
                        notificationsBooking.push(notificationData);
                    } else if (notificationData.type === NOTIFICATION_TYPE.PAYMENT) {
                        notificationsPayment.push(notificationData);
                    }
                    return notificationData;
                });
                commit('setNotificationUnreadCount', notificationUnreadCount);
                commit('setNotifications', notifications);
                commit('setNotificationsBooking', notificationsBooking);
                commit('setNotificationsPayment', notificationsPayment);
                resolve();
            }).catch(error => {
                reject(error);
            });
        });
    },
    addNewNotification({ commit }, payload) {
        const { notification } = payload;
        const notificationData = {
            id: notification.id,
            content: notification.content,
            status: notification.status,
            type: notification.type,
            created_at: notification.created_at,
            parent_id: notification.parent_id,
            parent_table: notification.parent_table,
        }
        commit('setNotifications', [notificationData, ...state.notifications]);
        commit('setNewNotification', notificationData);
    },
    increaseNotificationUnreadCount({ commit }) {
        commit('setNotificationUnreadCount', state.notificationUnreadCount + 1);
    },
    decreaseNotificationUnreadCount({ commit }) {
        commit('setNotificationUnreadCount', state.notificationUnreadCount - 1);
    },
    markNotificationAsRead({ commit }, payload) {
        const { notificationChoose } = payload;
        if (notificationChoose.status === NOTIFICATION_STATUS.READ) {
            return;
        }
        return new Promise((resolve, reject) => {
            put(API.PUT_NOTIFICATION_STATUS(notificationChoose.id), {
                status: NOTIFICATION_STATUS.READ,
                type: notificationChoose.type,
             }).then(response => {
                const notifications = state.notifications.map(notification => {
                    if (notification.id === notificationChoose.id) {
                        notification.status = NOTIFICATION_STATUS.READ;
                    }
                    return notification;
                });

                commit('setNotificationUnreadCount', state.notificationUnreadCount - 1);
                commit('setNotifications', notifications);
                resolve();
            }).catch(error => {
                reject(error);
            });
        });
    },
    markAllNotificationAsRead({ commit }) {
        return new Promise((resolve, reject) => {
            put(API.PUT_NOTIFICATION_STATUS('all'), { status: NOTIFICATION_STATUS.READ }).then(response => {
                const notifications = state.notifications.map(notification => {
                    if (notification.status === NOTIFICATION_STATUS.UNREAD) {
                        notification.status = NOTIFICATION_STATUS.READ;
                    }
                    return notification;
                });

                commit('setNotificationUnreadCount', 0);
                commit('setNotifications', notifications);
                resolve();
            }).catch(error => {
                reject(error);
            });
        });
    }
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions
}
