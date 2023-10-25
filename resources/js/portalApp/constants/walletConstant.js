const API_PAYMENT_PREFIX = '/api-portal/payments';
const API_WALLET_PREFIX = '/api-portal/payments/wallets';
const API_PREFIX = '/api-portal';

export const WALLET_API = Object.freeze({
    GET_WALLET: () => `${API_WALLET_PREFIX}/getWallet`,
});
