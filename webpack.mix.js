const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
mix.options({
    processCssUrls: false,
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Styling
mix.sass('resources/assets/style.scss', 'public/assets/css');
// Tailwind
mix.sass('resources/assets/tailwind.scss', 'public/assets/css').options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')],
});
// Tailwind - Minimal
mix.sass('resources/assets/minimal-tailwind.scss', 'public/assets/css').options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')],
});
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management (System)
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.sass('resources/assets/adminlte/css/app.scss', 'public/assets/adminlte/css').version();
mix.sass('resources/assets/adminlte/css/app-custom.scss', 'public/assets/adminlte/css').version();
mix.js('resources/assets/adminlte/js/app.js', 'public/assets/adminlte/js').version();
// mix.js('resources/assets/adminlte/js/app-custom.js', 'public/assets/adminlte/js').version();
// mix.js('resources/assets/adminlte/js/function.js', 'public/assets/adminlte/js').version();
mix.scripts([
    'resources/assets/adminlte/js/function.js'
], 'public/assets/adminlte/js/function.js').version();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management (Plugins)
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// FontAwesome
// mix.sass('resources/assets/adminlte/plugins/fontawesome-free/css/all.scss', 'public/assets/plugins/fontawesome-free/css').version();
mix.styles([
    'resources/assets/adminlte/plugins/fontawesome-free/css/all.css'
], 'public/assets/plugins/fontawesome-free/css/all.css').version();
// jQuery
mix.js('resources/assets/adminlte/plugins/jquery/jquery.js', 'public/assets/plugins/jquery').version();
// Moment Js
mix.copy('node_modules/moment/min/moment.min.js', 'public/assets/plugins/moment/dist/moment.js').version();
// Bootstrap
mix.copy('node_modules/bootstrap/dist/css/bootstrap.css', 'public/assets/plugins/bootstrap/css/bootstrap.css').version();
mix.js('resources/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.js', 'public/assets/plugins/bootstrap/js/').version();
mix.js('resources/assets/adminlte/plugins/bootstrap/js/bootstrap.js', 'public/assets/plugins/bootstrap/js/').version();
// Bootstrap Custom File INput
mix.js('resources/assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.js', 'public/assets/plugins/bs-custom-file-input/').version();
// Sweetalert2
mix.sass('resources/assets/adminlte/plugins/sweetalert2/css/sweetalert2.scss', 'public/assets/plugins/sweetalert2/css').version();
mix.sass('resources/assets/adminlte/plugins/sweetalert2/css/bootstrap-4.scss', 'public/assets/plugins/sweetalert2/css').version();
mix.js('resources/assets/adminlte/plugins/sweetalert2/js/sweetalert2.js', 'public/assets/plugins/sweetalert2/js').version();
// FontAwesome Iconpicker
mix.scripts([
    'resources/assets/adminlte/plugins/fontawesome-iconpicker/js/fontawesome-iconpicker.js'
], 'public/assets/plugins/fontawesome-iconpicker/js/fontawesome-iconpicker.js').version();
mix.styles([
    'resources/assets/adminlte/plugins/fontawesome-iconpicker/css/fontawesome-iconpicker.css'
], 'public/assets/plugins/fontawesome-iconpicker/css/fontawesome-iconpicker.css').version();
// Datatables
mix.copy('node_modules/admin-lte/plugins/datatables/jquery.dataTables.js', 'public/assets/plugins/datatables/datatables.js').version();
// Datatables BS4
mix.sass('resources/assets/adminlte/plugins/datatables/bs4/css/datatables.bs4.scss', 'public/assets/plugins/datatables/bs4/css').version();
mix.copy('node_modules/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js', 'public/assets/plugins/datatables/bs4/js/dataTables.bootstrap4.js').version();
// Datatables Responsive
mix.sass('resources/assets/adminlte/plugins/datatables/responsive/css/dataTables.responsive.scss', 'public/assets/plugins/datatables/responsive/css').version();
mix.copy('node_modules/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.js', 'public/assets/plugins/datatables/responsive/js/dataTables.responsive.js').version();
mix.copy('node_modules/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.js', 'public/assets/plugins/datatables/responsive/js/responsive.bootstrap4.js').version();
// Datatable Mark
mix.copy('node_modules/datatables.mark.js/dist/datatables.mark.js', 'public/assets/plugins/datatables/mark/js/datatables.mark.js').version();
mix.copy('node_modules/datatables.net-plugins/sorting/datetime-moment.js', 'public/assets/plugins/datatables/sort/js/datetime-moment.js').version();
// Datatable Select
mix.copy('node_modules/datatables.net-select/js/dataTables.select.js', 'public/assets/plugins/datatables/select/js/dataTables.select.js').version();
// Datatable Checkbox
mix.copy('node_modules/jquery-datatables-checkboxes/css/dataTables.checkboxes.css', 'public/assets/plugins/datatables/checkboxes/css/dataTables.checkboxes.css').version();
mix.copy('node_modules/jquery-datatables-checkboxes/js/dataTables.checkboxes.js', 'public/assets/plugins/datatables/checkboxes/js/dataTables.checkboxes.js').version();
// Select2
mix.copy('node_modules/admin-lte/plugins/select2/css/select2.css', 'public/assets/plugins/select2/css/select2.css').version();
mix.copy('node_modules/admin-lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.css', 'public/assets/plugins/select2/css/select2-bootstrap4.css').version();
mix.copy('node_modules/admin-lte/plugins/select2/js/select2.js', 'public/assets/plugins/select2/js/select2.js').version();
// CKEditor
mix.copy([
    'resources/assets/adminlte/plugins/ckeditor/build/ckeditor.js'
], 'public/assets/plugins/ckeditor/build/ckeditor.js').version();
mix.copy([
    'resources/assets/adminlte/plugins/ckeditor/build/ckeditor.js.map'
], 'public/assets/plugins/ckeditor/build/ckeditor.js.map').version();
// Tempusdominus Bootstrap4
mix.copy('node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.css', 'public/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css').version();
mix.copy('node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js', 'public/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js').version();
// jQuery Mark.js
mix.copy('node_modules/mark.js/dist/jquery.mark.js', 'public/assets/plugins/mark.js/jquery.mark.js').version();
// InputMask
mix.scripts([
    'resources/assets/adminlte/plugins/jquery.inputmask/jquery.inputmask.js'
], 'public/assets/plugins/input-mask/input-mask.js').version();
// OverlayScrollbars
mix.copy('node_modules/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.css', 'public/assets/plugins/overlayScrollbars/css/OverlayScrollbars.css').version()
mix.copy('node_modules/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.js', 'public/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.js').version()
// Lightcase
mix.styles([
'resources/assets/adminlte/plugins/lightcase/css/lightcase.css'
], 'public/assets/plugins/lightcase/css/lightcase.css').version();
mix.scripts([
'resources/assets/adminlte/plugins/lightcase/js/lightcase.js'
], 'public/assets/plugins/lightcase/js/lightcase.js').version();
// OwlCarousel
mix.copy([
'node_modules/owl.carousel/dist/assets/owl.carousel.css'
], 'public/assets/plugins/owl-carousel/css/owl-carousel.css').version();
mix.js([
'resources/assets/adminlte/plugins/owl-carousel/js/owl-carousel.js'
], 'public/assets/plugins/owl-carousel/js/owl-carousel.js').version();
// MatchHeight
mix.copy([
    'node_modules/jquery-match-height/dist/jquery.matchHeight-min.js'
], 'public/assets/plugins/jquery-match-height/js/jquery.matchHeight.min.js');
// Chart Js
mix.copy([
    'node_modules/admin-lte/plugins/chart.js/Chart.bundle.js'
], 'public/assets/plugins/chart/js/Chart.bundle.js');
mix.copy([
    'node_modules/admin-lte/plugins/chart.js/Chart.css'
], 'public/assets/plugins/chart/css/Chart.css');
// Chart Js 3.5
mix.copy([
    // 'node_modules/chart.js/dist/Chart.bundle.min.js',
    'node_modules/chart.js/dist/chart.min.js'
], 'public/assets/plugins/chart/3.5/js/chart.min.js');
// Color Picker
mix.copy([
    'node_modules/@eastdesire/jscolor/jscolor.min.js' 
], 'public/assets/plugins/jscolor/jscolor.min.js');
// Lightbox 2
mix.copyDirectory([
    'node_modules/lightbox2/dist/css'
], 'public/assets/plugins/lightbox2/css');
mix.copyDirectory([
    'node_modules/lightbox2/dist/images'
], 'public/assets/plugins/lightbox2/images');
mix.copyDirectory([
    'node_modules/lightbox2/dist/js'
], 'public/assets/plugins/lightbox2/js');

// Dashboard
mix.scripts([
'resources/assets/js/s.js'
], 'public/assets/js/s.js').version();