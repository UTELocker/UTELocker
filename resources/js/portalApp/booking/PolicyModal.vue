<template>
    <a-modal
        v-model:open="visible"
        :confirmLoading="isSubmitLoading"
        :width="800"
        :closable="false"
    >
        <template #title>
            <p style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0;">
                Điều khoản đặt chỗ
            </p>
        </template>
        <template #footer>
            <a-row>
                <a-checkbox
                    v-model:checked="isConfirmPolicy"
                    style="margin-bottom: 1rem;"
                >
                    Tôi đã đọc và đồng ý với điều khoản
                </a-checkbox>
            </a-row>
            <a-row>
                <a-checkbox
                    v-model:checked="isDontShowAgain"
                    style="margin-bottom: 1rem;"
                >
                    Không hiển thị lại
                </a-checkbox>
            </a-row>
            <a-row>
                <a-button
                    @click="handleCancel"
                >
                    Quay về trang tìm kiếm
                </a-button>
                <a-button
                    type="primary"
                    @click="submit"
                    :loading="isSubmitLoading"
                    :disabled="!isConfirmPolicy"
                >
                    Đồng ý
                </a-button>
            </a-row>
        </template>
        <template #default>
            <a-row>
                <a-col :span="24">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0;">
                        1. Bạn có thể hủy đặt chỗ miễn phí trước 1 giờ so với thời gian đặt chỗ.
                    </p>
                </a-col>
                <a-col :span="24">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0;">
                        2. Bạn cần xác nhận kết thúc để kết thúc đặt chỗ.
                    </p>
                </a-col>
                <a-col :span="24">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0;">
                        3. Nếu bạn kết thúc trễ, bạn sẽ bị tính phí 50% giá trị đặt chỗ.
                    </p>
                </a-col>
            </a-row>
        </template>
    </a-modal>
</template>
<script>
import {defineComponent} from "vue";
import postBooking from "../mixins/apiBooking.js";
import {mapState} from "vuex";
import { Modal } from 'ant-design-vue';

export default defineComponent({
    name: "PolicyModal",
    mixins: [postBooking],
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
        totalPrice: 0,
    },
    data() {
        return {
            isLoading: false,
            isSubmitLoading: false,
            endDate: null,
            startDate: null,
            isConfirmPolicy: false,
            isDontShowAgain: false,
        };
    },
    computed: {
        ...mapState({
            selectedSlots: (state) => state.moduleBooking.selectedSlots,
        }),
    },
    methods: {
        handleCancel() {
            return this.$router.push({name: 'booking'});
        },
        submit() {
            this.postBooking().then((res) => {
                if (res?.status === 'success') {
                    Modal.success({
                        title: 'Thành công',
                        content: 'Đặt chỗ thành công',
                        onOk: () => {
                            this.$router.push({name: 'booking'});
                        },
                    });
                } else {
                    const message = res?.message || 'Có lỗi xảy ra';
                    Modal.error({
                        title: 'Lỗi đặt chỗ',
                        content: message,
                        onOk: () => {
                            this.$router.push({name: 'booking'});
                        },
                    });
                }
            }).catch((e) => {
                const message = res?.message || 'Có lỗi xảy ra';
                Modal.error({
                    title: 'Lỗi đặt chỗ',
                    content: message,
                    onOk: () => {
                        this.$router.push({name: 'booking'});
                    },
                });
            });
        },
    },
    created() {
        this.startDate = this.$route.query.startDate;
        this.endDate = this.$route.query.endDate;
    },
});
</script>
