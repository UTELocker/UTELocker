<template>
    <div
        :style="{
            width: '100%',
            height: isMobile ? '30vh' : '30vh',
        }"
    >
        <h3 style="margin-bottom: 10px;">Bản đồ</h3>
        <l-map ref="map" v-model:zoom="zoom" :center="[
                    this.marker.lat,
                    this.marker.lng,
                ]">
            <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                layer-type="base"
                name="OpenStreetMap"
            ></l-tile-layer>
            <l-marker
                :lat-lng="[
                    this.marker.lat,
                    this.marker.lng,
                ]"
                @click="onClick"
            >
                <l-popup>
                    <span>
                        {{this.address}}
                    </span>
                </l-popup>
            </l-marker>
        </l-map>
    </div>
</template>
<script>
import {defineComponent} from "vue";
import "leaflet/dist/leaflet.css"
import {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
} from "@vue-leaflet/vue-leaflet";

export default defineComponent({
    name: "MapLocker",
    props: {
        marker: {
            type: Object,
            required: true,
        },
        address: {
            type: String,
            default: "",
        },
        isMobile: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
    },
    data() {
        return {
            zoom: 20,
        };
    },
    methods: {
        onClick() {
            // popup detail

        },
    },
});
</script>
