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
                <a-col :span="12">
                    <a-row>
                        <a-col :span="24">
                            <a-select
                                v-model:value="selectedLocation"
                                :options="locations"
                                mode="multiple"
                                @change="handleSelectLocation"
                                placeholder="Select a location"
                                style="width: 100%"
                                :size="size"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :span="6">
                    <a-row>
                        <a-col :span="24">
                            <a-range-picker
                                showTime
                                format="DD/MM/YYYY HH:mm"
                                @change="onRangePickerChange"
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
                            <a-button type="primary" :size="size" style="width: 100%">
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

export default defineComponent({
    name: "BookingApp",
    components: {
        Layout,
    },
    setup() {
        const locations = ref([
            {value: '1', label: 'Location 1'},
            {value: '2', label: 'Location 2'},
            {value: '3', label: 'Location 3'},
            {value: '4', label: 'Location 4'},
            {value: '5', label: 'Location 5'},
            {value: '6', label: 'Location 6'}
        ]);
        const selectedLocation = ref([]);

        const onRangePickerChange = (value, dateString) => {
            console.log('Selected Time: ', value);
            console.log('Formatted Selected Time: ', dateString);
        };

        const handleSelectLocation = (value) => {
            selectedLocation.value = value;
        };

        const lockers = ref([
            {value: '1', label: 'Locker 1'},
            {value: '2', label: 'Locker 2'},
            {value: '3', label: 'Locker 3'},
            {value: '4', label: 'Locker 4'},
            {value: '5', label: 'Locker 5'},
            {value: '6', label: 'Locker 6'}
        ]);

        return {
            locations,
            selectedLocation,
            handleSelectLocation,
            onRangePickerChange,
            size: 'large',
            lockers,
        };
    },
});
</script>
