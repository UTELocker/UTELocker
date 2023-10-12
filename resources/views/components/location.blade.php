<div class="media align-items-center mw-250">
    @if (!is_null($location))
        <div class="media-body">
            <h5 class="mb-0 f-12">
                <a
                    href="{{ route('admin.location.locations.show', [$location->id]) }}"
                    class="text-darkest-grey">
                    {{ ucfirst($location->code) }}
                </a>
            </h5>
            <p class="mb-0 f-12 text-dark-grey">
                {{ $location->description ?? ' ' }}
            </p>
        </div>
    @else
        --
    @endif
</div>
