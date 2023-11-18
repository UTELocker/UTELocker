<div class="d-flex justify-content-end align-items-center">
    <a class="d-flex justify-content-center align-items-center mr-2 px-2 py-1"
        style="background: red; color: white; border-radius: 5px;"
        href="javascript: acceptBroken({{$row->id}});">Accept Broken</a>
    <a class="d-flex justify-content-center align-items-center px-2 py-1"
    style="background: rgb(0, 255, 21); color: rgb(255, 255, 255); border-radius: 5px;"
        href="javascript:rejectBroken({{$row->id}});">Reject Broken</a>
</div>
