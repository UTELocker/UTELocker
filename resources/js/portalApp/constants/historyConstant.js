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
