export const API_NOTIFICATION_PREFIX = '/api-portal/notifications';

export const API = Object.freeze({
    GET_NOTIFICATIONS: () => `${API_NOTIFICATION_PREFIX}/`,
    PUT_NOTIFICATION_STATUS: (notificationId) => `${API_NOTIFICATION_PREFIX}/${notificationId}/status`,
});

export const NOTIFICATION_STATUS = Object.freeze({
    UNREAD: 'N',
    READ: 'Y',
});

export const NOTIFICATION_TYPE = Object.freeze({
    PAYMENT: 'payment',
    BOOKING: 'booking',
    SUPER_ADMIN: 'super_admin',
    LOCKER_SYSTEM: 'locker_system',
    SITE_GROUP: 'site_group',
    REPORT: 'report',
});

export const NOTIFICATION_TYPE_LABEL_CHOOSE = Object.freeze({
    ALL: 'all',
    PAYMENT: 'payment',
    BOOKING: 'booking',
});

