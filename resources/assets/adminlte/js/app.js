// require('admin-lte/dist/js/adminlte');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap'); // Include AdminLTE
    require('admin-lte'); // Include AdminLTE
} catch (e) {}