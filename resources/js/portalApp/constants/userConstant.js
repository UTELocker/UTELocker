const API_PAYMENT_PREFIX = '/api-portal/user';
const API_WALLET_PREFIX = '/api-portal/user/settings';

export const WALLET_API = Object.freeze({
    GET_WALLET: () => `${API_WALLET_PREFIX}/getWallet`,
    GET_PAYMENT_METHODS: () => `${API_PAYMENT_PREFIX}/methods`,
    GET_PAYMENT_METHOD: (methodId) => `${API_PAYMENT_PREFIX}/methods/${methodId}`,
    POST_DEPOSIT: () => `${API_PREFIX}/payments/wallets/deposit`,
    GET_TRANSACTIONS: (params) => `${API_PREFIX}/payments/transactions?` + jQuery.param(params),
    POST_AUTHENTICATE: () => `${API_WALLET_PREFIX}/auth`,
});

export const USER_GENDER = Object.freeze({
    MALE: 0,
    FEMALE: 1,
    OTHER: 2,
});
