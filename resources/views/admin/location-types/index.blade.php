@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="d-grid d-lg-flex d-md-flex action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <x-forms.button-primary
                    icon="plus" id="add-location-type" class="mr-3"
                >
                    @lang('app.add')
                    @lang('app.type')
                </x-forms.button-primary>
            </div>
            <x-datatable.actions></x-datatable.actions>
        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
    </div>
@endsection

@push('scripts')
    @include('sections.datatables_js')

    <script>
        const showTable = () => {
            window.LaravelDataTables["locationtypes-table"].draw(false);
        }

        function deletLocationType(locationTypeId ) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete this location type?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.easyAjax({
                    url: "{{ route('admin.location.types.destroy', ':id') }}".replace(':id', locationTypeId),
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

        $('#add-location-type').click(function () {
            const url = "{{ route('admin.location.types.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });
    </script>
@endpush

