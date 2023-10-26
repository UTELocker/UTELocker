import { shallowRef, ref } from "vue";
import CashPaymentMethod from "./CashPaymentMethod.vue";
import VNPayPaymentMethod from "./VNPayPaymentMethod.vue";


export const PAYMENT_METHOD_COMPONENTS = {
    cash: shallowRef(CashPaymentMethod),
    vnpay: shallowRef(VNPayPaymentMethod),
}
