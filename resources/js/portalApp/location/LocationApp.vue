<template>
    <div
        style="height: 80vh; width: 70vw;"
    >
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
            locationCurrent: [0, 0]
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
                    location: location.value,
                },
            });
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
