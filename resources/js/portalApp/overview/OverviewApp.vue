<template>
    <a-page-header
        title="Trang chủ"
        sub-title="Danh sách các tủ đang đặt"
        style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
        :back-icon="false"
    >
        <template #extra>
            <a-button type="primary" @click="() => $router.push({name: 'booking'})">
                Đặt tủ ngay
            </a-button>
        </template>
    </a-page-header>
    <a-space
        direction="vertical"
        :style="{
            width: '100%',
            margin: '1rem 0',
        }"
    >
        <a-row v-if="isLoading">
            <a-col :span="24">
                <a-skeleton active />
            </a-col>
        </a-row>
        <a-row v-else>
            <a-col :span="24">
                <a-list
                    :grid="{ gutter: 16, xs: 1, sm: 1, md: 2, lg: 2, xl: 3, xxl: 3 }"
                    :dataSource="bookingActivities"
                >
                    <template #renderItem="{ item }">
                        <a-list-item
                            :style="{
                                cursor: isActive(item) ? 'pointer' : 'not-allowed',
                            }"
                            :key="item.id"
                        >
                            <a-card>
                                <a-row
                                    :style="{
                                        opacity: isActive(item) ? '1' : '0.5',
                                    }"
                                >
                                    <a-col :span="6">
                                        <div
                                            :style="{
                                                display: 'flex',
                                                flexDirection: 'column',
                                                justifyContent: 'center',
                                                alignItems: 'center',
                                            }"
                                        >
                                            <h3>{{item.slot_code}}</h3>
                                            <a-avatar
                                                :size="64"
                                                :src="locker_icon"
                                            />
                                        </div>
                                    </a-col>
                                    <a-col :span="10">
                                        <a-row>
                                            <a-col :span="24">
                                                <h3>{{item.title}}</h3>
                                            </a-col>
                                        </a-row>
                                        <a-row>
                                            <a-col :span="24">
                                                <p>Mã ngăn: {{item.slot_code}}</p>
                                                <p>Trạng thái: {{handleStatusText(item.status)}}</p>
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                    <a-col :span="8"
                                        :style="{
                                            display: 'flex',
                                            flexDirection: 'column',
                                            justifyContent: 'center',
                                            alignItems: 'center',
                                        }"
                                    >
                                        <div>
                                            <a-button
                                                type="primary"
                                                shape="round"
                                                size="large"
                                                @click="handleClickShowButton(item)"
                                                :disabled="!isActive(item)"
                                            >
                                                MỞ KHÓA
                                            </a-button>
                                        </div>
                                        <div>
                                            <p v-if="isActive(item)">Còn lại</p>
                                            <p v-if="isActive(item)">
                                                <a-statistic-countdown
                                                    :value="handleTimeRemain(item)"
                                                    format="HH:mm"
                                                    :valueStyle= "{
                                                        fontSize: '1rem',
                                                    }"
                                                />
                                            </p>
                                        </div>
                                    </a-col>
                                </a-row>
                                <a-divider />
                                <a-collapse ghost>
                                    <a-collapse-panel header="Cài đặt" :key="item.id">
                                        <a-button
                                            type="primary"
                                            size="large"
                                            :style="{
                                                width: '100%',
                                                marginBottom: '0.5rem',
                                            }"
                                            @click="handleClickExtendButton(item)"
                                            :disable="!isActive(item)"
                                        >
                                            Thêm thời gian
                                        </a-button>
                                        <a-button
                                            type="primary"
                                            size="large"
                                            danger
                                            :style="{
                                                width: '100%',
                                            }"
                                            @click="handleClickEndButton(item)"
                                        >
                                            {{isActive(item) ? 'Kết thúc' : 'Hủy đặt'}}
                                        </a-button>
                                    </a-collapse-panel>
                                </a-collapse>
                            </a-card>
                        </a-list-item>
                    </template>
                </a-list>
            </a-col>
            <a-drawer
                title="Chi tiết đặt tủ"
                placement="right"
                :open="isShowDrawer"
                @close="onCloseDrawer"
                :width="isMobile ? '100%' : '40%'"
            >
                <actions-booking
                    :isMobile="this.isMobile"
                    :booking="this.chosenBooking"
                    @closeDrawer="onCloseDrawer"
                />
            </a-drawer>
        </a-row>
    </a-space>
    <extend-date-booking-modal
        :booking="this.chosenBooking"
        :visible="this.visible"
        @closeModal="onCloseModal"
    />
</template>
<script>
import {defineComponent,createVNode,inject} from "vue";
import {mapActions, mapState} from "vuex";
import {BOOKING_ACTIVITY_STATUS} from "../constants/bookingConstant";
import ActionsBooking from "./ActionsBooking.vue";
import ExtendDateBookingModal from "./ExtendDateBookingModal.vue";
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { Modal } from 'ant-design-vue';
import {GLOBAL_CONFIG} from "../SymbolKey.js";
import locker_icon from "../assets/images/locker_icon.png";

export default defineComponent({
    name: "OverviewApp",
    data() {
        return {
            isLoading: false,
            isShowDrawer: false,
            chosenBooking: {},
            visible: false,
        }
    },
    setup() {
        const globalConfig = inject(GLOBAL_CONFIG);
        return {
            isMobile: globalConfig.isMobile,
            locker_icon,
        }
    },
    components: {
        ActionsBooking,
        ExtendDateBookingModal,
    },
    computed: {
        ...mapState({
            bookingActivities: (state) => state.moduleBase.bookingActivities,
        }),
    },
    mounted(){
        this.$watch( ()=> this.$route.path,(to, from)=> {
            const id = this.$route.params.id;
            if (id) {
                this.isShowDrawer = true;
                this.chosenBooking = this.bookingActivities.find(booking => booking.id === parseInt(id));
            }
        })
    },
    methods: {
        ...mapActions({
            getBookingActivities: 'moduleBase/loadBookingActivities',
            deleteBooking: 'moduleBase/deleteBooking',
        }),
        isActive(booking) {
            return booking.status === BOOKING_ACTIVITY_STATUS.ACTIVE;
        },
        handleStatusText(status) {
            switch(status) {
                case BOOKING_ACTIVITY_STATUS.ACTIVE:
                    return "Active";
                case BOOKING_ACTIVITY_STATUS.NOT_YET:
                    return "Not yet";
                case BOOKING_ACTIVITY_STATUS.EXPIRED:
                    return "Expired";
                default:
                    return "Unknown";
            }
        },
        handleClickShowButton(booking) {
            this.isShowDrawer = true;
            this.chosenBooking = booking;
        },
        onCloseDrawer() {
            this.isShowDrawer = false;
        },
        onCloseModal() {
            this.visible = false;
        },
        handleClickExtendButton(booking) {
            this.visible = true;
            this.chosenBooking = booking;
        },
        handleTimeRemain(booking) {
            const EndDate = new Date(booking.end_date);
            return EndDate.getTime();
        },
        handleClickEndButton(booking) {
            Modal.confirm({
                title: () => 'Bạn có chắc muốn kết thúc đặt tủ này?',
                icon: () => createVNode(ExclamationCircleOutlined),
                content: () => 'Khi kết thúc đặt tủ, bạn sẽ không thể mở tủ này nữa',
                onOk: () => {
                    this.deleteBooking({
                        bookingId: booking.id,
                    });
                },
                okText: 'Đồng ý',
                cancelText: 'Hủy',
                onCancel() {},
            });
        },
        changePinCode(booking) {
            this.isShowDrawer = false;
        },
    },
    created() {
        this.isLoading = true;
        this.getBookingActivities().then(() => {
            this.isLoading = false;
        });
    },
});
</script>
<style lang="scss">
.ant-list .ant-list-item {
    padding: 0;
}
</style>
