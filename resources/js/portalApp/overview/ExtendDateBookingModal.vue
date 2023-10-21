<template>
    <a-modal
        title="Extend booking"
        v-model:visible="visibleState"
        :onOk="onOk"
        :okText="'Extend'"
        :cancelText="'Cancel'"
        :width="800"
        :bodyStyle="{
            maxHeight: 'calc(100vh - 200px)',
            height: 'auto',
            overflowY: 'auto',
        }"
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
                    />
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                    <p>
                        Time extend: {{
                            calculateTimeExtend()[0]
                        }} hours {{
                            calculateTimeExtend()[1]
                        }} minutes
                    </p>
                </a-col>
            </a-row>
            <a-row>
                <a-space>
                    <a-button
                        type="primary"
                        :disabled="validateSubmit()"
                        @click="addExtendTime(0, 30)"
                    >
                        +30 minutes
                    </a-button>
                    <a-button
                        type="primary"
                        :disabled="validateSubmit()"
                        @click="addExtendTime(1, 0)"
                    >
                        +1 hour
                    </a-button>
                    <a-button
                        type="primary"
                        :disabled="validateSubmit()"
                        @click="addExtendTime(2, 0)"
                    >
                        +2 hours
                    </a-button>
                </a-space>
            </a-row>
            <a-row
                style="display: flex; justify-content: flex-end; align-items: center; width: 100%;"
            >
                <p>
                    Total price:
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
import {API} from "../constants/bookingConstant";
import {put} from "../helpers/api";
import { Modal } from 'ant-design-vue';
import { h } from 'vue';

const dates = ref();
const value = ref();
const hackValue = ref();

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
        };
    },
    methods: {
        disabledDate(current) {
            const startDate = dayjs(this.booking.end_date, 'YYYY-MM-DD HH:mm');
            return startDate.diff(current, 'days') > 0;
        },
        validateSubmit() {
            return false;
        },
        onOk() {
            if (this.validateSubmit()) {
                return;
            }
            put(API.PUT_EXTEND_TIME(this.booking.id), {
                extend_time: this.value,
            }).then(() => {
                Modal.success({
                    title: 'Success',
                    content: 'Extend booking successfully',
                    onOk: () => {
                        this.$emit('closeModal');
                    },
                });
            }).catch((e) => {
                const message = e?.response?.data?.message ?? 'Extend booking failed';
                Modal.error({
                    title: 'Error',
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
            const price = this.booking.slot_config?.price_of_hour ?? 10000;
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
    },
    computed: {
        visibleState: {
            get () { return this.visible },
            set (value) {
                if (!value) {
                    this.$emit('closeModal');
                }
            },
        },
    },
})
</script>
