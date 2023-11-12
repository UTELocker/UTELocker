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
    GET_TRANSACTIONS: () => `${API_PREFIX}/payments/transactions`,
    POST_AUTHENTICATE: () => `${API_WALLET_PREFIX}/auth`,
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

export const TRANSACTION_STATUS = Object.freeze({
    SUCCESS: 1,
    PENDING: 0,
    FAILED: 2,
});

export const TRANSACTION_STATUS_LABELS = Object.freeze({
    [TRANSACTION_STATUS.SUCCESS]: 'Success',
    [TRANSACTION_STATUS.PENDING]: 'Pending',
    [TRANSACTION_STATUS.FAILED]: 'Failed',
});

export const TRANSACTION_STATUS_COLOR = Object.freeze({
    [TRANSACTION_STATUS.SUCCESS]: 'green',
    [TRANSACTION_STATUS.PENDING]: 'yellow',
    [TRANSACTION_STATUS.FAILED]: 'red',
});

export const TRANSACTION_TYPES = Object.freeze({
    DEPOSIT: 0,
    WITHDRAW: 1,
    TRANSFER: 2,
    PAYMENT: 3,
    REFUND: 4,
    PROMOTION: 5,
});

export const TRANSACTION_TYPES_LABELS = Object.freeze({
    [TRANSACTION_TYPES.DEPOSIT]: 'Nạp tiền',
    [TRANSACTION_TYPES.WITHDRAW]: 'Rút tiền',
    [TRANSACTION_TYPES.TRANSFER]: 'Chuyển tiền',
    [TRANSACTION_TYPES.PAYMENT]: 'Thanh toán',
    [TRANSACTION_TYPES.REFUND]: 'Hoàn tiền',
    [TRANSACTION_TYPES.PROMOTION]: 'Khuyến mãi',
});


export const TRANSACTION_TYPES_COLOR = Object.freeze({
    [TRANSACTION_TYPES.DEPOSIT]: 'blue',
    [TRANSACTION_TYPES.WITHDRAW]: 'red',
    [TRANSACTION_TYPES.TRANSFER]: 'green',
    [TRANSACTION_TYPES.PAYMENT]: 'orange',
    [TRANSACTION_TYPES.REFUND]: 'purple',
    [TRANSACTION_TYPES.PROMOTION]: 'yellow',
});
