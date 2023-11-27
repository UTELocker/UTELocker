import {post} from "../helpers/api";
import {WALLET_API} from "../constants/walletConstant";

export default {
    data() {
        return {
            isLoading: false,
        }
    },
    methods: {
        deposit: function (data) {
            this.isLoading = true;
            post(WALLET_API.POST_DEPOSIT(), data)
                .then((res) => {
                    window.location.href = res.data.redirectUrl;
                })
                .catch((err) => {
                })
                .finally(() => {
                    this.isLoading = false;
                })
        },
    }
}
