<template>
    <a-space
        :size="20"
        direction="vertical"
        style="width: 100%;"
    >
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
            <a-row v-if="isLoading">
                <a-col :span="24">
                    <a-skeleton active />
                </a-col>
            </a-row>
            <a-row v-else>
                <a-card style="width: 100%">
                    <template v-for="row in lockerSlots">
                        <template v-for="slot in row">
                            <a-card-grid :style="{
                                width: calculateSlotWidth(row) + '%',
                                textAlign: 'center',
                                backgroundColor: getBackgroundColor(slot),
                                cursor: isBooked(slot) ? 'not-allowed' : 'pointer',
                            }"
                                :hoverable="!isBooked(slot)"
                                 @click="selectSlot(slot)"
                            >
                                <a-popover
                                    title="Slot Details"
                                >
                                    <template #content>
                                        <p>Slot Type: {{slot.type}}</p>
                                        <p>Slot Status: {{slot.statusSlot}}</p>
                                    </template>
                                    <p>A{{slot.number_of_slot}}</p>
                                </a-popover>
                            </a-card-grid>
                        </template>
                    </template>
                </a-card>
            </a-row>
        </a-page-header>
        <a-page-header
            :title="locker.description"
            sub-title="Information about your booking"
            style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
        >
            <template #extra>
                <a-button
                    type="primary"
                    @click="() => submit()"
                    :disabled="totalPrice === 0"
                >
                    Book Slots
                </a-button>
            </template>

            <template v-for="item in getInformationSelectedSlots()">
                <a-row
                    style="justify-content: space-between; align-items: center;"
                    :style="item.options?.stylesRow"
                >
                    <p
                        style="font-size: 1rem; font-weight: 400;"
                        :style="item.options?.stylesLabel"
                    >
                        {{item.label}}:
                    </p>
                    <p
                        style="font-weight: 500; font-size: 1.25rem;"
                        :style="item.options?.stylesValue"
                    >
                        {{item.value}}
                    </p>
                </a-row>
            </template>

        </a-page-header>
    </a-space>
</template>
<script>
import {defineComponent} from "vue";
import {mapActions, mapState} from "vuex";
import {LIMIT_BOOKING_SLOTS, SLOT_STATUS, SLOT_TYPE} from "../constants/bookingConstant";
import { Modal } from 'ant-design-vue';

export default defineComponent({
    name: "SelectLockerApp",
    components: {
        SLOT_STATUS
    },
    computed: {
        ...mapState({
            lockerSlots: (state) => state.moduleBooking.lockerSlots,
            locker: (state) => state.moduleBooking.locker,
            selectedSlots: (state) => state.moduleBooking.selectedSlots,
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
            setStatusSelectedSlot: "moduleBooking/setStatusSelectedSlot",
        }),
        calculateSlotWidth(row) {
            return 100 / row.length;
        },
        getBackgroundColor(slot) {
            if (this.isSystemSlot(slot)) {
                return 'var(--border-color-base)';
            }

            if (slot.is_selected) {
                return 'var(--green-3)';
            }

            switch (slot.statusSlot) {
                case SLOT_STATUS.AVAILABLE:
                    return 'var(--green-6)';
                case SLOT_STATUS.BOOKED:
                    return 'var(--error-color)';
            }
        },
        isSystemSlot(slot) {
            return slot.type === SLOT_TYPE.CPU || slot.type === SLOT_TYPE.EMPTY;
        },
        isBooked(slot) {
            if (this.isSystemSlot(slot)) {
                return true;
            }
            return slot.statusSlot;
        },
        selectSlot(slot) {
            if (this.isBooked(slot)) {
                return;
            }

            if (this.selectedSlots.length === LIMIT_BOOKING_SLOTS
                && !slot.is_selected
            ) {
                return;
            }

            const timeDiff = Math.abs(new Date(this.endDate).getTime() - new Date(this.startDate).getTime()) / 36e5;
            if (!slot.is_selected) {
                this.totalPrice += (slot.config?.price_per_hour ?? 10) * timeDiff;
            } else {
                this.totalPrice -= (slot.config?.price_per_hour ?? 10) * timeDiff;
            }

            this.setStatusSelectedSlot({
                slotId: slot.id,
            });
        },
        validate() {
            if (this.startDate === null || this.endDate === null) {
                Modal.error({
                    title: 'Error',
                    content: 'Please select start date and end date',
                });
                return false;
            }
            if (this.totalPrice === 0) {
                Modal.error({
                    title: 'Error',
                    content: 'Please select at least one slot',
                });
                return false;
            }
            return true;
        },
        submit() {
            if (!this.validate()) {
                return;
            }
            this.$router.push({
                name: 'booking.confirm',
                query: {
                    startDate: this.startDate,
                    endDate: this.endDate,
                }
            })
        },
        getInformationSelectedSlots() {
            return [
                {
                    label: 'Number of selected slots',
                    value: this.selectedSlots?.length,
                    options: {
                        stylesValue: {
                            color: 'var(--primary-color)',
                            fontWeight: 'bold',
                        },
                    },
                },
                {
                    label: 'Code of selected slots',
                    value: this.selectedSlots?.map((slot) => slot.number_of_slot).join(', '),
                    options: {
                        stylesValue: {
                            fontWeight: 'bold',
                        },
                    },
                },
                {
                    label: 'Total price',
                    value: parseFloat(this.totalPrice).toFixed(2),
                    options: {
                        stylesValue: {
                            color: 'var(--green-6)',
                            fontWeight: 'bold',
                            fontSize: '1.5rem',
                        },
                        stylesRow: {
                            marginTop: '1rem',
                            borderTop: '2px solid var(--border-color-base)',
                        },
                        stylesLabel: {
                            fontWeight: 'bold',
                        },
                    },
                },
            ]
        }
    },
    data() {
        return {
            lockerId: null,
            startDate: null,
            endDate: null,
            isLoading: false,
            totalPrice: 0,
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
