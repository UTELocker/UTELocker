(function ($) {
    $.fn.bulkCreate = function (options) {
        const bulkCreate = this;
        const rowTypes = {
            1 : '12',
            2 : '6-6',
            3 : '4-4-4',
            4 : '3-3-3-3',
        }
        const lockerId = options.lockerId;
        const slotConfigDefault = {
            'type': 'SLOT'
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
            targetRow.splice(slotId, 1, module);

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
                                <button class="btn btn-outline-primary addModuleBtn">Add Slot</button>
                            </section>
                        </div>
                    </div>
                </div>
            `);

            emptySlotDiv.find('.addModuleBtn').on('click', function () {
                addSlot(slotConfigDefault, rowId, slotId);
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
                            ${slot.type === 'SLOT' ? `
                                <button
                                    class="btn btn-light btn-sm btn-delete-block"
                                    type="button"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="block-container">
                        <div class="block-content solid-border">
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

            return dummySlotDiv;
        }

        const getNumberSlot = function (rowId, slotId, slot) {
            let number = 0;

            for (let i = 0; i < rowId; i++) {
                const row = modules[i];
                for (let j = 0; j < row.length; j++) {
                    const currentSlot = row[j];
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

        this.init = function () {
            renderModules();
        }

        return this;
    }
})(jQuery)
