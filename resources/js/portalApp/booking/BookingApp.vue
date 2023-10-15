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
                <a-col :span="10">
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
                <a-col :span="8">
                    <a-row>
                        <a-col :span="24">
                            <a-range-picker
                                showTime
                                format="YYYY-MM-DD HH:mm"
                                @change="this.onRangePickerChange"
                                :size="size"
                                style="width: 100%"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :span="4">
                    <a-row>
                        <a-col :span="24">
                            <a-input-number
                                min="1"
                                max="10"
                                :size="size"
                                style="width: 100%"
                                placeholder="Number of lockers"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :span="2">
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
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="6" v-for="locker in availableLockers" :key="locker.id">
                        <a-card
                            :title="locker.description"
                            :style="{marginBottom: '20px'}"
                            @click="this.$router.push({
                                name: 'booking.locker',
                                params: {id: locker.id},
                                query: {
                                    startDate: this.formModel.startDate,
                                    endDate: this.formModel.endDate,
                                }
                            })"
                            hoverable>
                            <p>Address: {{locker.address}}</p>
                            <p>Available slots: {{locker.locker_slots_count}}</p>
                        </a-card>
                    </a-col>
                </a-row>
            </div>
        </section>
    </article>
</template>
<script>
import Layout from "../components/layouts/Layout.vue";
import {defineComponent} from "vue";
import {mapActions, mapState} from "vuex";

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
        }),
        setFormModel(key, value) {
            this.formModel[key] = value;
        },
        onRangePickerChange(value, dateString) {
            this.setFormModel('startDate', dateString[0]);
            this.setFormModel('endDate', dateString[1]);
        },
        validateForm() {
            return true;
        },
        submit() {
            if (this.validateForm()) {
                this.isSubmitLoading = true;
                this.loadLockers({
                    locationIds: this.formModel.selectedLocation,
                    startDate: this.formModel.startDate,
                    endDate: this.formModel.endDate,
                }).then(() => {
                    this.isSubmitLoading = false;
                });
            }
        },
    },
    created() {
        this.isLoading = true;
        this.loadLocations().then(() => {
            this.isLoading = false;
        });
    }
});
</script>
