export const API_HISTORY_PREFIX = '/api-portal/histories';

export const API = Object.freeze({
    GET_HISTORIES_BOOKING: () => `${API_HISTORY_PREFIX}/booking`,
});

export const HISTORY_STATUS = Object.freeze({
    PENDING: 0,
    APPROVED: 1,
    REJECTED: 2,
    CANCELLED: 3,
    EXPIRED: 4,
    COMPLETED: 5,
    LOCKED: 6,
});

export const HISTORY_STATUS_TEXT = Object.freeze({
    [HISTORY_STATUS.PENDING]: 'Đang chờ',
    [HISTORY_STATUS.APPROVED]: 'Đã duyệt',
    [HISTORY_STATUS.REJECTED]: 'Đã từ chối',
    [HISTORY_STATUS.CANCELLED]: 'Đã hủy',
    [HISTORY_STATUS.EXPIRED]: 'Đã hết hạn',
    [HISTORY_STATUS.COMPLETED]: 'Đã hoàn thành',
    [HISTORY_STATUS.LOCKED]: 'Đã khóa',
});
