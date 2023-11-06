<template>
    <a-form-item
        label="Tủ chứa"
        name="src_id"
        :rules="rules.lockerId"
        v-if="step >= 1 && type >= step"
    >
        <a-select
            placeholder="Chọn tủ"
            @change="handleSelectLocker"
            :value="formSelectedLabel.locker"
        >
            <template v-for="option in optionsLocker" :key="option.id">
                <a-select-option
                    :value="option.id"
                >
                    {{ option.code }} - {{ option.address }}
                </a-select-option>
            </template>
        </a-select>
    </a-form-item>
    <a-form-item
        label="Ngăn tủ"
        name="src_id"
        :rules="rules.lockerSlotId"
        v-if="step >= 2 && type >= step"
    >
        <a-select
            placeholder="Chọn ngăn tủ"
            @change="handleSelectLockerSlot"
            :value="formSelectedLabel.lockerSlot"
        >
            <template v-for="option in this.optionsLockerSlot" :key="option.id">
                <a-select-option
                    :value="option.id"
                >
                    {{ option.name }}
                </a-select-option>
            </template>
        </a-select>
    </a-form-item>

    <a-form-item
        label="Đơn đặt chỗ"
        name="src_id"
        :rules="rules.bookingId"
        v-if="step >= 3 && type >= step"
    >
        <a-select
            placeholder="Chọn đơn đặt chỗ"
            @change="handleSelectBooking"
            :value="formSelectedLabel.booking"
        >
            <template v-for="option in this.optionsBooking" :key="option.id">
                <a-select-option
                    :value="option.id"
                >
                    Đặt chỗ {{ option.start_date }} - {{ option.end_date }} ({{ renderStatus(option.status) }})
                </a-select-option>
            </template>
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
            formSelectedLabel: {
                locker: null,
                lockerSlot: null,
                booking: null,
            }
        }),
        methods: {
            handleCancel() {
                this.$emit('handleCancel');
            },
            handleSelectLocker(value) {
                this.$emit('updateForm', {
                    key: 'lockerId',
                    value,
                });
                get(API_LOCKER.GET_LOCKER_SLOTS(value)).then((res) => {
                    this.optionsLockerSlot = res.data.data;
                    if (this.$route.query.lockerSlotId === undefined) {
                        this.$emit('updateForm', {
                            key: 'lockerSlotId',
                            value : null,
                        });
                    } else {
                        this.handleSelectLockerSlot(this.$route.query.lockerSlotId);
                        this.lockerSlotLabel = this.optionsLockerSlot.find((lockerSlot) => lockerSlot.id == this.$route.query.lockerSlotId);
                        this.formSelectedLabel.lockerSlot = this.lockerSlotLabel.name;
                    }
                });
                if (this.type >= HELP_CALL_TYPE.LOCKER_SLOT) {
                    this.step = 2;
                }
            },
            handleSelectLockerSlot(value) {
                this.$emit('updateForm', {
                    key: 'lockerSlotId',
                    value,
                });
                get(API_BOOKING.GET_BOOKING_OF_SLOT(value)).then((res) => {
                    this.optionsBooking = res.data.data;
                    if (this.$route.query.bookingId === undefined) {
                        this.$emit('updateForm', {
                            key: 'bookingId',
                            value : null,
                        });
                    } else {
                        this.handleSelectBooking(this.$route.query.bookingId);
                        this.bookingLabel = this.optionsBooking.find((booking) => booking.id == this.$route.query.bookingId);
                        this.formSelectedLabel.booking = `Đặt chỗ ${this.bookingLabel.start_date} - ${this.bookingLabel.end_date} (${this.renderStatus(this.bookingLabel.status)})`;
                    }
                });
                if (this.type >= HELP_CALL_TYPE.BOOKING) {
                    this.step = 3;
                }
            },
            handleSelectBooking(value) {
                this.$emit('updateForm', {
                    key: 'bookingId',
                    value,
                });
            },
            renderStatus(status) {
                return BOOKING_STATUS_TEXT[status];
            },
        },
        created() {
            get(API_LOCKER.GET_LOCKERS_ACTIVITIES()).then((res) => {
                this.optionsLocker.push(...res.data.data);
                if (this.$route.query.lockerId) {
                    this.handleSelectLocker(this.$route.query.lockerId);
                    this.lockerLabel = this.optionsLocker.find((locker) => locker.id == this.$route.query.lockerId);
                    this.formSelectedLabel.locker = `${this.lockerLabel.code} - ${this.lockerLabel.address}`;
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

            this.step = this.form.type;
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
        },
    })
</script>
