<template>
    <a-space
        :size="5"
        direction="vertical"
        :style="{width: '100%'}"
    >
        <a-page-header
            title="Địa điểm"
            sub-title="danh sách địa điểm đang hoạt động"
            style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
            :back-icon="false"
        >
        </a-page-header>
        <a-card :style="{backgroundColor: 'var(--purple-3)'}">
            <a-row :gutter="16">
                <a-col :span="22">
                    <a-row>
                        <a-col :span="24">
                            <a-select
                                v-model:value="value"
                                show-search
                                placeholder="Chọn địa điểm"
                                :options="locations"
                                :filter-option="filterOption"
                                style="width: 100%"
                            />
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :span="2">
                    <a-row>
                        <a-col :span="24">
                            <a-button
                                type="primary"
                                style="width: 100%"
                                @click="this.selectedLocation()"
                            >
                                Đi đến
                            </a-button>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </a-card>
        <div style="height: 80vh; width: 100%;">
            <l-map
                ref="map"
                v-model:zoom="zoom"
                :center="[
                    this.locationCurrent[0],
                    this.locationCurrent[1],
                ]"
            >
                <l-tile-layer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    layer-type="base"
                    name="OpenStreetMap"
                ></l-tile-layer>
                <template
                    v-for="location in locations"
                    :key="location.id"
                >
                    <l-marker
                        :lat-lng="[
                            location.latitude,
                            location.longitude,
                        ]"
                    >
                        <l-popup
                            style="width: 300px;"
                        >
                            <a-space
                                direction="vertical"
                                :size='5'
                            >
                                <a-row>
                                    <p>Address:</p>
                                </a-row>
                                <a-row>
                                    <p>{{location.label}}</p>
                                </a-row>
                                <a-row>
                                    <a-button
                                        type="primary"
                                        @click="negativeBooking(location)"
                                    >
                                        Booking Locker Here
                                    </a-button>
                                </a-row>
                                <a-row>
                                    <p>List Locker:</p>
                                </a-row>
                            </a-space>
                            <a-list item-layout="horizontal" :data-source="location.lockers">
                                <template #renderItem="{ item }">
                                    <a-list-item>
                                        <a-list-item-meta
                                            :description="showStatus(item)"
                                        >
                                            <template #title>
                                                <a href=""
                                                >
                                                    {{ item.code }}
                                                </a>
                                            </template>
                                            <template #avatar>
                                                <a-avatar src="https://joeschmoe.io/api/v1/random" />
                                            </template>
                                        </a-list-item-meta>
                                    </a-list-item>
                                </template>
                            </a-list>
                        </l-popup>
                    </l-marker>
                </template>
            </l-map>
        </div>
    </a-space>
</template>
<script>
import { defineComponent } from 'vue';
import "leaflet/dist/leaflet.css"
import {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
} from "@vue-leaflet/vue-leaflet";
import {mapActions, mapState} from "vuex";
import {LOCKER_STATUS} from "../constants/lockerConstant";
export default defineComponent({
    name: 'LocationApp',
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
    },
    data() {
        return {
            zoom: 20,
            isLoading: true,
            locationCurrent: [0, 0],
            value: null,
        };
    },
    computed: {
        ...mapState({
            locations: (state) => state.moduleBase.locations,
        }),
    },
    methods: {
        ...mapActions({
            loadLocations: "moduleBase/loadLocations",
        }),
        showStatus(item) {
            switch (item.status) {
                case LOCKER_STATUS.AVAILABLE:
                    return "Available";
                case LOCKER_STATUS.IN_USE:
                    return "In use";
                case LOCKER_STATUS.BROKEN:
                    return "Broken";
                case LOCKER_STATUS.UNDER_MAINTENANCE:
                    return "Under maintenance";
                default:
                    return "Unknown";
            }
        },
        negativeBooking(location) {
            this.$router.push({
                name: "booking",
                query: {
                    location: location.code,
                },
            });
        },
        filterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        selectedLocation() {
            const location = this.locations.find(
                (location) => location.value === this.value
            );
            this.locationCurrent = [
                location.latitude,
                location.longitude,
            ];
        },
    },
    created() {
        this.loadLocations().then(() => {
            this.isLoading = false;
        });
        navigator.geolocation.getCurrentPosition((position) => {
            this.locationCurrent = [
                position.coords.latitude,
                position.coords.longitude,
            ];
        });

    }

});
</script>
