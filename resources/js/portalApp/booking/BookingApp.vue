<template>
    <article>
        <section class="markdown">
            <h1>Find a locker</h1>
            <section class="markdown">
                <p>Select a locker to book {{isLoading}}</p>
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
                                format="DD/MM/YYYY HH:mm"
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
        <section class="markdown" v-if="lockers.length > 0">
            <h2>Available lockers</h2>
            <div class="site-card-wrapper">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="6" v-for="locker in lockers" :key="locker.value">
                        <a-card :title="locker.label" :style="{marginBottom: '20px'}" hoverable>
                            <p>Card content</p>
                            <p>Card content</p>
                            <p>Card content</p>
                        </a-card>
                    </a-col>
                </a-row>
            </div>
        </section>
    </article>
</template>
<script>
import Layout from "../components/layouts/Layout.vue";
import {ref, defineComponent} from "vue";
import {mapActions, mapState} from "vuex";

export default defineComponent({
    name: "BookingApp",
    components: {
        Layout,
    },
    computed: {
        ...mapState({
            locations: state => state.moduleBase.locations,
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
        const lockers = ref([
            {value: '1', label: 'Locker 1'},
            {value: '2', label: 'Locker 2'},
            {value: '3', label: 'Locker 3'},
            {value: '4', label: 'Locker 4'},
            {value: '5', label: 'Locker 5'},
            {value: '6', label: 'Locker 6'}
        ]);

        return {
            size: 'large',
            lockers,
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
