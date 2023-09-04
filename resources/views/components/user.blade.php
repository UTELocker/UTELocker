<div class="media align-items-center mw-250">
    @if (!is_null($user))
        <a href="{{ route('admin.users.show', [$user->id]) }}" class="position-relative">
            <img src="{{ $avatar }}" class="mr-2 taskEmployeeImg rounded-circle"
                 alt="{{ ucfirst($user->name) }}" title="{{ ucfirst($user->name) }}">
        </a>
        <div class="media-body">
            <h5 class="mb-0 f-12">
                <a
                    href="{{ route('admin.users.show', [$user->id]) }}"
                    class="text-darkest-grey">
                    {{ ucfirst($user->name) }}
                </a>
            </h5>
            <p class="mb-0 f-12 text-dark-grey">
                {{ !is_null($user->email) ? ucwords($user->email) : ' ' }}
            </p>
        </div>
    @else
        --
    @endif
</div>
