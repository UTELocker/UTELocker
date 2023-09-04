@if (!is_null($gender))
    @if ($gender === 0)
        <i class="fas fa-mars"></i> @lang('app.male')
    @elseif ($gender === 1)
        <i class="fas fa-venus"></i> @lang('app.female')
    @else
        <i class="fas fa-venus-mars"></i> @lang('app.others')
    @endif
@else
    --
@endif
