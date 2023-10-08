@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatables_css')
@endpush

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
        })
    </script>
@endpush
