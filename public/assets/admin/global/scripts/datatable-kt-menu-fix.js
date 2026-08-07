/**
 * DataTables re-renders rows via AJAX on every draw, but Metronic's KTMenu widget
 * (used by the row "أدوات" tools dropdown) only creates instances for elements
 * present at page load. Without this, clicks on newly drawn rows' dropdown
 * triggers do nothing because KTMenu.getInstance() returns null for them.
 * Re-create KTMenu instances after every DataTable draw, on every admin page.
 */
$(document).on('draw.dt', function () {
    if (typeof KTMenu !== 'undefined') {
        KTMenu.createInstances('[data-kt-menu="true"]');
    }
});
