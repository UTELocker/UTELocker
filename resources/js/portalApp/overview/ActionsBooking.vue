<template>
    <a-row>
        <a-col :span="24" >
            <a-space
                style="width: 100%;"
                :size="10"
                direction="vertical"
            >
                <div
                    style="
                    font-weight: bold;
                    margin-bottom: 10px
                ">
                    Chi tiết cho ngăn {{booking.slot_code}}
                </div>
                <a-row>
                    Mã PIN (Dùng để mở ngăn)
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
                        <a-tooltip placement="top" title="Sao chép">
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
                        <a-tooltip placement="top" title="Đổi mã PIN">
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
                        <a-tooltip placement="top" title="Kết thúc">
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
                        <a-tooltip placement="top" title="Báo hỏng">
                            <a-button
                                style="width: 80px; height: 40px"
                                @click="createHelpCall()"
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
                        Thời gian đặt:
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
                            <h3>{{booking.start_date}}</h3>
                            <swap-right-outlined
                                style="font-size: 2rem; color: blue; font-weight: 600;"
                            />
                            <h3>{{booking.end_date}}</h3>
                        </a-space>
                    </a-card>
                </a-row>
            </a-space>
        </a-col>
        <a-col :span="24">
            <map-locker
                :marker="{
                    lat: booking.location.latitude,
                    lng: booking.location.longitude,
                }"
                :address="booking.address"
                :isMobile="isMobile"
            />
        </a-col>
    </a-row>
</template>
<script>
import {createVNode, defineComponent} from "vue";
import MapLocker from "./MapLocker.vue";
import { Modal } from 'ant-design-vue';
import { notification } from 'ant-design-vue';

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
        isMobile: {
            type: Boolean,
            required: false,
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
            changePinCode: 'moduleBase/',
            deleteBooking: 'moduleBase/deleteBooking',
        }),
        splitPinCode(booking) {
            console.log('booking', booking);
            return booking.pin_code.split("");
        },
        copyPinCode(booking) {
            const pinCode = this.pinCode.join("");
            navigator.clipboard.writeText(pinCode);
            notification['success']({
                message: 'Sao chép mã pin',
                description: `Sao chép mã pin ${pinCode} thành công`,
            });
        },
        handlePinCode(booking) {
            Modal.confirm({
                title: 'Thay đổi mã pin',
                content: 'Bạn có chắc chắn muốn thay đổi mã pin?',
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
                title: () => 'Bạn có chắc chắn muốn kết thúc đặt chỗ này?',
                icon: () => createVNode(ExclamationCircleOutlined),
                content: () => 'Khi kết thúc đặt chỗ, bạn sẽ không thể mở ngăn này nữa',
                onOk: () => {
                    this.deleteBooking({
                        bookingId: booking.id,
                    }).then(() => {
                        notification['success']({
                            message: 'Thành công',
                            description: 'Kết thúc đặt chỗ ' + booking.slot_code + ' thành công',
                        });
                    }).catch(() => {
                        notification['error']({
                            message: 'Thành công',
                            description: 'Kết thúc đặt chỗ thất bại',
                        });
                    });
                    this.$emit('closeDrawer');
                },
                onCancel() {},
            });
        },
        createHelpCall() {
            this.$router.push(
                {
                    name: 'help-call.create',
                    query: {
                        lockerId: this.booking.lockerId,
                        lockerSlotId: this.booking.lockerSlotId,
                        bookingId: this.booking.id,
                    },
                }
            )
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
        booking: {
            handler: function (booking) {
                this.pinCode = this.splitPinCode(booking);
            },
            deep: true,
        },
    }
});
</script>
