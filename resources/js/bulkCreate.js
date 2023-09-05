(function ($) {
    $.fn.bulkCreate = function (options) {
        const bulkCreate = this;
        const rowTypes = {
            1 : '12',
            2 : '6-6',
            3 : '4-4-4',
            4 : '3-3-3-3',
            5 : '2-2-2-2-2-2',
        }
        const lockerId = options.lockerId;
        let clipboard = {};
        const slotConfigDefault = {
            'id': -1,
            'type': 'SLOT',
            'status': 0,
        }
        const emptyConfigDefault = {
            'id': -1,
            'type': 'EMPTY',
            'status': 0,
        }
        const rawModules = options.modules;
        const modules = [];

        Object.keys(rawModules).forEach((rowId) => {
            const row = rawModules[rowId];
            const rowModules = [];
            Object.keys(row).forEach((slotId) => {
                const slot = row[slotId];
                rowModules.push(slot);
            });
            modules.push(rowModules);
        });

        const addRow = function (type, after) {
            after = after || 0;
            const row = rowTypes[type];
            const columns = row.split('-');
            const moduleRow = [];
            columns.forEach((column) => {
                moduleRow.push({});
            });
            modules.splice(after, 0, moduleRow);

            renderModules();
        }

        const addRowDiv = function (type, after) {
            const style = after === 0 ? 'mt-0' : 'mb-0';
            const text = after === 0 ? 'Add Row Below' : 'Add Row Above';
            const rowDiv = $(`
                <section class="grid-layout">
                    <div class="row row-layout ${style}">
                        <div class="row-item w-100">
                            <div class="col-sm-12">
                                <div class="block-wrapper">
                                    <div class="block-container">
                                        <div class="block-content dash-border">
                                            <section class="empty-block">
                                                <button class="btn btn-primary addRowBtn"
                                                    data-after="${after}">${text}</button>
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
                const after = $(this).data('after');

                $('#modal-select-row-layout').modal('show');
                $('.sample-row-item').off('click').on('click', function () {
                    const rowType = $(this).data('type');
                    $('#modal-select-row-layout').modal('hide');
                    addRow(rowType, after);
                });
            });

            return rowDiv;
        }

        const addSlot = function (module, rowId, slotId) {
            const targetRow = modules[rowId];
            targetRow.splice(slotId, 1, {
                ...module,
                'row': rowId,
                'column': slotId,
            });

            renderModules();
        }

        const removeSlot = function (rowId, slotId) {
            const targetRow = modules[rowId];
            targetRow.splice(slotId, 1, {});

            renderModules();
        }

        const addEmptySlot = function (
            rowId,
            slotId,
        ) {
            const emptySlotDiv = $(`
                <div class="block-wrapper">
                    <div class="block-container">
                        <div class="block-content dash-border">
                            <section class="empty-block">
                                <button class="btn btn-outline-primary addModuleBtn mr-1">Add Slot</button>
                                <button class="btn btn-outline-secondary addEmptyBtn ml-1">Add Empty</button>
                                ${Object.keys(clipboard).length > 0 ? `
                                    <button class="btn btn-outline-info pasteBtn ml-1">Paste</button>
                                ` : ''}
                            </section>
                        </div>
                    </div>
                </div>
            `);

            emptySlotDiv.find('.addModuleBtn').on('click', function () {
                addSlot(slotConfigDefault, rowId, slotId);
            });

            emptySlotDiv.find('.addEmptyBtn').on('click', function () {
                addSlot(emptyConfigDefault, rowId, slotId);
            });

            emptySlotDiv.find('.pasteBtn').on('click', function () {
                addSlot(clipboard, rowId, slotId);
                clipboard = {};

                renderModules();
            });

            return emptySlotDiv;
        }

        const getDummySlot = function (rowId, slotId, slot) {
            const dummySlotDiv = $(`
                <div class="block-wrapper">
                    <div class="block-control">
                        <div class="form-inline">
                            <button
                                class="btn btn-light btn-sm btn-edit-block"
                                type="button"
                            >
                                <i class="fas fa-cog"></i>
                            </button>
                            <button
                                class="btn btn-light btn-sm btn-move-block ml-1"
                                type="button"
                            >
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                            ${slot.type !== 'CPU' ? `
                                <button
                                    class="btn btn-light btn-sm btn-delete-block ml-1"
                                    type="button"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="block-container">
                        <div class="block-content solid-border ${slot.type}">
                            <div class="block-dummy-box">
                                <div class="d-block">
                                    ${slot.type === 'SLOT' ? `
                                        <span class="dot-values-overflow box-value">${getNumberSlot(rowId, slotId, slot)}</span>
                                    ` : ''}
                                    <span class="dot-values-overflow box-text">${slot.type}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            dummySlotDiv.find('.btn-delete-block').on('click', function () {
                removeSlot(rowId, slotId);
            });

            dummySlotDiv.find('.btn-move-block').on('click', function () {
                clipboard = modules[rowId][slotId];
                removeSlot(rowId, slotId);
            });

            return dummySlotDiv;
        }

        const getRowControl = function (rowId) {
            const $div = $(`
                <div class="row-control">
                    <div class="form-inline">
                        <button
                            class="btn btn-outline-secondary btn-sm btn-move-up mr-1"
                            type="button"
                        ><i class="fas fa-arrow-up"></i> <strong>Up</strong></button>
                        <button
                            class="btn btn-outline-secondary btn-sm btn-move-down mr-1 ml-1"
                            type="button"
                        ><i class="fas fa-arrow-down"></i> <strong>Down</strong></button>
                        <button
                            class="btn btn-outline-danger btn-sm btn-remove-row ml-1"
                            type="button"
                        ><i class="fas fa-trash"></i> <strong>Delete</strong></button>
                    </div>
                </div>
            `);

            if (rowId === 0) {
                $div.find('.btn-move-up').attr('disabled', true);
            }

            if (rowId === modules.length - 1) {
                $div.find('.btn-move-down').attr('disabled', true);
            }

            $div.find('.btn-move-up').on('click', function () {
                const targetRow = modules[rowId];
                modules[rowId] = modules[rowId - 1];
                modules[rowId - 1] = targetRow;

                renderModules();
            });

            $div.find('.btn-move-down').on('click', function () {
                const targetRow = modules[rowId];
                modules[rowId] = modules[rowId + 1];
                modules[rowId + 1] = targetRow;

                renderModules();
            });

            $div.find('.btn-remove-row').on('click', function () {
                const row = modules[rowId];
                for (const element of row) {
                    const currentSlot = element;
                    if (!currentSlot || currentSlot.type !== 'CPU') {
                        continue;
                    }

                    Swal.fire({
                        title: 'Error!',
                        text: 'Cannot delete row with CPU',
                        icon: 'error',
                    });
                    return;
                }
                modules.splice(rowId, 1);

                renderModules();
            });

            return $div;
        }

        const getNumberSlot = function (rowId, slotId, slot) {
            let number = 0;

            for (let i = 0; i < rowId; i++) {
                const row = modules[i];
                for (const element of row) {
                    const currentSlot = element;
                    if (!currentSlot || currentSlot.type !== 'SLOT') {
                        continue;
                    }
                    number++;
                }
            }

            for (let k = 0; k <= slotId; k++) {
                const currentSlot = modules[rowId][k];
                if (!currentSlot || currentSlot.type !== 'SLOT') {
                    continue;
                }
                number++;
            }

            return number;
        };

        const renderModules = function () {
            bulkCreate.empty();

            bulkCreate.append(addRowDiv(1, 0));
            modules.forEach((row, rowIndex) => {
                const rowCount = row.length;
                const colClass = 'col-' + (12 / rowCount);
                const rowDiv = $('<div class="row row-layout row-height-medium"></div>');
                const rowControlDiv = getRowControl(rowIndex);
                rowDiv.append(rowControlDiv);
                row.forEach((slot, slotId) => {
                    let slotDiv = null;
                    if (Object.keys(slot).length === 0 && slot.constructor === Object) {
                        slotDiv = addEmptySlot(rowIndex, slotId);
                    } else {
                        slotDiv = getDummySlot(rowIndex, slotId, slot);
                    }
                    const colDiv = $('<div class="' + colClass + '"></div>');
                    colDiv.append(slotDiv);
                    rowDiv.append(colDiv);
                });
                bulkCreate.append(rowDiv);
            });

            bulkCreate.append(addRowDiv(1, modules.length));
        }

        $('#saveBulkCreate').on('click', function () {
            const errors = validateModules();
            if (errors.length > 0) {
                let errorMessage = '';
                errors.forEach((error) => {
                    errorMessage += `Row ${error.row + 1} Slot ${error.slot + 1}: ${error.message}\n`;
                });
                alert(errorMessage);
                return;
            }

            const data = {
                'modules': modules,
                '_token': $('meta[name="csrf-token"]').attr('content'),
                '_method': 'PUT'
            };

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: options.saveRoute,
                        type: 'POST',
                        data: data,
                        success: function (response) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Locker has been created.',
                                icon: 'success',
                            }).then(() => {
                                window.location.href = '/lockers';
                            });
                        },
                        error: function (response) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong.',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });

        const validateModules = function () {
            const errors = [];
            modules.forEach((row, rowIndex) => {
                row.forEach((slot, slotId) => {
                    if (Object.keys(slot).length === 0 && slot.constructor === Object) {
                        errors.push({
                            'row': rowIndex,
                            'slot': slotId,
                            'message': 'Slot is empty',
                        });
                    }
                });
            });

            return errors;
        }

        this.init = function () {
            renderModules();
        }

        return this;
    }
})(jQuery)
