import SelectPaymentMethod from "../wallet/TopUp/SelectPaymentMethod.vue";
import TopUpPayment from "../wallet/TopUp/TopUpPayment.vue";
const API_PAYMENT_PREFIX = '/api-portal/payments';
const API_WALLET_PREFIX = '/api-portal/payments/wallets';
const API_PREFIX = '/api-portal';

export const WALLET_API = Object.freeze({
    GET_WALLET: () => `${API_WALLET_PREFIX}/getWallet`,
});

export const TOPUP_STEPS = [
    {
        title: 'Select Payment Method',
        component: SelectPaymentMethod,
    },
    {
        title: 'Payment',
        component: TopUpPayment,
    },
];
