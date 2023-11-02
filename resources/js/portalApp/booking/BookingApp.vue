<template>
    <article>
        <section class="markdown">
            <h1>Đặt Locker</h1>
            <p>Dùng bộ lọc bên dưới để tìm kiếm locker phù hợp với nhu cầu của bạn.</p>
        </section>
        <a-card :style="{backgroundColor: 'var(--purple-3)'}">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="8" :lg="10" :xl="10" style="margin: 5px 0 !important">
                    <a-row>
                        <a-col :span="24">
                            <a-select
                                v-model:value="this.formModel.selectedLocation"
                                :disabled="isLoading"
                                :options="locations"
                                mode="multiple"
                                @change="this.setFormModel('selectedLocation', $event)"
                                placeholder="Chọn địa điểm"
                                style="width: 100%"
                                :size="size"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8" style="margin: 5px 0 !important">
                    <a-row>
                        <a-col :span="24">
                            <a-range-picker
                                showTime
                                format="YYYY-MM-DD HH:mm"
                                @change="this.onRangePickerChange"
                                :size="size"
                                style="width: 100%"
                                :disabledDate="date => date < Date.now() - 24 * 60 * 60 * 1000"
                                :placeholder="['Bắt đầu', 'Kết thúc']"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :xs="24" :sm="24" :md="4" :lg="4" :xl="4" style="margin: 5px 0 !important">
                    <a-row>
                        <a-col :span="24">
                            <a-input-number
                                min="1"
                                max="10"
                                :size="size"
                                style="width: 100%"
                                placeholder="Số ngăn trống tối thiểu"
                                @change="this.setFormModel('numberOfSlots', $event)"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :xs="24" :sm="24" :md="2" :lg="2" :xl="2" style="margin: 5px 0 !important">
                    <a-row>
                        <a-col :span="24">
                            <a-button
                                type="primary"
                                :size="size"
                                style="width: 100%"
                                @click="this.submit()"
                                :loading="isSubmitLoading"
                            >
                                Search
                            </a-button>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </a-card>
        <template v-if="availableLockers.length > 0">
            <section class="markdown">
                <h2>Kết quả tìm kiếm</h2>
            </section>
            <div class="site-card-wrapper">
                <a-list
                    :grid="{ gutter: 16, xs: 1, sm: 1, md: 2, lg: 2, xl: 3, xxl: 3 }"
                    :dataSource="availableLockers"
                    :loading="isLoading"
                >
                    <template #renderItem="{ item }">
                        <a-list-item>
                            <a-card
                                :title="item.description"
                                :style="{marginBottom: '20px'}"
                                @click="this.$router.push({
                                    name: 'booking.locker',
                                    params: {id: item.id},
                                    query: {
                                        startDate: this.formModel.startDate,
                                        endDate: this.formModel.endDate,
                                    }
                                })"
                                hoverable>
                                <h3>Địa chỉ: {{item.address}}</h3>
                                <h3>Số ngăn tủ trống: {{item.locker_slots_count}}</h3>
                            </a-card>
                        </a-list-item>
                    </template>
                </a-list>
            </div>
        </template>
    </article>
</template>
<script>
import Layout from "../components/layouts/Layout.vue";
import {defineComponent} from "vue";
import {mapActions, mapState} from "vuex";
import { Modal } from 'ant-design-vue';
import dayjs, { Dayjs } from 'dayjs';

export default defineComponent({
    name: "BookingApp",
    components: {
        Layout,
    },
    computed: {
        ...mapState({
            locations: state => state.moduleBase.locations,
            availableLockers: state => state.moduleBooking.availableLockers,
        }),
    },
    data() {
        return {
            formModel: {
                selectedLocation: [],
                startDate: null,
                endDate: null,
            },
            isLoading: false,
            isSubmitLoading: false,
            presets: [
                { label: '1 giờ', value: [dayjs(), dayjs().add(1, 'h')] },
                { label: '1 ngày', value: [dayjs(), dayjs().add(1, 'd')] },
                { label: '2 ngày', value: [dayjs(), dayjs().add(2, 'd')] },
            ]
        };
    },
    setup() {
        return {
            size: 'large',
        };
    },
    methods: {
        ...mapActions({
            loadLocations: "moduleBase/loadLocations",
            loadLockers: "moduleBooking/loadAvailableLockers",
            resetBooking: "moduleBooking/resetBooking",
        }),
        setFormModel(key, value) {
            this.formModel[key] = value;
        },
        onRangePickerChange(date, dateString) {
            this.setFormModel('startDate', dateString[0]);
            this.setFormModel('endDate', dateString[1]);
        },
        validateForm() {
            if (this.formModel.startDate === null) {
                this.showErrorMessage('Vui lòng chọn thời gian bắt đầu');
                return false;
            }
            if (this.formModel.endDate === null) {
                this.showErrorMessage('Vui lòng chọn thời gian kết thúc');
                return false;
            }
            if (this.formModel.selectedLocation.endDate <= this.formModel.startDate) {
                this.showErrorMessage('Thời gian kết thúc phải sau thời gian bắt đầu');
                return false;
            }
            return true;
        },
        showErrorMessage(message, onOk = null) {
            Modal.error({
                title: 'Lỗi',
                content: message,
                onOk: onOk,
            });

        },
        submit() {
            if (this.validateForm()) {
                this.isSubmitLoading = true;
                this.loadLockers({
                    locationIds: this.formModel.selectedLocation,
                    startDate: this.formModel.startDate,
                    endDate: this.formModel.endDate,
                    numberOfSlots: this.formModel.numberOfSlots,
                }).then(() => {
                    this.isSubmitLoading = false;
                }).catch((e) => {
                    const message = e?.response?.data?.message || e || 'Lỗi tìm kiếm locker'
                    this.isSubmitLoading = false;
                    Modal.error({
                        title: 'Lỗi tìm kiếm locker',
                        content: message,
                    });
                });
            }
        },
    },
    created() {
        this.isLoading = true;
        this.resetBooking();
        this.loadLocations().then(() => {
            this.isLoading = false;
            if (this.$route.query.location) {
                const location = this.locations.find((location) => {
                    return location.code === this.$route.query.location;
                });
                this.formModel['selectedLocation'] = [
                    parseInt(location.value),
                ];
            }
        }).catch((e) => {
            const message = e.response.data.message;
            this.isLoading = false;
            Modal.error({
                title: 'Lỗi tải địa điểm',
                content: message,
            });
        });
    },
});
</script>
