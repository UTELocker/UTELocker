@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

@section('filter-section')
    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('modules.users.addedOn')</p>
            <div class="select-status d-flex">
                <label for="datatableRange"></label>
                <input type="text"
                       class="position-relative
                       text-dark form-control
                       border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                       id="datatableRange" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>

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
        <div class="d-grid d-lg-flex d-md-flex action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <x-forms.link-primary
                    :link="route('admin.users.create')"
                    class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0" icon="plus"
                >
                    @lang('app.add')
                    @lang('app.user')
                </x-forms.link-primary>
            </div>
            <x-datatable.actions>
                <div class="select-status mr-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        <option value="delete">@lang('app.delete')</option>
                    </select>
                </div>
                <div class="select-status mr-3 d-none quick-action-field" id="change-status-action">
                    <select name="status" class="form-control select-picker">
                        <option value="deactive">@lang('app.inactive')</option>
                        <option value="active">@lang('app.active')</option>
                    </select>
                </div>
            </x-datatable.actions>
        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
    </div>
@endsection

@push('scripts')
    @include('sections.datatables_js')
@endpush
