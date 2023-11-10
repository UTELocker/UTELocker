@switch($row->status)
    @case(\App\Enums\TransactionStatus::PENDING)
        <i class="fa fa-circle mr-1 f-10" style="color: #f3fb00;"></i> {{ __('app.enums.transactionStatus.pending')}}
        @break
    @case(\App\Enums\TransactionStatus::SUCCESS)
        <i class="fa fa-circle mr-1 f-10" style="color: #5dff00;"></i> {{ __('app.enums.transactionStatus.success')}}
        @break
    @default
        <i class="fa fa-circle mr-1 f-10" style="color: #ff0000;"></i> {{ __('app.enums.transactionStatus.failed')}}
        @break
@endswitch
