export const API_LOCKER_PREFIX = '/api-portal/lockers';

export const API = Object.freeze({
    GET_LOCKERS_ACTIVITIES: () => `${API_LOCKER_PREFIX}/get-activities`,
    GET_LOCKER_SLOTS: (lockerId) => `${API_LOCKER_PREFIX}/${lockerId}/slots-short`,
});
export const LOCKER_STATUS = Object.freeze({
    AVAILABLE: 0,
    IN_USE: 1,
    UNDER_MAINTENANCE: 2,
    BROKEN: 3,
});
