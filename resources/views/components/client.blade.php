<div class="media align-items-center mw-250">
    @if (!is_null($client))
        <a href="{{ route('admin.clients.show', [$client->id]) }}" class="position-relative">
            <img src="{{ $logo }}" class="mr-2 taskEmployeeImg rounded-circle"
                 alt="{{ ucfirst($client->name) }}" title="{{ ucfirst($client->name) }}">
        </a>
        <div class="media-body">
            <h5 class="mb-0 f-12">
                <a
                    href="{{ route('admin.clients.show', [$client->id]) }}"
                    class="text-darkest-grey">
                    {{ ucfirst($client->name) }}
                </a>
            </h5>
            <p class="mb-0 f-12 text-dark-grey">
                {{ !is_null($client->app_name) ? ucwords($client->app_name) : ' ' }}
            </p>
        </div>
    @else
        --
    @endif
</div>
