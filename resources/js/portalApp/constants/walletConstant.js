import SelectPaymentMethod from "../wallet/TopUp/SelectPaymentMethod.vue";
import TopUpPayment from "../wallet/TopUp/TopUpPayment.vue";
const API_PAYMENT_PREFIX = '/api-portal/payments';
const API_WALLET_PREFIX = '/api-portal/payments/wallets';
const API_PREFIX = '/api-portal';
import vnpay from '../assets/images/payment-methods/vnpay.svg'
import cash from '../assets/images/payment-methods/cash.svg'
import zalopay from '../assets/images/payment-methods/zalopay.svg'

export const WALLET_API = Object.freeze({
    GET_WALLET: () => `${API_WALLET_PREFIX}/getWallet`,
    GET_PAYMENT_METHODS: () => `${API_PAYMENT_PREFIX}/methods`,
    GET_PAYMENT_METHOD: (methodId) => `${API_PAYMENT_PREFIX}/methods/${methodId}`,
    POST_DEPOSIT: () => `${API_PREFIX}/payments/wallets/deposit`,
    GET_TRANSACTIONS: (params) => `${API_PREFIX}/payments/transactions?` + jQuery.param(params),
});

export const TOP_UP_STEPS = [
    {
        title: 'Select Payment Method',
        component: SelectPaymentMethod,
    },
    {
        title: 'Payment',
        component: TopUpPayment,
    },
];

export const PAYMENT_METHODS = Object.freeze({
    CASH: 'cash',
    VN_PAY: 'vnpay',
    ZALO_PAY: 'zalopay',
});

export const PAYMENT_METHOD_IMAGES = Object.freeze({
    [PAYMENT_METHODS.CASH]: cash,
    [PAYMENT_METHODS.VN_PAY]: vnpay,
    [PAYMENT_METHODS.ZALO_PAY]: zalopay,
});
