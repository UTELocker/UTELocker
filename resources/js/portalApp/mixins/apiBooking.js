import {post} from "../helpers/api";
import {API, DONT_SHOW_POLICY_BOOKING, SHOW_POLICY_BOOKING_STATUS} from "../constants/bookingConstant";
import {mapActions, mapState} from "vuex";

export default {
    computed: {
        ...mapState({
            wallet: state => state.moduleBase.wallet,
        }),
    },
    methods: {
        ...mapActions({
            toggleIsVisibleBalance: 'moduleBase/toggleIsVisibleBalance',
        }),
        validateWallet(purchaseBooking) {
            return this.wallet.balance + this.wallet.promotion_balance < purchaseBooking;
        },
        postBooking: function () {
            if (!this.validateWallet(this.totalPrice)) {
                return new Promise((resolve, reject) => {
                    reject( {message: 'Insufficient balance'} );
                });
            }

            return new Promise((resolve, reject) => {
                post(API.POST_BOOKING(), {
                    start_date: this.startDate,
                    end_date: this.endDate,
                    list_slots_id: this.selectedSlots.map((slot) => slot.id),
                }).then((res) => {
                    if (this.isDontShowAgain) {
                        localStorage.setItem(DONT_SHOW_POLICY_BOOKING, SHOW_POLICY_BOOKING_STATUS.DONT_SHOW);
                    }
                    resolve();
                }).catch((err) => {
                    reject(err.response.data);
                });
            });
        }
    },
}
