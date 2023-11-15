<div class="task_view">
    <div class="dropdown">
        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle
              {{ $row->status == \App\Enums\UserStatus::BAN ? 'disable-row' : '' }}
            "
           type="link"
           id="dropdownMenuLink-{{ $row->id }}"
           data-toggle="dropdown"
           aria-haspopup="true"
           aria-expanded="false"
        >
            <i class="icon-options-vertical icons"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-{{ $row->id }}" tabindex="0">
            <a href="{{ route('admin.users.show', $row->id) }}"
               class="dropdown-item"><i class="fa fa-eye mr-2"></i>View</a>
            <a href="{{ route('admin.users.edit', $row->id) }}"
               class="dropdown-item openRightModal"><i class="fa fa-edit mr-2"></i>Edit</a>
            @if ($row->status != \App\Enums\UserStatus::BAN)
                <a class="dropdown-item delete-table-row"
                    href="javascript: deleteUser({{ $row->id }});" data-form-id="user-delete-{{ $row->id }}"
                    data-method="delete"
                ><i class="fa fa-trash mr-2"></i>Delete</a>
            @endif
        </div>
    </div>
</div>
