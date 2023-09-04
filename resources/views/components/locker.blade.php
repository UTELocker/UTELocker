<div class="media align-items-center mw-250">
    @if (!is_null($locker))
        <a href="{{ route('admin.lockers.show', [$locker->id]) }}" class="position-relative">
            <img src="{{ $image }}" class="mr-2 taskEmployeeImg rounded-circle"
                 alt="{{ ucfirst($locker->code) }}" title="{{ ucfirst($locker->code) }}">
        </a>
        <div class="media-body">
            <h5 class="mb-0 f-12">
                <a
                    href="{{ route('admin.lockers.show', [$locker->id]) }}"
                    class="text-darkest-grey">
                    {{ ucfirst($locker->code) }}
                </a>
            </h5>
            <p class="mb-0 f-12 text-dark-grey">
                {{ !is_null($locker->description) ? ucwords($locker->description) : ' ' }}
            </p>
        </div>
    @else
        --
    @endif
</div>
