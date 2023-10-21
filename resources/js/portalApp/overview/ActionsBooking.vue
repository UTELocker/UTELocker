<template>
    <a-row>
        <a-col :xs="0" :sm="0" :md="12" :lg="12" :xl="12">
            <map-locker
                :style="{
                    height: '300px',
                    width: '800px',
                }"
                :marker="{
                    lat: 10.857038362936851,
                    lng: 106.76472910149498,
                }"
                :address="'12 Nguyen Van Bao, Ward 4, Go Vap District, Ho Chi Minh City, Vietnam'"
            />
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12"
               style="padding-right: 40px; padding-left: 40px;"
        >
            <a-space
                style="
                    width: 100%;
                   "
                :size="10"
                direction="vertical"
            >
                <div
                    style="
                    font-weight: bold;
                    margin-bottom: 10px
                "
                >
                    Detail Booking Slot {{booking.slot_code}}
                </div>
                <a-row>
                    Pin Code:
                </a-row>
                <a-row>
                    <a-space
                        :style="{
                        width: '100%',
                        justifyContent: 'center',
                    }"
                        :size="20"
                    >
                        <template v-for="code in pinCode">
                            <a-button ghost disabled style="width: 50px; height: 70px">
                                <p style="font-size: 2rem; color: red; font-weight: 600;">
                                    {{code}}
                                </p>
                            </a-button>
                        </template>
                    </a-space>
                </a-row>
                <a-row>
                    <a-space
                        :style="{
                        width: '100%',
                        justifyContent: 'center',
                    }"
                        :size="20"
                    >
                        <a-tooltip placement="topLeft" title="Copy pin code">
                            <a-button
                                style="width: 80px; height: 40px"
                                @click="() => copyPinCode(booking)"
                            >
                                <template #icon>
                                    <copy-two-tone
                                        width="20px"
                                        two-tone-color="#00FF22"
                                    />
                                </template>
                            </a-button>
                        </a-tooltip>
                        <a-tooltip placement="topLeft" title="Random pin code">
                            <a-button
                                style="width: 80px; height: 40px"
                                @click="handlePinCode(booking)"
                            >
                                <template #icon>
                                    <edit-two-tone
                                        width="20px"
                                        two-tone-color="#1677ff"
                                    />
                                </template>
                            </a-button>
                        </a-tooltip>
                        <a-tooltip placement="topLeft" title="End booking">
                            <a-button
                                style="width: 80px; height: 40px"
                                @click="handleEndBooking(booking)"
                            >
                                <template #icon>
                                    <stop-two-tone
                                        width="20px"
                                        two-tone-color="#FF0000"
                                    />
                                </template>
                            </a-button>
                        </a-tooltip>
                        <a-tooltip placement="topLeft" title="Report booking">
                            <a-button
                                style="width: 80px; height: 40px"
                                @click="() => $router.push({name: 'booking'})"
                            >
                                <template #icon>
                                    <warning-two-tone
                                        width="20px"
                                        two-tone-color="#FFFF00"
                                    />
                                </template>
                            </a-button>
                        </a-tooltip>
                    </a-space>
                </a-row>
                <a-row>
                    <p>
                        Date Time:
                    </p>
                </a-row>
                <a-row
                    :style="{
                        width: '100%',
                        justifyContent: 'center',
                    }"
                >
                    <a-card>
                        <a-space
                            :style="{
                                width: '100%',
                                justifyContent: 'center',
                            }"
                            :size="10"
                        >
                            <p>
                                {{booking.start_date}}
                            </p>
                            <swap-right-outlined
                                style="font-size: 2rem; color: blue; font-weight: 600;"
                            />
                            <p>
                                {{booking.end_date}}
                            </p>
                        </a-space>
                    </a-card>
                </a-row>
                <a-row>
                    <p>
                        Status: Active
                    </p>
                </a-row>
                <a-row>
                    <p>
                        Time Remaining: {{booking.timeOut}}
                    </p>
                </a-row>
            </a-space>
        </a-col>
        <a-col :xs="24" :sm="24" :md="0" :lg="0" :xl="0">
            <map-locker
                :style="{
                    height: '150px',
                    width: '400px',
                }"
                :marker="{
                    lat: 10.857038362936851,
                    lng: 106.76472910149498,
                }"
                :address="'12 Nguyen Van Bao, Ward 4, Go Vap District, Ho Chi Minh City, Vietnam'"
            />
        </a-col>
    </a-row>
</template>
<script>
import {createVNode, defineComponent} from "vue";
import MapLocker from "./MapLocker.vue";
import { Modal } from 'ant-design-vue';

import {
    CopyTwoTone,
    WarningTwoTone,
    StopTwoTone,
    EditTwoTone,
    SwapRightOutlined, ExclamationCircleOutlined
} from '@ant-design/icons-vue';
import {mapActions, mapState} from "vuex";
export default defineComponent({
    name: "ActionsBooking",
    components: {
        MapLocker,
        CopyTwoTone,
        WarningTwoTone,
        StopTwoTone,
        EditTwoTone,
        SwapRightOutlined
    },
    props: {
        booking: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            pinCode: '',
        };
    },
    computed: {
        ...mapState({
            bookingActivities: (state) => state.moduleBase.bookingActivities,
        }),
    },
    methods: {
        ...mapActions({
            changePinCode: 'moduleBase/changePinCode',
            deleteBooking: 'moduleBase/deleteBooking',
        }),
        splitPinCode(booking) {
            return booking.pin_code.split("");
        },
        copyPinCode(booking) {
            const pinCode = this.pinCode.join("");
            navigator.clipboard.writeText(pinCode);
            Modal.success({
                title: 'Copy pin code',
                content: `Copy pin code ${pinCode} success`,
            });
        },
        handlePinCode(booking) {
            Modal.confirm({
                title: 'Change pin code',
                content: 'Do you want to change pin code?',
                okText: 'Yes',
                cancelText: 'No',
                onOk: () => {
                    this.changePinCode({
                        bookingId: booking.id,
                        pinCode: this.pinCode.join(""),
                    });
                }
            });
        },
        handleEndBooking(booking){
            Modal.confirm({
                title: () => 'Do you want to end this booking?',
                icon: () => createVNode(ExclamationCircleOutlined),
                content: () => 'Booking will be ended and you can not use this slot anymore.',
                onOk: () => {
                    this.deleteBooking({
                        bookingId: booking.id,
                    });
                    this.$emit('closeDrawer');
                },
                onCancel() {},
            });
        }
    },
    created() {
        this.pinCode = this.splitPinCode(this.booking);
    },
    watch: {
        bookingActivities: {
            handler: function (bookingActivities) {
                const bookingActivity = bookingActivities.find(
                    (bookingActivity) => bookingActivity.id === this.booking.id
                );
                if (bookingActivity) {
                    const newPinCode = bookingActivity.pin_code;
                    this.pinCode = Array.from(newPinCode.toString());
                }
            },
            deep: true,
        },
    }
});
</script>
