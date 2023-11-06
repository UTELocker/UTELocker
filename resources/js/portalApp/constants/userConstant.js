const API_USER_PREFIX = '/api-portal/user';

export const API = Object.freeze({
    POST_AUTHENTICATE: () => `${API_WALLET_PREFIX}/auth`,
    GET_ADMINS: () => `${API_USER_PREFIX}/list-admins`,
});

export const USER_FOLDERS = '/user-uploads/user-avatar/';

export const USER_GENDER = Object.freeze({
    MALE: 0,
    FEMALE: 1,
    OTHER: 2,
});

export const USER_TYPE = Object.freeze({
    ADMIN: 1,
    USER: 2,
});
