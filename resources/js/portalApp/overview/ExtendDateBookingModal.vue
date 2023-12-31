<template>
    <a-modal
        title="Cộng thêm thời gian"
        v-model:open="visibleState"
        :onOk="onOk"
        :okText="'Thêm thời gian'"
        :cancelText="'Hủy'"
        :width="800"
        :bodyStyle="{
            maxHeight: 'calc(100vh - 200px)',
            height: 'auto',
            overflowY: 'auto',
        }"
        :loading="this.isLoaded"
    >
        <a-space :size="10" direction="vertical" style="width:100%">
            <a-row>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                    <a-date-picker
                        format="YYYY-MM-DD HH:mm"
                        :disabled-date="disabledDate"
                        show-time
                        style="width: 90%;"
                        v-model:value="value"
                        placeholder="Chọn thời gian cộng thêm"
                    />
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                    <p>
                        Thời gian cộng thêm: &nbsp;
                        {{calculateTimeExtend()[0]}} giờ &nbsp;
                        {{calculateTimeExtend()[1]}} phút
                    </p>
                </a-col>
            </a-row>
            <a-row>
                <a-space>
                    <a-button
                        type="primary"
                        :disabled="this.isLoaded"
                        @click="addExtendTime(0, 30)"
                    >
                        +30 phút
                    </a-button>
                    <a-button
                        type="primary"
                        :disabled="this.isLoaded"
                        @click="addExtendTime(1, 0)"
                    >
                        +1 tiếng
                    </a-button>
                    <a-button
                        type="primary"
                        :disabled="this.isLoaded"
                        @click="addExtendTime(2, 0)"
                    >
                        +2 tiếng
                    </a-button>
                </a-space>
            </a-row>
            <a-row
                style="display: flex; justify-content: flex-end; align-items: center; width: 100%;"
            >
                <p>
                    Tổng tiền: &nbsp;
                </p>
                <p>
                    {{calculatePrice()}}
                </p>
            </a-row>
        </a-space>
    </a-modal>

</template>
<script>
import {defineComponent, ref} from "vue";
import dayjs from "dayjs";
import {mapActions} from "vuex";
import { Modal } from 'ant-design-vue';

const value = ref();

export default defineComponent({
    name: "ExtendDateBookingModal",
    props: {
        booking: {
            type: Object,
            required: true,
        },
        visible: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            value: null,
            priceOfHour: 0,
            isLoaded: false,
        };
    },
    methods: {
        ...mapActions({
            extendTimeBooking: 'moduleBase/extendTimeBooking',
        }),
        disabledDate(current) {
            const startDate = dayjs(this.booking.end_date, 'YYYY-MM-DD HH:mm');
            return startDate.diff(current, 'days') > 0;
        },
        validateSubmit() {
            if (!this.value) {
                Modal.warning({
                    title: 'Warning',
                    content: 'Please choose date time',
                });
                return true;
            }
            const extendDate = dayjs(this.value, 'YYYY-MM-DD HH:mm');
            const endDate = dayjs(this.booking.end_date, 'YYYY-MM-DD HH:mm');

            if (extendDate.diff(endDate, 'minutes') < 30) {
                Modal.warning({
                    title: 'Warning',
                    content: 'Please choose date time greater than 30 minutes',
                });
                return true;
            }

            return false;
        },
        onOk() {
            this.isLoaded = true;
            if (this.validateSubmit()) {
                return;
            }
            this.extendTimeBooking({
                bookingId: this.booking.id,
                extendTime: this.calculateTimeExtend()[0] * 60 + this.calculateTimeExtend()[1],
            }).then(() => {
                Modal.success({
                    title: 'Thành công',
                    content: 'Booking đã được cộng thêm thời gian',
                    onOk: () => {},
                });
                this.handleClose();
            }).catch((e) => {
                this.handleClose();
                const message = e?.response?.data?.message ?? 'Có lỗi xảy ra';
                Modal.error({
                    title: 'Lỗi',
                    content: message,
                });
            });
        },
        onChange(value) {
            this.value = value;
        },
        calculateTimeExtend() {

            if (!this.value) {
                return [0, 0];
            }
            const startDate = dayjs(this.booking.end_date, 'YYYY-MM-DD HH:mm');
            const endDate = dayjs(this.value, 'YYYY-MM-DD HH:mm');
            const minutes = endDate.diff(startDate, 'minutes') ?? 0;
            const hours = Math.floor(minutes / 60);

            return [
                hours,
                minutes - hours * 60,
            ];
        },
        calculatePrice() {
            const [hours, minutes] = this.calculateTimeExtend();
            const price = this.booking.pricePerHours;
            const total = hours * price + minutes * price / 60;
            return total.toFixed(2);
        },
        addExtendTime(hours, minutes) {
            let startDate;
            if (!this.value) {
                startDate = dayjs(this.booking.end_date, 'YYYY-MM-DD HH:mm');
            }  else {
                startDate = dayjs(this.value, 'YYYY-MM-DD HH:mm');
            }
            const endDate = startDate.add(hours, 'hours').add(minutes, 'minutes');
            this.value = endDate;
        },
        handleClose() {
            this.isLoaded = false;
            this.value = null;
            this.$emit('closeModal');
        }
    },
    computed: {
        visibleState: {
            get () { return this.visible },
            set (value) {
                if (!value) {
                    this.handleClose();
                }
            },
        },
    },
})
</script>
