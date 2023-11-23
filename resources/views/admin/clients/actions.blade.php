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
            <a href="{{ route('admin.clients.show', $row->id) }}"
               class="dropdown-item"><i class="fa fa-eye mr-2"></i>View</a>
            <a href="{{ route('admin.clients.edit', $row->id) }}"
               class="dropdown-item openRightModal"><i class="fa fa-edit mr-2"></i>Edit</a>
            <a class="dropdown-item delete-table-row"
               href="javascript: deleteClient({{ $row->id }})" data-form-id="user-delete-{{ $row->id }}"><i
                    class="fa fa-trash mr-2"></i>Delete</a>
        </div>
    </div>
</div>
