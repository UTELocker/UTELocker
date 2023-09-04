@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bulkCreate.css') }}">
@endpush

<div class="card bg-white border-0 b-shadow-4">
    <div class="card-header bg-white border-0 text-capitalize d-flex justify-content-between pt-4">
        <h4 class="box-title">{{ __('modules.lockers.tabs.bulkCreate') }}</h4>
    </div>
    <div class="card-body pt-2">
        @include('admin.lockers.slots._select_row_layout')
        <div id="bulkCreate">
            <div id="moduleList"></div>
        </div>
    </div>
</div>
