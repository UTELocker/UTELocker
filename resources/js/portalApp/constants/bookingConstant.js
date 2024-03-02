export const API_BOOKING_PREFIX = '/api-portal/bookings';
export const API_LOCKER_PREFIX = '/api-portal/lockers';
export const API_PREFIX = '/api-portal';

export const API = Object.freeze({
    GET_LOCATIONS: () => `${API_PREFIX}/locations`,
    GET_AVAILABLE_LOCKERS: (params) => `${API_LOCKER_PREFIX}/get-available?` + jQuery.param(params),
    GET_LOCKER_SLOTS: (lockerId, params) => `${API_LOCKER_PREFIX}/${lockerId}/slots?` + jQuery.param(params),
    POST_BOOKING: () => `${API_BOOKING_PREFIX}`,
    GET_BOOKING_ACTIVITIES: (params) => `${API_BOOKING_PREFIX}/activities?` + jQuery.param(params),
    PUT_EXTEND_TIME: (bookingId) => `${API_BOOKING_PREFIX}/${bookingId}/extend-time`,
    DEL_END_BOOKING: (bookingId) => `${API_BOOKING_PREFIX}/${bookingId}`,
    POST_CHANGE_PIN_CODE: () => `${API_BOOKING_PREFIX}/change-password`,
    PUT_UPDATE_USER: () => `${API_PREFIX}/user`,
    GET_BOOKING_OF_SLOT: (lockerSlotId) => `${API_BOOKING_PREFIX}/slot/${lockerSlotId}`,
    GET_OPEN_LOCKER: (bookingId) => `${API_BOOKING_PREFIX}/open-locker/${bookingId}`,
});

export const LOGOUT_URL = '/logout';

export const SLOT_TYPE = Object.freeze({
    CPU: 'CPU',
    SLOT: 'SLOT',
    EMPTY: 'EMPTY',
});

export const SLOT_STATUS = Object.freeze({
    AVAILABLE: 0,
    BOOKED: 1,
    LOCKED: 2,
});

export const LIMIT_BOOKING_SLOTS = 3;

export const DONT_SHOW_POLICY_BOOKING = 'isDontShowPolicyBooking';

export const SHOW_POLICY_BOOKING_STATUS = Object.freeze({
    SHOW: 0,
    DONT_SHOW: 1,
});

export const BOOKING_ACTIVITY_STATUS = Object.freeze({
    NOT_YET: 0,
    ACTIVE: 1,
    EXPIRED: 4,
});

export const BOOKING_STATUS_TEXT = Object.freeze({
    [BOOKING_ACTIVITY_STATUS.NOT_YET]: 'Chưa sử dụng',
    [BOOKING_ACTIVITY_STATUS.ACTIVE]: 'Đang sử dụng',
    [BOOKING_ACTIVITY_STATUS.EXPIRED]: 'Đã hết hạn',
});

