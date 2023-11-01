import {mapActions, mapState} from "vuex";
import {get} from "../helpers/api";
import {WALLET_API} from "../constants/walletConstant";

export default {
    computed: {
        ...mapState({
            wallet: state => state.moduleBase.wallet,
            isVisibleBalance: state => state.moduleBase.isVisibleBalance,
        }),
    },
    methods: {
        ...mapActions({
            toggleIsVisibleBalance: 'moduleBase/toggleIsVisibleBalance',
        }),
        formatCurrency: function (value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        },
        getWalletBalance: function () {
            return this.isVisibleBalance ? this.formatCurrency(this.wallet.balance) : '***';
        },
        getWalletPoints: function () {
            return this.isVisibleBalance ? this.formatCurrency(this.wallet.promotion_balance) : '***';
        },
        getTransactions: function (
            page = 1,
            perPage = 10,
            orderBy = 'created_at',
            order = 'desc',
        ) {
            this.loading = true;
            get(WALLET_API.GET_TRANSACTIONS({
                page,
                perPage,
                orderBy,
                order,
            }))
                .then(response => {
                    this.transactions = response.data.data;
                    this.pagination = {
                        ...this.pagination,
                        ...response.data.meta,
                    }
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
    }
}
