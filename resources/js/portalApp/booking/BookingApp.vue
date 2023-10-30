<template>
    <article>
        <section class="markdown">
            <h1>Find a locker</h1>
            <section class="markdown">
                <p>Select a locker to book</p>
            </section>
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
                                placeholder="Select a location"
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
                                :presets="presets"
                                format="YYYY-MM-DD HH:mm"
                                @change="this.onRangePickerChange"
                                :size="size"
                                style="width: 100%"
                                :disabledDate="date => date < Date.now() - 24 * 60 * 60 * 1000"
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
                                placeholder="Number of lockers"
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
        <section class="markdown" v-if="availableLockers.length > 0">
            <h2>Available lockers</h2>
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
                                <p>Address: {{item.address}}</p>
                                <p>Available slots: {{item.locker_slots_count}}</p>
                            </a-card>
                        </a-list-item>
                    </template>
                </a-list>
            </div>
        </section>
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
                { label: '1 hours', value: [dayjs(), dayjs().add(1, 'h')] },
                { label: '1 Day', value: [dayjs(), dayjs().add(1, 'd')] },
                { label: '2 Days', value: [dayjs(), dayjs().add(2, 'd')] },
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
                this.showErrorMessage('Please select a start date');
                return false;
            }
            if (this.formModel.endDate === null) {
                this.showErrorMessage('Please select an end date');
                return false;
            }
            if (this.formModel.selectedLocation.endDate <= this.formModel.startDate) {
                this.showErrorMessage('End date must be greater than start date');
                return false;
            }
            return true;
        },
        showErrorMessage(message, onOk = null) {
            Modal.error({
                title: 'Error',
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
                    const message = e?.response?.data?.message || e || 'Error searching lockers';
                    this.isSubmitLoading = false;
                    Modal.error({
                        title: 'Error searching lockers',
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
                title: 'Error loading locations',
                content: message,
            });
        });
    },
});
</script>
