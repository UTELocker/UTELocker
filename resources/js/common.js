UTELocker.common = (function () {
    const init = function (parent = "") {
        if (parent != "") {
            parent = parent + " ";
        }

        if (
            /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)
        ) {
            $(parent + ".select-picker").selectpicker("mobile");
        } else {
            $(parent + ".select-picker").selectpicker();
        }

        $(parent + "input").attr("autocomplete", "off");

        $("body").tooltip({
            selector: '[data-toggle="tooltip"]',
            trigger: 'hover'
        });

        $(function () {
            $('[data-toggle="popover"]').popover();
        });

        var drEvent = $(".dropify").dropify({
            messages: dropifyMessages,
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp','svg'],
        });

        drEvent.on("dropify.afterClear", function (event, element) {
            var elementID = element.element.id;
            var elementName = element.element.name;
            if ($("#" + elementID + "_delete").length == 0) {
                $("#" + elementID).after(
                    '<input type="hidden" name="' +
                    elementName +
                    '_delete" id="' +
                    elementID +
                    '_delete" value="yes">'
                );
            }
        });
    };

    const dataTableRowCheck = (id) => {
        if ($(".select-table-row:checked").length > 0) {
            $("#quick-action-form").fadeIn();
            //if at-least one row is selected
            document.getElementById("select-all-table").indeterminate = true;
            $("#quick-actions")
                .find("input, textarea, button, select")
                .removeAttr("disabled");
            if ($("#quick-action-type").val() == "") {
                $("#quick-action-apply").attr("disabled", true);
            }
            $(".select-picker").selectpicker("refresh");
        } else {
            $("#quick-action-form").fadeOut();
            //if no row is selected
            document.getElementById("select-all-table").indeterminate = false;
            $("#select-all-table").attr("checked", false);
            resetActionButtons();
        }

        if ($("#datatable-row-" + id).is(":checked")) {
            $("#row-" + id).addClass("table-active");
        } else {
            $("#row-" + id).removeClass("table-active");
        }
    };

    const selectAllTable = function (source) {
        let checkboxes = document.getElementsByName("datatable_ids[]");
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            // if disabled property is given to checkbox, it won't select particular checkbox.
            if (!$("#" + checkboxes[i].id).prop('disabled')){
                checkboxes[i].checked = source.checked;
            }
            if ($("#" + checkboxes[i].id).is(":checked")) {
                $("#" + checkboxes[i].id)
                    .closest("tr")
                    .addClass("table-active");
                $("#quick-actions")
                    .find("input, textarea, button, select")
                    .removeAttr("disabled");
                if ($("#quick-action-type").val() == "") {
                    $("#quick-action-apply").attr("disabled", true);
                }
                $(".select-picker").selectpicker("refresh");
            } else {
                $("#" + checkboxes[i].id)
                    .closest("tr")
                    .removeClass("table-active");
                resetActionButtons();
            }
        }

        if ($(".select-table-row:checked").length > 0) {
            $("#quick-action-form").fadeIn();
        } else {
            $("#quick-action-form").fadeOut();
        }
    };

    const resetActionButtons = () => {
        $("#quick-action-form")[0].reset();
        $("#quick-actions")
            .find("input, textarea, button, select")
            .attr("disabled", "disabled");
        $(".select-picker").selectpicker("refresh");
    };

    const quillConfig = {
        modules: {
            magicUrl: {
                urlRegularExpression: /(https?:\/\/[\S]+)|(www.[\S]+)|(tel:[\S]+)/g,
                globalRegularExpression: /(https?:\/\/|www\.|tel:)[\S]+/g,
            },
            toolbar: [
                [{
                    header: [1, 2, 3, 4, 5, false]
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['bold', 'italic', 'underline', 'strike'],
                ['image', 'code-block', 'link','video'],
                [{
                    'direction': 'rtl'
                }],
                ['clean']
            ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
    };

    const initQuill = (selector, config = quillConfig) => {
        const quillEditor = new Quill(selector, config);
    };

    return {
        init: init,
        selectAllTable: selectAllTable,
        dataTableRowCheck: dataTableRowCheck,
        resetActionButtons: resetActionButtons,
        initQuill: initQuill
    };
})();
