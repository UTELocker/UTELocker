import {mapActions, mapState} from "vuex";

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
        }
    }
}
