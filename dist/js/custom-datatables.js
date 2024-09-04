/******/ (() => { // webpackBootstrap
/*!******************************************!*\
  !*** ./src/scripts/custom-datatables.js ***!
  \******************************************/
// Custom JS file for FURI Symposium

jQuery(document).ready(function ($) {
  if ($('body').is('.tax-symposium_date, .tax-research_theme, .tax-presentation_type')) {
    $('.symposium-archive').dataTable({
      paging: false,
      dom: 'ift',
      columns: [{
        width: '40%'
      }, null, null, null, null],
      scrollX: true
    });
  }
  if ($('body').hasClass('page-template-data-dump')) {
    $('.data-dump').dataTable({
      paging: true
    });
  }
});
/******/ })()
;
//# sourceMappingURL=custom-datatables.js.map