(function ($) {
    $.fn.bulkCreate = function (options) {
        const rowTypes = {
            1 : '12',
            2 : '6-6',
            3 : '4-4-4',
            4 : '3-3-3-3',
        }
        const lockerId = options.lockerId;
        const defaultRow = [{},{},{}]
        const modules = options.modules || [defaultRow];

        this.addRow = function (type, after) {
            after = after || 0;
            const row = rowTypes[type];
            const columns = row.split('-');
            const moduleRow = [];
            columns.forEach((column) => {
                moduleRow.push([]);
            });
            modules.splice(after, 0, moduleRow);
        }
        // this.addModule = function (module, row, after) {
        //     row = row || 0;
        //     after = after || 0;
        //     modules[row].splice(after, 0, module);
        // }

        const addRowDiv = function (type, after) {
            const rowDiv = $(`
                <section class="grid-layout">
                    <div class="row row-layout">
                        <div class="row-item">
                            <div class="col-sm-12">
                                <div class="block-wrapper">
                                    <div class="block-container">
                                        <div class="block-content dash-border">
                                            <section class="empty-block">
                                                <button class="btn btn-primary btn-block addRowBtn"
                                                    data-type="${type}"
                                                    data-after="${after}">Add Row</button>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            `);

            rowDiv.find('.addRowBtn').on('click', function () {
                const type = $(this).data('type');
                const after = $(this).data('after');
                $('#modal-select-row-layout').modal('show');
            });

            return rowDiv;
        }

        this.renderModules = function () {
            const moduleList = $('#moduleList');
            moduleList.empty();

            moduleList.append(addRowDiv(1, 0));
            modules.forEach((row, rowIndex) => {
                const rowCount = row.length;
                const colClass = 'col-md-' + (12 / rowCount);
                const rowDiv = $('<div class="row"></div>');
                row.forEach((slot) => {
                    if (Object.keys(slot).length === 0 && slot.constructor === Object) {
                        const addBtn = $('<button class="btn btn-primary btn-block addModuleBtn">Add Module</button>');
                        const colDiv = $('<div class="' + colClass + '"></div>');
                        colDiv.append(addBtn);
                        rowDiv.append(colDiv);
                    }
                });
                moduleList.append(rowDiv);
                moduleList.append(addRowDiv(1, rowIndex + 1));
            });

        }

        return this;
    }
})(jQuery)
