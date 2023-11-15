@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="d-grid d-lg-flex d-md-flex action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                @if (user()->isAdmin())
                    <x-forms.link-primary
                        :link="route('admin.payment.methods.create')"
                        class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0" icon="plus"
                    >
                        @lang('app.add')
                        @lang('app.paymentMethod')
                    </x-forms.link-primary>
                @endif
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
        const showTable = () => {
            window.LaravelDataTables["paymentmethods-table"].draw(false);
        }

        function deletePaymentMethod(paymentMethodId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to inactive this payment method?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.easyAjax({
                        url: "{{ route('admin.payment.methods.destroy', ':id') }}".replace(':id', paymentMethodId),
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
                        },
                    });
                }
            });
        }


        $(document).ready(function () {
            showTable();
        })
    </script>
@endpush
