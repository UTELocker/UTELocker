<template>
    <a-form-item
        label="Tủ chứa"
        name="lockerId"
        :rules="rules.lockerId"
        v-if="step >= 1 && type >= step"
    >
        <a-select
            placeholder="Chọn tủ"
            :value="lockerId"
            @change="$emit('updateForm', { key: 'lockerId', value: $event })"
            :options="optionsLocker"
        >
        </a-select>
    </a-form-item>
    <a-form-item
        label="Ngăn tủ"
        name="lockerSlotId"
        :rules="rules.lockerSlotId"
        v-if="step >= 2 && type >= step"
    >
        <a-select
            placeholder="Chọn ngăn tủ"
            :value="lockerSlotId"
            @change="$emit('updateForm', { key: 'lockerSlotId', value: $event })"
            :options="optionsLockerSlot"
        >
        </a-select>
    </a-form-item>

    <a-form-item
        label="Đơn đặt chỗ"
        name="bookingId"
        :rules="rules.bookingId"
        v-if="step >= 3 && type >= step"
    >
        <a-select
            placeholder="Chọn đơn đặt chỗ"
            :value="bookingId"
            @change="$emit('updateForm', { key: 'bookingId', value: $event })"
            :options="optionsBooking"
        >
        </a-select>
    </a-form-item>
</template>
<script>
    import { defineComponent, ref } from 'vue';
    import { HELP_CALL_TYPE } from '../constants/helpCallConstant';
    import { get } from '../helpers/api';
    import { API as API_LOCKER } from '../constants/lockerConstant';
    import { API as API_BOOKING, BOOKING_STATUS_TEXT } from '../constants/bookingConstant';

    export default defineComponent({
        name: 'SelectedDetail',
        props: {
            type: {
                type: String,
                required: true,
            },
            form: {
                type: Object,
                required: true,
            },
        },
        setup() {
            const rules = ref({
                lockerId: [
                    {
                        required: true,
                        message: 'Vui lòng chọn tủ',
                    },
                ],
                lockerSlotId: [
                    {
                        required: true,
                        message: 'Vui lòng chọn ngăn tủ',
                    },
                ],
                bookingId: [
                    {
                        required: true,
                        message: 'Vui lòng chọn đơn đặt chỗ',
                    },
                ],
            });

            return {
                rules,
            };
        },
        data: () => ({
            optionsLocker: [],
            optionsLockerSlot: [],
            optionsBooking: [],
            step: 1,
            hasQuery: false,
        }),
        methods: {
            handleCancel() {
                this.$emit('handleCancel');
            },
            handleSelectLocker(value) {
                get(API_LOCKER.GET_LOCKER_SLOTS(value)).then((res) => {
                    this.optionsLockerSlot = res.data.data.map((item) => ({
                        value: item.id,
                        label: item.name
                    }));
                    if (this.$route.query.lockerSlotId === undefined) {
                        this.$emit('updateForm', {
                            key: 'lockerSlotId',
                            value : null,
                        });
                    } else {
                        this.$emit('updateForm', {
                            key: 'lockerSlotId',
                            value : this.$route.query.lockerSlotId,
                        });
                    }
                });
                if (this.type >= HELP_CALL_TYPE.LOCKER_SLOT) {
                    this.step = 2;
                }
            },
            handleSelectLockerSlot(value) {
                get(API_BOOKING.GET_BOOKING_OF_SLOT(value)).then((res) => {
                    this.optionsBooking = res.data.data.map((item) => ({
                        value: item.id,
                        label: `Đặt chỗ ${ item.start_date } - ${ item.end_date } (${ this.renderStatus(item.status) })`
                    }));
                    if (this.$route.query.bookingId === undefined) {
                        this.$emit('updateForm', {
                            key: 'bookingId',
                            value : null,
                        });
                    } else {
                        this.$emit('updateForm', {
                            key: 'bookingId',
                            value : this.$route.query.bookingId,
                        });
                    }
                });
                if (this.type >= HELP_CALL_TYPE.BOOKING) {
                    this.step = 3;
                }
            },
            renderStatus(status) {
                return BOOKING_STATUS_TEXT[status];
            },
        },
        created() {
            get(API_LOCKER.GET_LOCKERS_ACTIVITIES()).then((res) => {
                this.optionsLocker = res.data.data.map((item) => ({
                        value: item.id,
                        label: `${item.code} - ${item.address}`
                    }));
                if (this.$route.query.lockerId) {
                    this.$emit('updateForm', {
                        key: 'lockerId',
                        value : this.$route.query.lockerId,
                    });
                }
            });

            if (this.$route.query.lockerId) {
                this.$emit('updateForm', {
                    key: 'helpCallstdProblemId',
                    value: -1,
                });
                this.hasQuery = true;
            }


            if (this.$route.query.bookingId) {
                this.$emit('updateForm', {
                    key: 'type',
                    value: HELP_CALL_TYPE.BOOKING,
                });
            } else if (this.$route.query.lockerSlotId) {
                this.$emit('updateForm', {
                    key: 'type',
                    value: HELP_CALL_TYPE.LOCKER_SLOT,
                });
            } else if (this.$route.query.lockerId) {
                this.$emit('updateForm', {
                    key: 'type',
                    value: HELP_CALL_TYPE.LOCKER,
                });
            }

        },
        watch: {
            type() {
                if (!this.hasQuery) {
                    this.$emit('updateForm', {
                        key: 'bookingId',
                        value: null,
                    });
                    this.$emit('updateForm', {
                        key: 'lockerSlotId',
                        value: null,
                    });
                    this.$emit('updateForm', {
                        key: 'lockerId',
                        value: null,
                    });
                    this.step = 1;
                }
            },
            'form.lockerId': function (value) {
                this.handleSelectLocker(value);
            },
            'form.lockerSlotId': function (value) {
                this.handleSelectLockerSlot(value);
            },
        },
        computed: {
            lockerId() {
                if (this.form.lockerId === null) {
                    return null;
                }
                return this.optionsLocker.find((item) => item.value == this.form.lockerId)?.label;
            },
            lockerSlotId() {
                if (this.form.lockerSlotId === null) {
                    return null;
                }
                return this.optionsLockerSlot.find((item) => item.value == this.form.lockerSlotId)?.label;
            },
            bookingId() {
                if (this.form.bookingId === null) {
                    return null;
                }
                return this.optionsBooking.find((item) => item.value == this.form.bookingId)?.label;
            },
        }
    })
</script>
