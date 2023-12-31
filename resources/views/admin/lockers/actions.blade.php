<div class="task_view">
    <div class="dropdown">
        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle"
           type="link"
           id="dropdownMenuLink-{{ $row->id }}"
           data-toggle="dropdown"
           aria-haspopup="true"
           aria-expanded="false"
        >
            <i class="icon-options-vertical icons"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-{{ $row->id }}" tabindex="0">
            @if ($row->status != \App\Enums\LockerStatus::AVAILABLE)
                <a href="{{ route('admin.lockers.show', $row->id) }}"
                    class="dropdown-item"><i class="fa fa-eye mr-2"></i>View</a>
                <a href="{{ route('admin.lockers.edit', $row->id) }}"
                    class="dropdown-item openRightModal"><i class="fa fa-edit mr-2"></i>Edit</a>
            @else
                <a href="{{ route('admin.lockers.edit', $row->id) }}"
                    class="dropdown-item openRightModal"><i class="fa fa-edit mr-2"></i>
                    Setup Wizard
                </a>
            @endif
        </div>
    </div>
</div>
