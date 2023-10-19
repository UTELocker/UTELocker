import {post} from "../helpers/api";
import {API, DONT_SHOW_POLICY_BOOKING, SHOW_POLICY_BOOKING_STATUS} from "../constants/bookingConstant";

export default {
    methods: {
        postBooking: function (booking) {
            post(API.POST_BOOKING(), {
                start_date: this.startDate,
                end_date: this.endDate,
                list_slots_id: this.selectedSlots.map((slot) => slot.id),
            }).then((res) => {
                if (this.isDontShowAgain) {
                    localStorage.setItem(DONT_SHOW_POLICY_BOOKING, SHOW_POLICY_BOOKING_STATUS.DONT_SHOW);
                }
                this.$swal({
                    icon: 'success',
                    title: 'Your booking has been created successfully',
                    text: 'Redirecting to overview page...',
                    timer: 2000,
                    showConfirmButton: true,
                    showCancelButton: false,
                    timerProgressBar: true,
                    didOpen: () => {
                        this.$router.push({name: 'portal'});
                    },
                });
            }).catch((err) => {
                const { response } = err;
                if (response.status === 422) {
                    this.$swal({
                        icon: 'error',
                        title: 'Your booking has been created failed',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: true,
                        showCancelButton: false,
                        timerProgressBar: true,
                        didOpen: () => {
                            this.$router.push({name: 'booking'});
                        },
                    });
                    return;
                }
                this.$swal({
                    icon: 'error',
                    title: 'Server error',
                    text: 'Please try again later',
                    timer: 2000,
                    showConfirmButton: true,
                    showCancelButton: false,
                    timerProgressBar: true,
                    didOpen: () => {
                        this.$router.push({name: 'booking'});
                    },
                });
            });
        }
    },
}
