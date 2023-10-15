<template>
    <a-page-header
        :title="locker.description"
        sub-title="Select slots for your booking"
        style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
        @back="() => $router.push({name: 'booking'})"
    >
        <template #extra>
            <a-button type="primary" @click="() => $router.push({name: 'booking'})">
                Cancel
            </a-button>
        </template>
        <a-row>
            <a-card style="width: 100%">
                <template v-for="row in lockerSlots">
                    <template v-for="slot in row">
                        <a-card-grid :style="{
                            width: calculateSlotWidth(row) + '%',
                            textAlign: 'center',
                            backgroundColor: getBackgroundColor(slot),
                            cursor: isSystemSlot(slot) ? 'not-allowed' : 'pointer',
                        }"
                            :hoverable="!isSystemSlot(slot)"
                        >
                            <a-popover
                                title="Slot Details"
                            >
                                <template #content>
                                    <p>Slot Type: {{slot.type}}</p>
                                    <p>Slot Status: {{slot.status}}</p>
                                </template>
                                <p>{{slot.id}}</p>
                            </a-popover>
                        </a-card-grid>
                    </template>
                </template>
            </a-card>
        </a-row>
    </a-page-header>
</template>
<script>
import {defineComponent} from "vue";
import {mapActions, mapState} from "vuex";
import {SLOT_STATUS, SLOT_TYPE} from "../constants/bookingConstant";
export default defineComponent({
    name: "SelectLockerApp",
    components: {
        SLOT_STATUS
    },
    computed: {
        ...mapState({
            lockerSlots: (state) => state.moduleBooking.lockerSlots,
            locker: (state) => state.moduleBooking.locker,
        }),
    },
    setup() {
        return {
            SLOT_STATUS,
            SLOT_TYPE,
        };
    },
    methods: {
        ...mapActions({
            loadLockerSlots: "moduleBooking/loadLockerSlots",
        }),
        calculateSlotWidth(row) {
            return 100 / row.length;
        },
        getBackgroundColor(slot) {
            if (this.isSystemSlot(slot)) {
                return 'var(--border-color-base)';
            }

            switch (slot.status) {
                case SLOT_STATUS.AVAILABLE:
                    return 'var(--green-6)';
                case SLOT_STATUS.BOOKED:
                    return 'var(--error-color)';
            }
        },
        isSystemSlot(slot) {
            return slot.type === SLOT_TYPE.CPU || slot.type === SLOT_TYPE.EMPTY;
        },
    },
    data() {
        return {
            lockerId: null,
            startDate: null,
            endDate: null,
            isLoading: false,
        };
    },
    created() {
        this.lockerId = this.$route.params.id;
        this.startDate = this.$route.query.startDate;
        this.endDate = this.$route.query.endDate;

        this.isLoading = true;
        this.loadLockerSlots({
            lockerId: this.lockerId,
            startDate: this.startDate,
            endDate: this.endDate,
        }).then(() => {
            this.isLoading = false;
        });
    },
});
</script>
