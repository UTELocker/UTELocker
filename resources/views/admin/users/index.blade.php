@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

@section('filter-section')
    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
            <div class="select-status d-flex">
                <input type="text" class="position-relative text-dark form-control my-2 text-left f-14  p-1 border-additional-grey"
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
                        <select class="form-control select-picker" data-container="body" name="status" id="status-filter">
                            <option value="">@lang('app.all')</option>
                            <option value="Y">@lang('app.active')</option>
                            <option value="N">@lang('app.inactive')</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.gender')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="gender-filter">
                            <option value="">@lang('app.all')</option>
                            <option value="0">@lang('app.male')</option>
                            <option value="1">@lang('app.female')</option>
                            <option value="2">@lang('app.others')</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.type')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="type-filter">
                            <option value="">@lang('app.all')</option>
                            @if (\App\Models\User::isSuperUser())
                                <option value="0">@lang('app.superAdmin')</option>
                            @endif
                            <option value="1">@lang('app.admin')</option>
                            <option value="2">@lang('app.normalUser')</option>
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
                <x-forms.button-secondary
                    class="mr-3 float-left mb-2 mb-lg-0 mb-md-0" icon="plus"
                    id="show-modal-token-register"
                >
                    @lang('app.createTokenRegister')
                </x-forms.button-secondary>
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
                        <option value="N">@lang('app.inactive')</option>
                        <option value="Y">@lang('app.active')</option>
                    </select>
                </div>
            </x-datatable.actions>
        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>

        <div class="modal fade" id="modal-token" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">List token access register</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ol class="list-group list-group-numbered" id="list-token">
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="create-token">Create token</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sections.datatables_js')
    <script>
        let listToken = [];

        function renderListToken() {
            let html = '';
            listToken.forEach(function (item) {
                html += `<li class="list-group-item" data-id="${item.id}">
                        <div class="row">
                            <div class="col-12">
                                <div style="font-weight: bold; font-size: 16px; color: #000; margin-bottom: 5px">
                                    ${item.token}
                                </div>
                                <div class="mt-2 row" style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                ">
                                    <span class="badge badge-primary badge-pill" style="margin-left: 1rem">${item.expired_at}</span>
                                    <div style="display: flex">
                                        <button class="mr-2 delete-token" style="cursor: pointer" onclick="deleteTokenRes('${item.token}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ff0000 !important;}</style><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                        </button>
                                        <div class="mr-2" style="cursor: pointer" onclick="copyTokenRes('${item.token}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#005eff !important;}</style><path d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/></svg>
                                        </div>
                                        <div style="cursor: pointer" onclick="openNewTabQr('${item.token}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ff5900 !important;}</style><path d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80zM64 96v64h64V96H64zM0 336c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V336zm64 16v64h64V352H64zM304 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H304c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48zm80 64H320v64h64V96zM256 304c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s7.2-16 16-16s16 7.2 16 16v96c0 8.8-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s-7.2-16-16-16s-16 7.2-16 16v64c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V304zM368 480a16 16 0 1 1 0-32 16 16 0 1 1 0 32zm64 0a16 16 0 1 1 0-32 16 16 0 1 1 0 32z"/></svg>                                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>`;
            });
            $('#modal-token .modal-body #list-token').html(html);
        }


        function deleteTokenRes(id) {
            $.easyAjax({
                url: "{{ route('portal.user.token-register.delete', '') }}" + '/' + id,
                type: "GET",
                success: function(response) {
                    if (response.status == "success") {
                        listToken = listToken.filter(item => item.id !== id);
                        renderListToken();
                    }
                }
            })
        }

        function copyTokenRes(token) {
            const domain = window.location.origin;
            const linkRegister = domain + '/register?token=' + token;
            navigator.clipboard.writeText(linkRegister).then(function() {
                Swal.fire({
                    icon: "success",
                    text: "Copy success!",

                    toast: true,
                    position: "top-end",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,

                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                    showClass: {
                        popup: "swal2-noanimation",
                        backdrop: "swal2-noanimation",
                    },
                });
            }, function() {
                $.showToastr("Copy failed!", 'error');
            });
        }

        function openNewTabQr (token) {
            window.open('/qr-code?token=' + token, '_blank');
        }

        $(document).ready(function () {
            $('#datatableRange').on('click', function() {
                var dateRangePicker = $('#datatableRange').data('daterangepicker');
                console.log(dateRangePicker);
            });
            $('#datatableRange').on('apply.daterangepicker', (event, picker) => {
                cb(picker.startDate, picker.endDate);
                $('#datatableRange').val(picker.startDate.format('{{ globalSettings()->moment_format }}') +
                    ' @lang("app.to") ' + picker.endDate.format(
                        '{{ globalSettings()->moment_format }}'));
            });

            $('#datatableRange2').on('apply.daterangepicker', (event, picker) => {
                cb(picker.startDate, picker.endDate);
                $('#datatableRange2').val(picker.startDate.format('{{ globalSettings()->moment_format }}') +
                    ' @lang("app.to") ' + picker.endDate.format(
                        '{{ globalSettings()->moment_format }}'));
            });

            function cb(start, end) {
                $('#datatableRange, #datatableRange2').val(start.format('{{ globalSettings()->moment_format }}') +
                    ' @lang("app.to") ' + end.format(
                        '{{ globalSettings()->moment_format }}'));
                $('#reset-filters, #reset-filters-2').removeClass('d-none');

            }
            const table = $('#users-table');
            $('#search-text-field').on('keyup', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.search = value;
                }).DataTable().ajax.reload();
            });
            $('#status-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.status = value;
                }).DataTable().ajax.reload();
            });
            $('#type-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.type = value;
                }).DataTable().ajax.reload();
            });
            $('#gender-filter').on('change', function () {
                const value = $(this).val();
                table.on('preXhr.dt', function (e, settings, data) {
                    data.gender = value;
                }).DataTable().ajax.reload();
            });
            $('#reset-filters-2').on('click', function () {
                $('#status-filter').val('');
                $('#type-filter').val('');
                $('#gender-filter').val('');
                table.on('preXhr.dt', function (e, settings, data) {
                    data.gender = '';
                    data.type = '';
                    data.status = '';
                }).DataTable().ajax.reload();
            });

            $('#show-modal-token-register').on('click', function () {
                $('#modal-token').modal('show');
                $.ajax({
                    url: '{{ route('portal.user.token-register') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (result) {
                        if (result.status == 'success') {
                            listToken = result.data;
                            renderListToken();
                        }
                    }
                });
            });

            $('#create-token').on('click', function () {
                $.ajax({
                    url: '{{ route('portal.user.token-register.create') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (result) {
                        if (result.status == 'success') {
                            listToken.push(result.token);
                            renderListToken();
                        }
                        Swal.fire({
                            icon: "success",
                            text: "Token created successfully",

                            toast: true,
                            position: "top-end",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,

                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                            showClass: {
                                popup: "swal2-noanimation",
                                backdrop: "swal2-noanimation",
                            },
                        });
                    }
                });
            })
        });
    </script>
@endpush
