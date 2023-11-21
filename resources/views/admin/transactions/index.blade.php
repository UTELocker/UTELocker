@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
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
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('modules.paymentMethod.title')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="payment-method-filter">
                            <option value="all">@lang('app.all')</option>
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{$paymentMethod->name}}">
                                    {{ $paymentMethod->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.status')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="status-filter">
                            <option value="all">@lang('app.all')</option>
                            @foreach(\App\Enums\TransactionStatus::asArray() as $status)
                                <option value="{{$status}}">
                                    {{ \App\Enums\TransactionStatus::getDescription($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('modules.paymentMethod.type')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="type-filter">
                            <option value="all">@lang('app.all')</option>
                            @foreach(\App\Enums\TransactionType::asArray() as $type)
                                <option value="{{$type}}">
                                    {{ \App\Enums\TransactionType::getDescription($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </x-filters.more-filter-box>
    </x-filters.filter-box>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-grid d-lg-flex d-md-flex action-bar">
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
        // $('#lockers-table').on('preXhr.dt', function(e, settings, data) {
        //     const dateRangePicker = $('#datatableRange').data('daterangepicker');
        //     let startDate = $('#datatableRange').val();
        //     let endDate;
        //
        //     if (startDate === '') {
        //         startDate = null;
        //         endDate = null;
        //     } else {
        //         startDate = dateRangePicker.startDate.format('YYYY-MM-DD');
        //         endDate = dateRangePicker.endDate.format('YYYY-MM-DD');
        //     }
        //
        //     data['startDate'] = startDate;
        //     data['endDate'] = endDate;
        // });

        // const showTable = () => {
        //     window.LaravelDataTables["lockers-table"].draw(false);
        // }

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
            const table = $('#lockers-table');

            $('#search-text-field').on('keyup', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.search = value;
                }).DataTable().ajax.reload();
            });

            $('#payment-method-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.payment_method_name = value === 'all' ? null : value;
                }).DataTable().ajax.reload();
            });

            $('#status-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.status = value === 'all' ? null : value;
                }).DataTable().ajax.reload();
            });

            $('#type-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.type = value === 'all' ? null : value;
                }).DataTable().ajax.reload();
            });

            $('#reset-filters-2').on('click', function () {
                $('#location-filter').val('');
                $('#status-filter').val('');
                $('#type-filter').val('');
                table.on('preXhr.dt', function (e, settings, data) {
                    data.payment_method_name = '';
                    data.status = '';
                    data.type = '';
                }).DataTable().ajax.reload();
            });
        })
    </script>
@endpush
