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

        $(document).ready(function () {
            showTable();
        })
    </script>
@endpush
