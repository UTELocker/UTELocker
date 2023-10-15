export const API_BOOKING_PREFIX = '/api-portal/bookings';
export const API_LOCKER_PREFIX = '/api-portal/lockers';
export const API_PREFIX = '/api-portal';

export const API = Object.freeze({
    GET_LOCATIONS: () => `${API_PREFIX}/locations`,
    GET_AVAILABLE_LOCKERS: (params) => `${API_LOCKER_PREFIX}/get-available?` + jQuery.param(params),
});
