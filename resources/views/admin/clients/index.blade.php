@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-grid d-lg-flex d-md-flex action-bar">

            <div id="table-actions" class="flex-grow-1 align-items-center">
{{--                @if ($addClientPermission == 'all' || $addClientPermission == 'added' || $addClientPermission == 'both')--}}
{{--                    <x-forms.link-primary :link="route('clients.create')" class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0" icon="plus">--}}
{{--                        @lang('app.add')--}}
{{--                        @lang('app.client')--}}
{{--                    </x-forms.link-primary>--}}
{{--                @endif--}}

{{--                @if ($addClientPermission == 'all' || $addClientPermission == 'added' || $addClientPermission == 'both')--}}
{{--                    <x-forms.link-secondary :link="route('clients.import')" class="mr-3 float-left mb-2 mb-lg-0 mb-md-0 d-sm-bloc d-none d-lg-block" icon="file-upload">--}}
{{--                        @lang('app.importExcel')--}}
{{--                    </x-forms.link-secondary>--}}
{{--                @endif--}}
            </div>

{{--            <x-datatable.actions>--}}
{{--                <div class="select-status mr-3">--}}
{{--                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>--}}
{{--                        <option value="">@lang('app.selectAction')</option>--}}
{{--                        <option value="change-status">@lang('modules.tasks.changeStatus')</option>--}}
{{--                        <option value="delete">@lang('app.delete')</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                <div class="select-status mr-3 d-none quick-action-field" id="change-status-action">--}}
{{--                    <select name="status" class="form-control select-picker">--}}
{{--                        <option value="deactive">@lang('app.inactive')</option>--}}
{{--                        <option value="active">@lang('app.active')</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </x-datatable.actions>--}}


{{--            <div class="btn-group mt-2 mt-lg-0 mt-md-0 ml-0 ml-lg-3 ml-md-3" role="group">--}}
{{--                <a href="{{ route('clients.index') }}" class="btn btn-secondary f-14 btn-active show-clients" data-toggle="tooltip"--}}
{{--                   data-original-title="@lang('app.menu.clients')"><i class="side-icon bi bi-list-ul"></i></a>--}}

{{--                <a href="javascript:;" class="btn btn-secondary f-14 show-unverified" data-toggle="tooltip"--}}
{{--                   data-original-title="@lang('modules.dashboard.verificationPending')"><i class="side-icon bi bi-person-x"></i></a>--}}
{{--            </div>--}}

        </div>
        <!-- Add Task Export Buttons End -->

        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    @include('sections.datatables_js')
@endpush
