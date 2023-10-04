@switch($isActive)
    @case(1)
        <i class="fa fa-circle mr-1 text-light-green f-10"></i>{{ __('app.active') }}
        @break
    @case(0)
        <i class="fa fa-circle mr-1 text-red f-10"></i>{{ __('app.inactive') }}
        @break
    @default
@endswitch
