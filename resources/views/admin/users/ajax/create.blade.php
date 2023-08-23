<div class="row">
    <div class="col-sm-12">
        <x-form id="save-user-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    User Details
                </h4>

                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                        </div>
                    </div>
                </div>
        </x-form>
    </div>
</div>

<script>
    $(document).ready(function() {
        UTELocker.common.init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        let checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }
</script>
