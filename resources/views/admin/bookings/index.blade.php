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
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.status')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="status-filter">
                            <option value="all">@lang('app.all')</option>
                            @foreach(
                                \App\Enums\BookingStatus::getDescriptions()
                                as $key => $status
                            )
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.location')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="location" id="location-filter">
                            <option value="all">@lang('app.all')</option>
                            @foreach($locations as $location)
                                <option value="{{$location->id}}">
                                    {{ $location->description }}
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
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
    </div>
@endsection

@push('scripts')
    @include('sections.datatables_js')

    <script>
        const showTable = () => {
            window.LaravelDataTables["bookings-table"].draw(false);
        }

        $(document).ready(function () {
            showTable();

            const table = $('#bookings-table');

            $('#search-text-field').on('keyup', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.search = value === '' ? null : value;
                }).DataTable().ajax.reload();
            });

            $('#location-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.location = value === 'all' ? null : value;
                }).DataTable().ajax.reload();
            });

            $('#status-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.status = value === 'all' ? null : value;
                }).DataTable().ajax.reload();
            });

            $('#reset-filters-2').on('click', function () {
                $('#location-filter').val('all').selectpicker('refresh');
                $('#status-filter').val('all').selectpicker('refresh');
                table.on('preXhr.dt', function (e, settings, data) {
                    data.status = '';
                    data.location = '';
                }).DataTable().ajax.reload();
            });
        })
    </script>
@endpush
