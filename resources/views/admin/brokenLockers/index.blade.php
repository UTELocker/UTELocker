@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
    <style>
        .disabled-row {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush

@section('filter-section')
    <x-filters.filter-box>
        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <label for="search-text-field"></label>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                           placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

        <!-- MORE FILTERS START -->
        <x-filters.more-filter-box>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.status')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="status">
                            <option value="all">@lang('app.all')</option>
                            <option value="active">@lang('app.active')</option>
                            <option value="deactive">@lang('app.inactive')</option>
                        </select>
                    </div>
                </div>
            </div>
        </x-filters.more-filter-box>
    </x-filters.filter-box>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
    </div>

    <!-- MODAL START -->
    <div class="modal" id="modal-accept-broken" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <x-form id="save-locker-data-form" method="PUT">
                    <div class="modal-body">
                        <x-forms.select fieldId="status" :fieldLabel="__('modules.lockers.status')"
                                        fieldName="status"
                                        fieldRequired="true"
                        >
                            @foreach(
                                \App\Enums\LockerStatus::getDescriptions([
                                    \App\Enums\LockerStatus::IN_USE,
                                    \App\Enums\LockerStatus::AVAILABLE,
                                    \App\Enums\LockerStatus::PENDING_BROKEN
                                ])
                                as $key => $status
                            )
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </x-forms.select>
                        <x-forms.text
                            fieldId="cancel_reason"
                            :fieldLabel="__('modules.bookings.cancelReason')"
                            fieldName="cancel_reason"
                            fieldRequired="false"
                            :fieldPlaceholder="__('placeholders.cancelReason')"
                            :fieldValue="''">
                        </x-forms.text>
                    </div>
                    <div class="modal-footer">
                        <x-forms.button-primary
                            id="save-locker-form"
                            class="mr-3"
                            icon="check">@lang('app.save')
                        </x-forms.button-primary>
                        <x-forms.button-cancel
                            :link="route('admin.lockers.index')"
                            class="border-0">@lang('app.cancel')
                        </x-forms.button-cancel>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sections.datatables_js')

    <script>

        const showTable = () => {
            window.LaravelDataTables["broken-lockers-table"].draw(false);
        }

        function acceptBroken(lockerId) {
            Swal.fire({
                title: "Are you sure?",
                text: "If you accept this broken locker, All bookings will be canceled",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Accept",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modal-accept-broken').modal('show');
                    $('#save-locker-data-form').attr('data-locker-id', lockerId);
                }
            });
        }

        function rejectBroken(lockerId) {
            $.ajax({
                url: "{{ route('admin.broken-lockers.update' , ':id') }}".replace(':id', lockerId),
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    __token: "{{ csrf_token() }}",
                    status: "{{ \App\Enums\LockerStatus::IN_USE }}",
                },
                success: function (data) {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancel",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('admin.broken-lockers.index') }}";
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: data.message,
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancel",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('admin.broken-lockers.index') }}";
                            }
                        });
                    }
                },
                error: function (data) {
                        Swal.fire({
                            title: "Error!",
                            text: data.message,
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancel",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('admin.broken-lockers.index') }}";
                            }
                        });
                    }
            });
        }

        $(function () {
            $('#save-locker-form').on('click', function () {
                const form = $('#save-locker-data-form');
                const formData = form.serializeArray();
                const lockerId = form.attr('data-locker-id');

                $.ajax({
                    url: "{{ route('admin.broken-lockers.update' , ':id') }}".replace(':id', lockerId),
                    type: 'PUT',
                    data: formData,
                    success: function (data) {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: "Success!",
                                text: data.message,
                                icon: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "OK",
                                cancelButtonText: "Cancel",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('admin.broken-lockers.index') }}";
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message,
                                icon: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "OK",
                                cancelButtonText: "Cancel",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('admin.broken-lockers.index') }}";
                                }
                            });
                        }
                    },
                    error: function (data) {
                        Swal.fire({
                            title: "Error!",
                            text: data.message,
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancel",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('admin.broken-lockers.index') }}";
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush
