<template>
    <a-page-header
        sub-title="Confirm your booking"
        style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
        @back="() => $router.push({name: 'booking'})"
    >
        <template v-for="item in getDataConfirm()">
            <a-row
                style="justify-content: space-between; align-items: center;"
                :style="item.options?.stylesRow"
            >
                <p
                    style="font-size: 1rem; font-weight: 400;"
                    :style="item.options?.stylesLabel"
                >
                    {{item.label}}
                </p>
                <p
                    style="font-weight: 500; font-size: 1rem;"
                    :style="item.options?.stylesValue"
                >
                    {{item.value}}
                </p>
            </a-row>
        </template>
        <template #extra>
            <a-button danger @click="() => $router.push({name: 'booking'})">
                Cancel
            </a-button>
            <a-button type="primary" @click="submit()">
                Booking
            </a-button>
        </template>
    </a-page-header>
    <policy-modal
        :visible="this.isShowPolicyModal"
        :totalPrice="this.totalPrice"
        @close="closePolicyModal"
    />
</template>
<script>
import {defineComponent, createVNode} from "vue";
import {mapState} from "vuex";
import PolicyModal from "./PolicyModal.vue";
import postBooking from "../mixins/apiBooking.js";
import {DONT_SHOW_POLICY_BOOKING, SHOW_POLICY_BOOKING_STATUS} from "../constants/bookingConstant";
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { Modal } from 'ant-design-vue';
export default defineComponent({
    name: "ConfirmBookingApp",
    components: {
        PolicyModal,
    },
    mixins: [postBooking],
    setup() {
        return {
            size: 'large',
        };
    },
    data() {
        return {
            isLoading: false,
            isSubmitLoading: false,
            endDate: null,
            startDate: null,
            totalPrice: 0,
            isShowPolicyModal: false,
        };
    },
    methods: {
        handleTotalPrice() {
            const timeDiff = Math.abs(new Date(this.endDate).getTime() - new Date(this.startDate).getTime()) / 36e5;
            this.selectedSlots.forEach((slot) => {
                this.totalPrice += slot.config.price * timeDiff;
            });
        },
        submit() {
            Modal.confirm({
                title: 'Are you sure?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'You won\'t be able to revert this!',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk: () => {
                    const isDontShowPolicy = localStorage.getItem(DONT_SHOW_POLICY_BOOKING);
                    if (isDontShowPolicy == SHOW_POLICY_BOOKING_STATUS.DONT_SHOW) {
                        this.postBooking().then(() => {
                            Modal.success({
                                title: 'Booking success',
                                content: 'Your booking has been successfully booked',
                                onOk: () => {
                                    this.$router.push({name: 'portal'});
                                },
                            });
                        }).catch((e) => {
                            const message = e?.message || e;
                            this.isShowPolicyModal = false;
                            Modal.error({
                                title: 'Booking error',
                                content: message,
                                onOk: () => {
                                    this.$router.push({name: 'booking'});
                                },
                            });
                        });
                    } else {
                        this.isShowPolicyModal = true;
                    }
                },
                onCancel() {
                    console.log('Cancel');
                },
            });
        },
        validateRoute() {
            if (
                this.startDate === null ||
                this.endDate === null ||
                this.startDate > this.endDate ||
                this.locker === null ||
                this.selectedSlots.length === 0
            ) {
                return this.$router.push({name: 'booking'});
            }
        },
        getDataConfirm() {
            return [
                {
                    label: "Name",
                    value: this.user.name,
                },
                {
                    label: "Email",
                    value: this.user.email,
                },
                {
                    label: 'Locker code',
                    value: this.locker.code,
                },
                {
                    label: "Locker address",
                    value: this.locker.location?.address,
                },
                {
                    label: "Time booking",
                    value: this.startDate + ' - ' + this.endDate,
                    options: {
                        stylesValue: {
                            fontWeight: 'bold',
                            fontSize: '1.2rem',
                            borderRadius: '0.5rem',
                            border: '1px solid var(--border-color-base)',
                            padding: '0.5rem 1rem',
                            backgroundColor: 'var(--background-color-base)',
                        },
                    },
                },
                {
                    label: "Pick your slots",
                    value: this.selectedSlots?.map((slot) => slot.number_of_slot).join(', '),
                    options: {
                        stylesValue: {
                            color: 'orange',
                            fontSize: '1.2rem',
                        },
                    },
                },
                {
                    label: "Total Price",
                    value: parseInt(this.totalPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
                    options: {
                        stylesValue: {
                            color: 'var(--primary-color)',
                            fontWeight: 'bold',
                            fontSize: '1.2rem',
                        },
                        stylesLabel: {
                            fontWeight: 'bold',
                            fontSize: '1.2rem',
                        },
                        stylesRow: {
                            marginTop: '1rem',
                            borderTop: '2px solid var(--border-color-base)',
                        },
                    },
                }
            ]
        },
        closePolicyModal() {
            console.log('closePolicyModal');
            this.isShowPolicyModal = false;
        },
    },
    computed: {
        ...mapState({
            locker: (state) => state.moduleBooking.locker,
            selectedSlots: (state) => state.moduleBooking.selectedSlots,
            user: (state) => state.moduleBase.user,
        }),
    },
    created() {
        this.startDate = this.$route.query.startDate;
        this.endDate = this.$route.query.endDate;
        this.validateRoute();
        this.handleTotalPrice();
    }
});
</script>
