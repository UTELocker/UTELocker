export const API_PREFIX_HELP_CALL = '/api-portal/help-call';

export const API = Object.freeze({
    GET_HELP_CALL_USER: () => `${API_PREFIX_HELP_CALL}/user`,
    GET_HELP_CALL_ADMIN: () => `${API_PREFIX_HELP_CALL}/admin`,
    GET_HELP_CALL_STD_PROBLEM: () => `${API_PREFIX_HELP_CALL}/std-problems`,
    POST_HELP_CALL_STD_PROBLEM: () => `${API_PREFIX_HELP_CALL}/std-problems`,
    DELETE_HELP_CALL_STD_PROBLEM: (id) => `${API_PREFIX_HELP_CALL}/std-problems/${id}`,
    PUT_HELP_CALL_STD_PROBLEM: (id) => `${API_PREFIX_HELP_CALL}/std-problems/${id}`,
    POST_HELP_CALL: () => `${API_PREFIX_HELP_CALL}`,
    GET_SHOW_HELP_CALL: (id) => `${API_PREFIX_HELP_CALL}/${id}`,
    POST_HELP_CALL_COMMENT: (id) => `${API_PREFIX_HELP_CALL}/${id}/comment`,
    PUT_UPDATE_HELP_CALL: (id) => `${API_PREFIX_HELP_CALL}/${id}`,
});

export const HELP_CALL_FOLDERS = '/user-uploads/help-call/';

export const HELP_CALL_MENU_USER = Object.freeze([
    {
        title: 'Quản lý yêu cầu hỗ trợ',
        path: '/help-call',
    },
    {
        title: 'Tạo yêu cầu hỗ trợ',
        path: '/help-call/create',
    },
]);

export const HELP_CALL_MENU_ADMIN = HELP_CALL_MENU_USER.concat([
    {
        title: 'Quản lý yêu cầu (Admin)',
        path: '/help-call/admin',
    },
]);

export const HELP_CALL_STATUS = Object.freeze({
    PENDING: 0,
    ACCEPTED: 1,
    REJECTED: 2,
    CANCELLED: 3,
    DONE: 4,
});

export const HELP_CALL_STATUS_TEXT = Object.freeze({
    [HELP_CALL_STATUS.PENDING]: 'Đang chờ',
    [HELP_CALL_STATUS.ACCEPTED]: 'Đã chấp nhận',
    [HELP_CALL_STATUS.REJECTED]: 'Đã từ chối',
    [HELP_CALL_STATUS.CANCELLED]: 'Đã hủy',
    [HELP_CALL_STATUS.DONE]: 'Đã hoàn thành',
});

export const HELP_CALL_STATUS_COLOR = Object.freeze({
    [HELP_CALL_STATUS.PENDING]: 'orange',
    [HELP_CALL_STATUS.ACCEPTED]: 'cyan',
    [HELP_CALL_STATUS.REJECTED]: 'red',
    [HELP_CALL_STATUS.CANCELLED]: 'red',
    [HELP_CALL_STATUS.DONE]: 'green',
});

export const HELP_CALL_TYPE = Object.freeze({
    LOCKER: 1,
    LOCKER_SLOT: 2,
    BOOKING: 3,
    PAYMENT: 4,
});

export const HELP_CALL_TYPE_TEXT = Object.freeze({
    [HELP_CALL_TYPE.BOOKING]: 'Đặt chỗ',
    [HELP_CALL_TYPE.LOCKER]: 'Tủ',
    [HELP_CALL_TYPE.LOCKER_SLOT]: 'Ngăn tủ',
    [HELP_CALL_TYPE.PAYMENT]: 'Thanh toán',
});

