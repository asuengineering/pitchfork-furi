// Custom JS file for FURI Symposium

jQuery(document).ready(function ($) {
    if ($('body').hasClass('tax-symposium_date')) {
        $('.symposium-archive').dataTable({
            paging: false,
            dom: 'ift',
            columns: [{ width: '40%' }, null, null, null, null],
            scrollX: true
        });
    }

    if ($('body').hasClass('page-template-data-dump')) {
        $('.data-dump').dataTable({
            paging: true,
        });
    }
});
