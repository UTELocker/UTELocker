import {get} from "../helpers/api";
import {WALLET_API} from "../constants/walletConstant";

export default {
    data() {
        return {
            isLoading: true,
            paymentMethod: null,
            paymentMethodConfig: null,
        }
    },
    methods: {
        getPaymentMethod(methodId) {
            get(WALLET_API.GET_PAYMENT_METHOD(methodId))
                .then(response => {
                    this.isLoading = false;
                    this.paymentMethod = response.data.data;
                    this.paymentMethodConfig = response.data.config;
                }
            );
        }
    }
}
