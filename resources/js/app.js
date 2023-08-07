(function () {
    'use strict';

    let UTELocker = window.UTELocker = {};

    const modules = [
        'common',
    ];

    $(function () {
        modules.forEach(function (module) {
            if (UTELocker[module] && UTELocker[module] !== undefined) {
                UTELocker[module].init();
            }
        });
    })
})();
