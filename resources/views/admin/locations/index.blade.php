@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

@section('filter-section')
    <x-filters.filter-box>
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

        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
    </x-filters.filter-box>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-grid d-lg-flex d-md-flex action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <x-forms.link-primary
                    :link="route('admin.location.locations.create')"
                    class="mr-3 float-left mb-2 mb-lg-0 mb-md-0" icon="plus"
                >
                    @lang('app.add')
                    @lang('app.location')
                </x-forms.link-primary>
            </div>
            <x-datatable.actions>
            </x-datatable.actions>
        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
    </div>
@endsection

@push('scripts')
    @include('sections.datatables_js')

    <script>

        function deleteLocation(locationId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete this location?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.easyAjax({
                    url: "{{ route('admin.location.locations.destroy', ':id') }}".replace(':id', locationId),
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE",
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            showTable();
                            Swal.fire(
                                "@lang('messages.recordDeleted')!",
                                "@lang('messages.recordDeleted')",
                                "success"
                            );
                        }
                        else {
                            Swal.fire(
                                "@lang('app.error')!",
                                response.message,
                                "error"
                            );
                        }
                    },
                });
                }
            });
        }

        $('#lockers-table').on('preXhr.dt', function(e, settings, data) {
            const dateRangePicker = $('#datatableRange').data('daterangepicker');
            let startDate = $('#datatableRange').val();
            let endDate;

            if (startDate === '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('YYYY-MM-DD');
                endDate = dateRangePicker.endDate.format('YYYY-MM-DD');
            }

            data['startDate'] = startDate;
            data['endDate'] = endDate;
        });

        const showTable = () => {
            window.LaravelDataTables["locations-table"].draw(false);
        }

        $(document).ready(function () {
            @if (!is_null(request('start')) && !is_null(request('end')))
            $('#datatableRange').val('{{ request('start') }}' +
                ' @lang("app.to") ' + '{{ request('end') }}');
            $('#datatableRange').data('daterangepicker').setStartDate("{{ request('start') }}");
            $('#datatableRange').data('daterangepicker').setEndDate("{{ request('end') }}");
            showTable();
            @endif
        })

        $(document).ready(function () {
            const table = $('#locations-table');
            $('#search-text-field').on('keyup', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.search = value;
                }).DataTable().ajax.reload();
            });
        })
    </script>
@endpush

